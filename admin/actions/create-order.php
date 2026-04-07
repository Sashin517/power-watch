<?php
session_start();
header('Content-Type: application/json');

// Database connection
require_once '../config/connection.php';

// Response array
$response = ['success' => false, 'message' => ''];

try {
    // Validate required fields
    $required_fields = ['email', 'fname', 'lname', 'address', 'city', 'phone', 'paymentMethod', 'cart', 'subtotal', 'total'];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Sanitize inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $address = htmlspecialchars(trim($_POST['address']));
    $apartment = isset($_POST['apartment']) ? htmlspecialchars(trim($_POST['apartment'])) : '';
    $city = htmlspecialchars(trim($_POST['city']));
    $postal = isset($_POST['postal']) ? htmlspecialchars(trim($_POST['postal'])) : '';
    $phone = htmlspecialchars(trim($_POST['phone']));
    $payment_method = $_POST['paymentMethod'];
    
    // Validate payment method
    $valid_payments = ['card', 'koko', 'cod'];
    if (!in_array($payment_method, $valid_payments)) {
        throw new Exception("Invalid payment method");
    }

    // Parse cart items
    $cart_items = json_decode($_POST['cart'], true);
    if (!is_array($cart_items) || empty($cart_items)) {
        throw new Exception("Invalid cart data");
    }

    // Calculate amounts
    $subtotal = floatval($_POST['subtotal']);
    $shipping_cost = 0.00; // Free shipping
    $discount = 0.00;
    $total = floatval($_POST['total']);

    // Validate cart total
    $calculated_total = 0;
    foreach ($cart_items as $item) {
        $calculated_total += $item['price'] * $item['quantity'];
    }
    
    if (abs($calculated_total - $subtotal) > 0.01) {
        throw new Exception("Cart total mismatch");
    }

    // Get user ID if logged in
    $user_id = isset($_SESSION['u']['id']) ? $_SESSION['u']['id'] : null;

    // Start transaction
    Database::iud("START TRANSACTION");

    // Generate custom order number
    $order_number = generateOrderNumber($connection);

    // Insert order
    $order_query = "INSERT INTO orders (
        order_number, user_id, customer_email, customer_fname, customer_lname, customer_phone,
        shipping_address, shipping_apartment, shipping_city, shipping_postal,
        payment_method, subtotal, shipping_cost, discount_amount, total_amount,
        order_status, payment_status
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?
    )";

    $payment_status = ($payment_method === 'cod') ? 'unpaid' : 'pending';

    $stmt = $connection->prepare($order_query);
    $stmt->bind_param(
        "sisssssssssdddds",
        $order_number,
        $user_id,
        $email,
        $fname,
        $lname,
        $phone,
        $address,
        $apartment,
        $city,
        $postal,
        $payment_method,
        $subtotal,
        $shipping_cost,
        $discount,
        $total,
        $payment_status
    );

    if (!$stmt->execute()) {
        throw new Exception("Failed to create order: " . $stmt->error);
    }

    $order_id = $connection->insert_id;

    // Insert order items
    $item_query = "INSERT INTO order_items (
        order_id, product_id, product_name, product_image, product_price, quantity, product_options, item_total
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $item_stmt = $connection->prepare($item_query);

    foreach ($cart_items as $item) {
        $product_id = intval($item['id']);
        $product_name = htmlspecialchars($item['name']);
        $product_image = $item['image'];
        $product_price = floatval($item['price']);
        $quantity = intval($item['quantity']);
        $product_options = json_encode($item['options']);
        $item_total = $product_price * $quantity;

        $item_stmt->bind_param(
            "iissdiss",
            $order_id,
            $product_id,
            $product_name,
            $product_image,
            $product_price,
            $quantity,
            $product_options,
            $item_total
        );

        if (!$item_stmt->execute()) {
            throw new Exception("Failed to add order item: " . $item_stmt->error);
        }
    }

    // Commit transaction
    Database::iud("COMMIT");

    // Send success response
    $response['success'] = true;
    $response['message'] = 'Order placed successfully';
    $response['order_number'] = $order_number;
    $response['order_id'] = $order_id;

    // Optional: Send confirmation email here
    // sendOrderConfirmationEmail($email, $order_number, $order_id);

} catch (Exception $e) {
    // Rollback transaction on error
    Database::iud("ROLLBACK");
    
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    
    // Log error for debugging
    error_log("Order creation error: " . $e->getMessage());
}

echo json_encode($response);

/**
 * Generate custom order number in format #PWORD{number}
 */
function generateOrderNumber($connection) {
    // Get the last order number
    $result = $connection->query("SELECT order_number FROM orders ORDER BY order_id DESC LIMIT 1");
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_number = $row['order_number'];
        
        // Extract number from #PWORD{number}
        preg_match('/#PWORD(\d+)/', $last_number, $matches);
        $next_number = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
    } else {
        // First order
        $next_number = 1;
    }
    
    return '#PWORD' . $next_number;
}
?>
