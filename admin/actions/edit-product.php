<?php
session_start();
require "../../includes/connection.php";
if(!isset($_SESSION["u"])){ echo "Unauthorized"; exit(); }

$product_id = $_POST["id"];
$product_name = addslashes($_POST["title"]);
$description = addslashes($_POST["desc"]);
$romance_copy = addslashes($_POST["romance_copy"]);

$diameter = !empty($_POST["diameter"]) ? (float)$_POST["diameter"] : 0;
$thickness = !empty($_POST["thickness"]) ? (float)$_POST["thickness"] : 0;
$water_atm = !empty($_POST["water_atm"]) ? (int)$_POST["water_atm"] : 0;

$materials = addslashes($_POST["materials"]);
$glass = addslashes($_POST["glass"]);
$movement = addslashes($_POST["movement"]);
$clasp = addslashes($_POST["clasp"]);
$warranty = !empty($_POST["warranty"]) ? (int)$_POST["warranty"] : 0;

$brand_id = $_POST["brand"];
$sub_category_id = $_POST["category"]; 
$original_price = (float)$_POST["oprice"];
$current_price = (float)$_POST["cprice"];
$stock_count = $_POST["qty"];

$is_premium = (isset($_POST["premium"]) && $_POST["premium"] == 'true') ? 1 : 0;
$is_peoples_choice = (isset($_POST["choice"]) && $_POST["choice"] == 'true') ? 1 : 0;

if (empty($product_id)) { echo "Product ID missing."; exit(); }
if (empty($product_name)) { echo "Please enter the product name."; exit(); }
if (empty($current_price) || $current_price <= 0) { echo "Please enter a valid current price."; exit(); }

$discount_percentage = ($original_price > $current_price) ? round((($original_price - $current_price) / $original_price) * 100) : 0;
$koko_installment = round($current_price / 3, 2);

// --- REPLACE FROM HERE ---
$op_value = ($original_price > 0) ? "'".$original_price."'" : "NULL";
$dia_val = ($diameter > 0) ? "'".$diameter."'" : "NULL";
$thick_val = ($thickness > 0) ? "'".$thickness."'" : "NULL";
$water_val = ($water_atm > 0) ? "'".$water_atm."'" : "NULL";
$warranty_val = ($warranty > 0) ? "'".$warranty."'" : "NULL"; // <--- Properly formats the quotes

try {
    // Notice that $warranty_val does NOT have single quotes around it in this query
    $query = "UPDATE `products` SET 
        `product_name` = '".$product_name."', 
        `description` = '".$description."', 
        `romance_copy` = '".$romance_copy."', 
        `case_diameter_mm` = $dia_val, 
        `case_thickness_mm` = $thick_val, 
        `materials` = '".$materials."', 
        `glass_type` = '".$glass."', 
        `water_resistance_atm` = $water_val, 
        `movement_details` = '".$movement."', 
        `clasp_type` = '".$clasp."', 
        `warranty_period` = $warranty_val, 
        `brand_id` = '".$brand_id."', 
        `sub_category_id` = '".$sub_category_id."', 
        `original_price` = $op_value, 
        `current_price` = '".$current_price."', 
        `discount_percentage` = '".$discount_percentage."', 
        `koko_installment` = '".$koko_installment."', 
        `stock_count` = '".$stock_count."', 
        `is_premium` = '".$is_premium."', 
        `is_peoples_choice` = '".$is_peoples_choice."' 
        WHERE `product_id` = '".$product_id."'";

    Database::iud($query);

    if(isset($_FILES['images']) && count($_FILES['images']['name']) > 0 && $_FILES['images']['name'][0] != "") {
        Database::iud("DELETE FROM `product_images` WHERE `product_id`='".$product_id."'");
        $allowed_types = array("image/jpg", "image/jpeg", "image/png", "image/webp");
        $limit = (count($_FILES['images']['name']) > 4) ? 4 : count($_FILES['images']['name']);
        $uploaded_paths = [];
        for($i = 0; $i < $limit; $i++) {
            $file_type = $_FILES['images']['type'][$i];
            if(in_array($file_type, $allowed_types)) {
                $ext = ($file_type == "image/jpg") ? ".jpg" : (($file_type == "image/jpeg") ? ".jpeg" : (($file_type == "image/png") ? ".png" : ".webp"));
                $file_name = uniqid() . "_img" . $i . $ext;
                $target_dir = "../../assets/images/products/";
                $db_path = "assets/images/products/" . $file_name;
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                if(move_uploaded_file($_FILES["images"]["tmp_name"][$i], $target_dir . $file_name)) {
                    $uploaded_paths[] = $db_path;
                    $is_primary = ($i == 0) ? 1 : 0;
                    Database::iud("INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`) VALUES ('".$product_id."', '".$db_path."', '".$is_primary."')");
                }
            }
        }
        if(count($uploaded_paths) > 0) {
            Database::iud("UPDATE `products` SET `image_path` = '".$uploaded_paths[0]."' WHERE `product_id` = '".$product_id."'");
        }
    }
    echo "success";
} catch (Exception $e) { echo "Error updating product: " . $e->getMessage(); }
?>