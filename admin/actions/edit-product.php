<?php
session_start();
require "../../includes/connection.php";

// Ensure the user is an admin
if(!isset($_SESSION["u"])){
    echo "Unauthorized";
    exit();
}

// 1. Collect Data
$product_id = $_POST["id"];
$product_name = $_POST["title"];
$brand_id = $_POST["brand"];
$sub_category_id = $_POST["category"]; 
$description = $_POST["desc"];
$original_price = (float)$_POST["oprice"];
$current_price = (float)$_POST["cprice"];
$stock_count = $_POST["qty"];

$is_luxury = (isset($_POST["luxury"]) && $_POST["luxury"] == 'true') ? 1 : 0;
$is_peoples_choice = (isset($_POST["choice"]) && $_POST["choice"] == 'true') ? 1 : 0;

// 2. Validation
if (empty($product_id)) { echo "Product ID missing."; exit(); }
if (empty($product_name)) { echo "Please enter the product name."; exit(); }
if (empty($brand_id) || $brand_id == "0") { echo "Please select a brand."; exit(); }
if (empty($sub_category_id) || $sub_category_id == "0") { echo "Please select a category."; exit(); }
if (empty($current_price) || $current_price <= 0) { echo "Please enter a valid current price."; exit(); }

// 3. Logic Calculations
$discount_percentage = 0;
if ($original_price > $current_price) {
    $discount_percentage = round((($original_price - $current_price) / $original_price) * 100);
}
$koko_installment = round($current_price / 3, 2);
$op_value = ($original_price > 0) ? "'".$original_price."'" : "NULL";

try {
    // 4. Update Main Product Details
    $query = "UPDATE `products` SET 
        `product_name` = '".$product_name."', 
        `description` = '".$description."', 
        `brand_id` = '".$brand_id."', 
        `sub_category_id` = '".$sub_category_id."', 
        `original_price` = $op_value, 
        `current_price` = '".$current_price."', 
        `discount_percentage` = '".$discount_percentage."', 
        `koko_installment` = '".$koko_installment."', 
        `stock_count` = '".$stock_count."', 
        `is_luxury` = '".$is_luxury."', 
        `is_peoples_choice` = '".$is_peoples_choice."' 
        WHERE `product_id` = '".$product_id."'";

    Database::iud($query);

    // 5. Handle New Images (Optional during edit)
    // If the user uploaded new images, we delete the old ones and add the new ones
    if(isset($_FILES['images']) && count($_FILES['images']['name']) > 0 && $_FILES['images']['name'][0] != "") {
        
        // Remove old images from database (Optional: unlink from server to save space)
        Database::iud("DELETE FROM `product_images` WHERE `product_id`='".$product_id."'");

        $allowed_types = array("image/jpg", "image/jpeg", "image/png", "image/webp");
        $file_count = count($_FILES['images']['name']);
        $limit = ($file_count > 4) ? 4 : $file_count;
        $uploaded_paths = [];

        for($i = 0; $i < $limit; $i++) {
            $file_type = $_FILES['images']['type'][$i];
            
            if(in_array($file_type, $allowed_types)) {
                $new_img_extension = "";
                if ($file_type == "image/jpg") $new_img_extension = ".jpg";
                elseif ($file_type == "image/jpeg") $new_img_extension = ".jpeg";
                elseif ($file_type == "image/png") $new_img_extension = ".png";
                elseif ($file_type == "image/webp") $new_img_extension = ".webp";

                $file_name = uniqid() . "_img" . $i . $new_img_extension;
                $target_dir = "../../assets/images/products/";
                $target_file = $target_dir . $file_name;
                $db_path = "assets/images/products/" . $file_name;

                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                if(move_uploaded_file($_FILES["images"]["tmp_name"][$i], $target_file)) {
                    $uploaded_paths[] = $db_path;
                    $is_primary = ($i == 0) ? 1 : 0;
                    Database::iud("INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`) VALUES ('".$product_id."', '".$db_path."', '".$is_primary."')");
                }
            }
        }

        // Update the main thumbnail path if new images were successfully uploaded
        if(count($uploaded_paths) > 0) {
            Database::iud("UPDATE `products` SET `image_path` = '".$uploaded_paths[0]."' WHERE `product_id` = '".$product_id."'");
        }
    }

    echo "success";

} catch (Exception $e) {
    echo "Error updating product: " . $e->getMessage();
}
?>