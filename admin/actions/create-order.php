<?php
ob_start();
session_start();
// Force response to be JSON so Javascript doesn't crash
header('Content-Type: application/json');

// 1. FIXED PATH: Only go up one level from 'actions' to reach 'includes'
require "../../includes/connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require "../../includes/PHPMailer/Exception.php";
require "../../includes/PHPMailer/PHPMailer.php";
require "../../includes/PHPMailer/SMTP.php";

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
    $valid_payments = ['bank transfer', 'cod'];
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

    // --- 3. SEND CONFIRMATION EMAIL VIA PHPMAILER ---
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        
        // Use your cPanel mail server, not Gmail!
        $mail->Host = 'mail.sldevs.web.lk'; 
        
        $mail->SMTPAuth = true;

        $mail->Username   = 'admin@sldevs.web.lk'; // The email created in cPanel
        $mail->Password   = 'sA26O&xG5Z}yxn9s'; // The password for that email
        
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('admin@sldevs.web.lk', 'Power Watch');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Order Confirmation - Power Watch (' . $order_number . ')';
        
        // Beautiful Email Template Matching Your Brand
        $formattedTotal = number_format($total, 2);
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #0A111F; color: #ffffff; border-radius: 10px; overflow: hidden;'>
            <div style='background-color: #D4AF37; padding: 20px; text-align: center;'>
                <h1 style='color: #000000; margin: 0; text-transform: uppercase; font-size: 24px;'>Order Received</h1>
            </div>
            <div style='padding: 30px; background-color: #111b2e;'>
                <h2 style='color: #D4AF37; margin-top: 0;'>Hello $fname,</h2>
                <p style='color: #f8f9fa; line-height: 1.6;'>Thank you for choosing Power Watch. Your order <strong>$order_number</strong> has been successfully placed.</p>
                
                <div style='background-color: #0A111F; padding: 20px; border-left: 4px solid #D4AF37; margin: 25px 0;'>
                    <h3 style='color: #e74c3c; margin-top: 0; font-size: 16px; text-transform: uppercase;'>Action Required: Payment</h3>
                    <p style='color: #adb5bd; font-size: 14px; margin-bottom: 15px;'>Please transfer <strong>LKR $formattedTotal</strong> to the account below and send us the receipt via WhatsApp.</p>
                    <p style='color: #f8f9fa; margin: 5px 0;'><strong>Bank:</strong> Bank of Ceylon</p>
                    <p style='color: #f8f9fa; margin: 5px 0;'><strong>Branch:</strong> Minuwangoda Branch (545)</p>
                    <p style='color: #f8f9fa; margin: 5px 0;'><strong>Account Name:</strong> MR R M S D RATNAYAKE</p>
                    <p style='color: #D4AF37; font-size: 18px; font-weight: bold; margin: 5px 0;'>Account No: 0003102670</p>
                </div>
                
                <p style='color: #adb5bd; font-size: 13px; text-align: center; margin-top: 30px;'>Orders are dispatched within 24 hours of payment confirmation.</p>
            </div>
        </div>";
        
        $mail->send();
    } catch (Exception $e) {
        // We log the error but DO NOT crash the checkout process! The user still sees the success page.
        error_log("Order email failed: " . $mail->ErrorInfo);
    }

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

ob_clean();

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