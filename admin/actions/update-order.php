<?php
session_start();
require "../../includes/connection.php";

// Basic security check
if (!isset($_SESSION["u"])) { die("error"); }
if (empty(Database::$connection)) { Database::setUpConnection(); }

if (isset($_POST['order_id']) && isset($_POST['column']) && isset($_POST['status'])) {
    
    $order_id = intval($_POST['order_id']);
    $column = $_POST['column'];
    $status = $_POST['status'];

    // Security: Only allow updating these specific columns to prevent SQL injection
    if ($column === 'order_status' || $column === 'payment_status') {
        
        $stmt = Database::$connection->prepare("UPDATE orders SET $column = ? WHERE order_id = ?");
        $stmt->bind_param("si", $status, $order_id);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>