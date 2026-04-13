<?php
session_start();
require "../../includes/connection.php";

if (!isset($_SESSION["u"])) { die(json_encode(["error" => "Unauthorized"])); }
if (empty(Database::$connection)) { Database::setUpConnection(); }

$query = "SELECT order_id, order_number, customer_fname, customer_lname, total_amount, payment_method, payment_status, order_status, created_at 
          FROM orders 
          ORDER BY order_id DESC";

$result = Database::$connection->query($query);
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

header('Content-Type: application/json');
echo json_encode($orders);
?>