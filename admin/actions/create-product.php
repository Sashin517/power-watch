<?php

require "../../includes/connection.php";

// 1. Collect Data
$product_name = $_POST["title"];
$brand_id = $_POST["brand"];
$sub_category_id = $_POST["category"]; 
$description = $_POST["desc"];
$original_price = (float)$_POST["oprice"];
$current_price = (float)$_POST["cprice"];
$stock_count = $_POST["qty"];

// Checkboxes
$is_luxury = (isset($_POST["luxury"]) && $_POST["luxury"] == 'true') ? 1 : 0;
$is_peoples_choice = (isset($_POST["choice"]) && $_POST["choice"] == 'true') ? 1 : 0;

// 2. Validation
if (empty($product_name)) { echo "Please enter the product name."; exit(); }
if (empty($brand_id) || $brand_id == "0") { echo "Please select a brand."; exit(); }
if (empty($sub_category_id) || $sub_category_id == "0") { echo "Please select a category."; exit(); }
if (empty($description)) { echo "Please enter a description."; exit(); }
if (empty($current_price) || $current_price <= 0) { echo "Please enter a valid current price."; exit(); }

// 3. Logic Calculations
$discount_percentage = 0;
if ($original_price > $current_price) {
    $discount_percentage = round((($original_price - $current_price) / $original_price) * 100);
}

$koko_installment = round($current_price / 3, 2);

// 4. Insert Product (Initially with default image)
try {
    $op_value = ($original_price > 0) ? "'".$original_price."'" : "NULL";
    $default_image = 'assets/images/products/default.png';

    // stock_count matches the HTML form name 'qty', schema name 'stock_count'
    // image_path defaults here, will update later if images are uploaded
    $query = "INSERT INTO `products` 
    (`product_name`, `description`, `brand_id`, `sub_category_id`, `original_price`, `current_price`, `discount_percentage`, `koko_installment`, `image_path`, `stock_count`, `is_luxury`, `is_peoples_choice`) 
    VALUES 
    ('".$product_name."', '".$description."', '".$brand_id."', '".$sub_category_id."', $op_value, '".$current_price."', '".$discount_percentage."', '".$koko_installment."', '".$default_image."','".$stock_count."', '".$is_luxury."', '".$is_peoples_choice."')";

    Database::iud($query);
    
    // GET THE NEW PRODUCT ID
    $product_id = Database::$connection->insert_id;

    // 5. Handle Multiple Images
    $uploaded_paths = [];
    
    if(isset($_FILES['images'])) {
        $allowed_types = array("image/jpg", "image/jpeg", "image/png", "image/webp");
        $file_count = count($_FILES['images']['name']);
        
        // Limit to 4 images
        $limit = ($file_count > 4) ? 4 : $file_count;

        for($i = 0; $i < $limit; $i++) {
            $file_type = $_FILES['images']['type'][$i];
            
            if(in_array($file_type, $allowed_types)) {
                $new_img_extension;
                if ($file_type == "image/jpg") $new_img_extension = ".jpg";
                elseif ($file_type == "image/jpeg") $new_img_extension = ".jpeg";
                elseif ($file_type == "image/png") $new_img_extension = ".png";
                elseif ($file_type == "image/webp") $new_img_extension = ".webp";

                // Unique name for each image
                $file_name = uniqid() . "_img" . $i . $new_img_extension;
                $target_dir = "../../assets/images/products/";
                $target_file = $target_dir . $file_name;
                $db_path = "assets/images/products/" . $file_name; // Path for DB

                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                if(move_uploaded_file($_FILES["images"]["tmp_name"][$i], $target_file)) {
                    $uploaded_paths[] = $db_path;
                    
                    // First image is primary
                    $is_primary = ($i == 0) ? 1 : 0;
                    
                    // Insert into product_images table
                    Database::iud("INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`) VALUES ('".$product_id."', '".$db_path."', '".$is_primary."')");
                }
            }
        }
    }

    // 6. Update Main Product Image Path (Set to the first uploaded image)
    if(count($uploaded_paths) > 0) {
        $main_image = $uploaded_paths[0];
        Database::iud("UPDATE `products` SET `image_path` = '".$main_image."' WHERE `product_id` = '".$product_id."'");
    }

    echo "success";

} catch (Exception $e) {
    echo "Error saving product: " . $e->getMessage();
}

?>