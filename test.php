<?php
session_start();
header('Content-Type: application/json');

require "../../includes/connection.php";
// Include PHPMailer files
require "../../includes/Exception.php";
require "../../includes/PHPMailer.php";
require "../../includes/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 0);

$response = ['success' => false, 'message' => ''];

try {
    if (empty(Database::$connection)) {
        Database::setUpConnection();
    }

    $required_fields = ['email', 'fname', 'lname', 'address', 'city', 'phone', 'paymentMethod', 'cart', 'subtotal', 'total'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("Missing required field: $field");
        }
    }

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $address = htmlspecialchars(trim($_POST['address']));
    $apartment = isset($_POST['apartment']) ? htmlspecialchars(trim($_POST['apartment'])) : '';
    $city = htmlspecialchars(trim($_POST['city']));
    $postal = isset($_POST['postal']) ? htmlspecialchars(trim($_POST['postal'])) : '';
    $phone = htmlspecialchars(trim($_POST['phone']));
    $payment_method = $_POST['paymentMethod'];
    
    $valid_payments = ['card', 'cod'];
    if (!in_array($payment_method, $valid_payments)) {
        throw new Exception("Invalid payment method.");
    }

    $cart_items = json_decode($_POST['cart'], true);
    if (!is_array($cart_items) || empty($cart_items)) {
        throw new Exception("Invalid cart data.");
    }

    $subtotal = floatval($_POST['subtotal']);
    $shipping_cost = 0.00; 
    $discount = 0.00;
    $total = floatval($_POST['total']);

    $calculated_total = 0;
    foreach ($cart_items as $item) {
        $calculated_total += floatval($item['price']) * intval($item['quantity']);
    }
    if (abs($calculated_total - $subtotal) > 0.01) {
        throw new Exception("Cart total mismatch. Please refresh and try again.");
    }

    $user_id = isset($_SESSION['u']['id']) ? intval($_SESSION['u']['id']) : null;

    // --- 1. START TRANSACTION ---
    Database::$connection->begin_transaction();

    $order_number = generateOrderNumber();
    $payment_status = 'unpaid';

    $order_query = "INSERT INTO orders (
        order_number, user_id, customer_email, customer_fname, customer_lname, customer_phone,
        shipping_address, shipping_apartment, shipping_city, shipping_postal,
        payment_method, subtotal, shipping_cost, discount_amount, total_amount,
        order_status, payment_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)";

    $stmt = Database::$connection->prepare($order_query);
    $stmt->bind_param("sisssssssssdddds", $order_number, $user_id, $email, $fname, $lname, $phone, $address, $apartment, $city, $postal, $payment_method, $subtotal, $shipping_cost, $discount, $total, $payment_status);

    if (!$stmt->execute()) { throw new Exception("Failed to create order summary."); }
    $order_id = Database::$connection->insert_id;

    $item_query = "INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, quantity, product_options, item_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $item_stmt = Database::$connection->prepare($item_query);

    foreach ($cart_items as $item) {
        $product_id = intval($item['id']);
        $product_name = htmlspecialchars($item['name']);
        
        $check_stmt = Database::$connection->prepare("SELECT product_id FROM products WHERE product_id = ?");
        $check_stmt->bind_param("i", $product_id);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows === 0) {
            throw new Exception("The item '" . $product_name . "' is no longer available.");
        }

        $product_image = $item['image'];
        $product_price = floatval($item['price']);
        $quantity = intval($item['quantity']);
        $product_options = json_encode($item['options']); 
        $item_total = $product_price * $quantity;

        $item_stmt->bind_param("iissdisd", $order_id, $product_id, $product_name, $product_image, $product_price, $quantity, $product_options, $item_total);
        if (!$item_stmt->execute()) { throw new Exception("Failed to add order items."); }
    }

    // --- 2. COMMIT TRANSACTION (Database Saved Successfully) ---
    Database::$connection->commit();

    // --- 3. SEND CONFIRMATION EMAIL VIA PHPMAILER ---
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        
        // ⚠️ PUT YOUR GMAIL AND APP PASSWORD HERE ⚠️
        $mail->Username = 'your_email@gmail.com'; 
        $mail->Password = 'your_app_password'; 
        
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('your_email@gmail.com', 'Power Watch');
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

    // --- 4. RETURN SUCCESS TO FRONTEND ---
    $response['success'] = true;
    $response['message'] = 'Order placed successfully';
    $response['order_number'] = $order_number;
    $response['order_id'] = $order_id;

} catch (Throwable $e) {
    if (!empty(Database::$connection)) { Database::$connection->rollback(); }
    $response['success'] = false;
    $response['message'] = "System Error: " . $e->getMessage();
    error_log("Order creation error: " . $e->getMessage());
}

echo json_encode($response);

function generateOrderNumber() {
    $result = Database::$connection->query("SELECT order_number FROM orders ORDER BY order_id DESC LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_number = $row['order_number'];
        preg_match('/#PWORD(\d+)/', $last_number, $matches);
        $next_number = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
    } else {
        $next_number = 1;
    }
    return '#PWORD' . $next_number;
}
?>