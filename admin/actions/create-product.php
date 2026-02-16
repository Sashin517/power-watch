<?php

require "../../includes/connection.php";

// 1. Collect Data
$product_name = $_POST["title"];
$brand_id = $_POST["brand"];
$sub_category_id = $_POST["category"]; // Maps to sub_category_id in DB
$description = $_POST["desc"];
$original_price = (float)$_POST["oprice"];
$current_price = (float)$_POST["cprice"];
$stock_status = $_POST["stock"];

// Checkboxes (sent as 'true' string or not set)
$is_luxury = (isset($_POST["luxury"]) && $_POST["luxury"] == 'true') ? 1 : 0;
$is_peoples_choice = (isset($_POST["choice"]) && $_POST["choice"] == 'true') ? 1 : 0;

// 2. Validation
if (empty($product_name)) {
    echo "Please enter the product name.";
    exit();
}
if (empty($brand_id)) {
    echo "Please select a brand.";
    exit();
}
if (empty($sub_category_id)) {
    echo "Please select a category.";
    exit();
}
if (empty($description)) {
    echo "Please enter a description.";
    exit();
}
if (empty($current_price) || $current_price <= 0) {
    echo "Please enter a valid current price.";
    exit();
}

// 3. Logic Calculations
$discount_percentage = 0;
if ($original_price > $current_price) {
    $discount_percentage = round((($original_price - $current_price) / $original_price) * 100);
}

$koko_installment = round($current_price / 3, 2);

// 4. Image Handling
$image_path = 'assets/images/products/default.png'; // Default fallback

if (isset($_FILES["image"]["name"])) {
    $allowed_types = array("image/jpg", "image/jpeg", "image/png", "image/webp");
    $file_type = $_FILES["image"]["type"];

    if (in_array($file_type, $allowed_types)) {
        $new_img_extension;
        if ($file_type == "image/jpg") {
            $new_img_extension = ".jpg";
        } elseif ($file_type == "image/jpeg") {
            $new_img_extension = ".jpeg";
        } elseif ($file_type == "image/png") {
            $new_img_extension = ".png";
        } elseif ($file_type == "image/webp") {
            $new_img_extension = ".webp";
        }

        $file_name = "assets/images/products/" . uniqid() . $new_img_extension;
        
        // Ensure directory exists
        if (!file_exists("assets/images/products/")) {
            mkdir("assets/images/products/", 0777, true);
        }

        move_uploaded_file($_FILES["image"]["tmp_name"], $file_name);
        $image_path = $file_name;
    } else {
        echo "Invalid image file type. Please upload JPG, PNG, or WEBP.";
        exit();
    }
}

// 5. Database Insertion
try {
    // Note: original_price can be null in DB if 0
    $op_value = ($original_price > 0) ? "'".$original_price."'" : "NULL";

    $query = "INSERT INTO `products` 
    (`product_name`, `description`, `brand_id`, `sub_category_id`, `original_price`, `current_price`, `discount_percentage`, `koko_installment`, `image_path`, `stock_status`, `is_luxury`, `is_peoples_choice`) 
    VALUES 
    ('".$product_name."', '".$description."', '".$brand_id."', '".$sub_category_id."', $op_value, '".$current_price."', '".$discount_percentage."', '".$koko_installment."', '".$image_path."', '".$stock_status."', '".$is_luxury."', '".$is_peoples_choice."')";

    Database::iud($query);
    echo "success";

} catch (Exception $e) {
    echo "Error saving product: " . $e->getMessage();
}

?>