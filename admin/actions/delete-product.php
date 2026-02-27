<?php
session_start();
require "../../includes/connection.php";

// Ensure the user is an admin (Optional but recommended security)
if(!isset($_SESSION["u"])){
    echo "Unauthorized";
    exit();
}

if(isset($_POST["id"])){
    $product_id = $_POST["id"];

    try {
        // Optional: If you want to physically delete the image files from the server, 
        // you would SELECT them here and use unlink() before deleting the database rows.
        /*
        $img_rs = Database::search("SELECT image_path FROM `product_images` WHERE product_id='".$product_id."'");
        while($img_data = $img_rs->fetch_assoc()) {
            $file_path = "../../" . $img_data['image_path'];
            if(file_exists($file_path) && strpos($file_path, 'default.png') === false) {
                unlink($file_path);
            }
        }
        */

        // 1. Delete associated images from product_images table
        Database::iud("DELETE FROM `product_images` WHERE `product_id`='".$product_id."'");

        // 2. Delete the product itself
        Database::iud("DELETE FROM `products` WHERE `product_id`='".$product_id."'");

        echo "success";

    } catch (Exception $e) {
        echo "Error deleting product: " . $e->getMessage();
    }

} else {
    echo "Product ID not provided.";
}
?>