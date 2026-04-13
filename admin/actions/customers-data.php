<?php
session_start();
require "../../includes/connection.php";

if (!isset($_SESSION["u"])) { die(json_encode(["error" => "Unauthorized"])); }
if (empty(Database::$connection)) { Database::setUpConnection(); }

// Group orders by email to calculate total lifetime value of each customer
$query = "SELECT 
            customer_email as email, 
            MAX(customer_fname) as fname, 
            MAX(customer_lname) as lname, 
            COUNT(order_id) as order_count, 
            SUM(total_amount) as total_spent
          FROM orders 
          GROUP BY customer_email 
          ORDER BY total_spent DESC";

$result = Database::$connection->query($query);
$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($customers);
?>