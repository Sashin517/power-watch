<?php
require "../../includes/connection.php";

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
$stock_count = (int)$_POST["qty"];

$is_luxury = (isset($_POST["luxury"]) && $_POST["luxury"] == 'true') ? 1 : 0;
$is_peoples_choice = (isset($_POST["choice"]) && $_POST["choice"] == 'true') ? 1 : 0;

if (empty($product_name)) { echo "Please enter the product name."; exit(); }
if (empty($brand_id) || $brand_id == "0") { echo "Please select a brand."; exit(); }
if (empty($sub_category_id) || $sub_category_id == "0") { echo "Please select a category."; exit(); }
if (empty($current_price) || $current_price <= 0) { echo "Please enter a valid current price."; exit(); }

$discount_percentage = ($original_price > $current_price) ? round((($original_price - $current_price) / $original_price) * 100) : 0;
$koko_installment = round($current_price / 3, 2);

try {
    $op_value = ($original_price > 0) ? "'".$original_price."'" : "NULL";
    $dia_val = ($diameter > 0) ? "'".$diameter."'" : "NULL";
    $thick_val = ($thickness > 0) ? "'".$thickness."'" : "NULL";
    $water_val = ($water_atm > 0) ? "'".$water_atm."'" : "NULL";
    $warranty_val = ($warranty > 0) ? "'".$warranty."'" : "NULL"; // <--- Properly formats the quotes
    $default_image = 'assets/images/products/default.png';

    // Notice that $warranty_val does NOT have single quotes around it
    $query = "INSERT INTO `products` 
    (`product_name`, `description`, `romance_copy`, `case_diameter_mm`, `case_thickness_mm`, `materials`, `glass_type`, `water_resistance_atm`, `movement_details`, `clasp_type`, `warranty_period`, `brand_id`, `sub_category_id`, `original_price`, `current_price`, `discount_percentage`, `koko_installment`, `image_path`, `stock_count`, `is_luxury`, `is_peoples_choice`) 
    VALUES 
    ('".$product_name."', '".$description."', '".$romance_copy."', $dia_val, $thick_val, '".$materials."', '".$glass."', $water_val, '".$movement."', '".$clasp."', $warranty_val, '".$brand_id."', '".$sub_category_id."', $op_value, '".$current_price."', '".$discount_percentage."', '".$koko_installment."', '".$default_image."','".$stock_count."', '".$is_luxury."', '".$is_peoples_choice."')";

    Database::iud($query);

    Database::setUpConnection(); 
    $product_id = Database::$connection->insert_id;

    $uploaded_paths = [];
    if(isset($_FILES['images'])) {
        $allowed_types = array("image/jpg", "image/jpeg", "image/png", "image/webp");
        $limit = (count($_FILES['images']['name']) > 4) ? 4 : count($_FILES['images']['name']);
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
    }

    if(count($uploaded_paths) > 0) {
        Database::iud("UPDATE `products` SET `image_path` = '".$uploaded_paths[0]."' WHERE `product_id` = '".$product_id."'");
    }
    echo "success";
} catch (Exception $e) { echo "Error saving product: " . $e->getMessage(); }
?>