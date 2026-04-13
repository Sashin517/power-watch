<?php
session_start();
// Force response to be JSON so Javascript doesn't crash
header('Content-Type: application/json');

// 1. FIXED PATH: Only go up one level from 'actions' to reach 'includes'
require "../../includes/connection.php";

// Force all errors to log silently in the background instead of breaking the JSON output
error_reporting(E_ALL);
ini_set('display_errors', 0);

$response = ['success' => false, 'message' => ''];

try {
    // 2. CRITICAL FIX: Establish Database Connection First!
    if (empty(Database::$connection)) {
        Database::setUpConnection();
    }

    // Validate required fields
    $required_fields = ['email', 'fname', 'lname', 'address', 'city', 'phone', 'paymentMethod', 'cart', 'subtotal', 'total'];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
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
    $valid_payments = ['card', 'cod'];
    if (!in_array($payment_method, $valid_payments)) {
        throw new Exception("Invalid payment method.");
    }

    // Parse cart items
    $cart_items = json_decode($_POST['cart'], true);
    if (!is_array($cart_items) || empty($cart_items)) {
        throw new Exception("Invalid cart data.");
    }

    // Calculate amounts
    $subtotal = floatval($_POST['subtotal']);
    $shipping_cost = 0.00; // Free shipping
    $discount = 0.00;
    $total = floatval($_POST['total']);

    // Validate cart total security check
    $calculated_total = 0;
    foreach ($cart_items as $item) {
        $calculated_total += floatval($item['price']) * intval($item['quantity']);
    }
    
    if (abs($calculated_total - $subtotal) > 0.01) {
        throw new Exception("Cart total mismatch. Please refresh and try again.");
    }

    // Get user ID if logged in (safely handle NULL)
    $user_id = isset($_SESSION['u']['id']) ? intval($_SESSION['u']['id']) : null;

    // 3. START TRANSACTION using the strict MySQLi method
    Database::$connection->begin_transaction();

    // Generate custom order number
    $order_number = generateOrderNumber();

    // Insert order
    $order_query = "INSERT INTO orders (
        order_number, user_id, customer_email, customer_fname, customer_lname, customer_phone,
        shipping_address, shipping_apartment, shipping_city, shipping_postal,
        payment_method, subtotal, shipping_cost, discount_amount, total_amount,
        order_status, payment_status
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?
    )";

    // The database ENUM only allows 'unpaid', 'paid', or 'refunded'.
    // Since payment hasn't been processed by a gateway yet, all new orders start as unpaid.
    $payment_status = 'unpaid';

    // 4. FIXED: Properly reference the static Database connection
    $stmt = Database::$connection->prepare($order_query);
    
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
        throw new Exception("Failed to create order summary.");
    }

    // Get the newly created Order ID
    $order_id = Database::$connection->insert_id;

    // Insert order items
    $item_query = "INSERT INTO order_items (
        order_id, product_id, product_name, product_image, product_price, quantity, product_options, item_total
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $item_stmt = Database::$connection->prepare($item_query);

    foreach ($cart_items as $item) {
        $product_id = intval($item['id']);
        $product_name = htmlspecialchars($item['name']);
        
        $check_stmt = Database::$connection->prepare("SELECT product_id FROM products WHERE product_id = ?");
        $check_stmt->bind_param("i", $product_id);
        $check_stmt->execute();
        
        if ($check_stmt->get_result()->num_rows === 0) {
            throw new Exception("The item '" . $product_name . "' is no longer available in our catalog. Please remove it from your cart and try again.");
        }

        $product_image = $item['image'];
        $product_price = floatval($item['price']);
        $quantity = intval($item['quantity']);
        $product_options = json_encode($item['options']); // Your DB strictly requires JSON here
        $item_total = $product_price * $quantity;

        // 5. FIXED BINDING TYPES: i=integer, s=string, d=double(decimal)
        $item_stmt->bind_param(
            "iissdisd", 
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
            throw new Exception("Failed to add order items.");
        }
    }

    // 6. COMMIT TRANSACTION: Everything succeeded!
    Database::$connection->commit();

    // Send success response back to javascript
    $response['success'] = true;
    $response['message'] = 'Order placed successfully';
    $response['order_number'] = $order_number;
    $response['order_id'] = $order_id;

} catch (Throwable $e) {
    // 7. FATAL ERROR SAFETY NET: If anything fails, rollback the database so half-empty orders aren't saved
    if (!empty(Database::$connection)) {
        Database::$connection->rollback();
    }
    
    $response['success'] = false;
    $response['message'] = "System Error: " . $e->getMessage();
    
    // Log the actual technical error to your server logs behind the scenes
    error_log("Order creation error: " . $e->getMessage());
}

// Print the final JSON for the Javascript to read
echo json_encode($response);


/**
 * Generate custom order number in format #PWORD{number}
 */
function generateOrderNumber() {
    // FIXED: Properly reference the static Database connection
    $result = Database::$connection->query("SELECT order_number FROM orders ORDER BY order_id DESC LIMIT 1");
    
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