<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

// Function to clean UTF-8
function utf8_clean($data) {
    if (is_array($data)) {
        return array_map('utf8_clean', $data);
    }
    if (is_string($data)) {
        return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
    }
    return $data;
}

try {
    require "../../includes/connection.php";
    
    // Set charset to UTF-8
    Database::setUpConnection();
    if (Database::$connection) {
        Database::$connection->set_charset("utf8mb4");
    }

    $products_data = [];

    // 1. Master Query using JOINs to combine tables efficiently
    $query = "
        SELECT 
            p.*, 
            b.brand_name, 
            b.brand_logo,
            sc.sub_category_name,
            c.category_name
        FROM products p
        LEFT JOIN brands b ON p.brand_id = b.brand_id
        LEFT JOIN sub_categories sc ON p.sub_category_id = sc.sub_category_id
        LEFT JOIN categories c ON sc.category_id = c.category_id
    ";

    $product_result = Database::search($query);

    if ($product_result === false) {
        throw new Exception('Database query failed');
    }

    if (mysqli_num_rows($product_result) > 0) {
        while ($row = mysqli_fetch_assoc($product_result)) {

            $product_id = intval($row['product_id']);

            // 2. Map the joined data cleanly
            $products_data[$product_id] = [
                'id' => $product_id,
                'name' => $row['product_name'] ?? '',
                'description' => $row['description'] ?? '',
                
                // Grouped Brand Data
                'brand' => [
                    'id' => isset($row['brand_id']) ? (int)$row['brand_id'] : 0,
                    'name' => $row['brand_name'] ?? '',
                    'logo' => $row['brand_logo'] ?? ''
                ],
                
                // Category Data
                'category' => $row['category_name'] ?? '',
                'sub_category' => $row['sub_category_name'] ?? '',
                
                // Grouped Pricing Data
                'pricing' => [
                    'original_price' => isset($row['original_price']) ? (float)$row['original_price'] : null,
                    'current_price' => (float)$row['current_price'],
                    'discount_percent' => (int)$row['discount_percentage'],
                    'koko_installment' => (float)$row['koko_installment']
                ],
                
                // Inventory & Flags
                'inventory' => [
                    'stock_count' => (int)$row['stock_count'],
                    'stock_status' => $row['stock_status'] ?? ''
                ],
                'is_luxury' => (bool)$row['is_luxury'],
                'is_peoples_choice' => (bool)$row['is_peoples_choice'],
                
                // Visuals
                'primary_thumbnail' => $row['image_path'] ?? '',
                'gallery' => []
            ];

            // 3. Fetch multiple images from the product_images table
            $img_res = Database::search("SELECT image_path, is_primary FROM product_images WHERE product_id = " . $product_id);
            if ($img_res && mysqli_num_rows($img_res) > 0) {
                while ($img_row = mysqli_fetch_assoc($img_res)) {
                    $products_data[$product_id]['gallery'][] = [
                        'url' => $img_row['image_path'] ?? '',
                        'is_main_gallery_image' => (bool)$img_row['is_primary']
                    ];
                }
            }
        }
    }

    // Clean UTF-8 issues
    $products_data = utf8_clean($products_data);

    // Convert to a flat array rather than an object keyed by ID (Better for frontend loops)
    $flat_array = array_values($products_data);

    // Encode with UTF-8 safety flags
    $json = json_encode(
        $flat_array, 
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR
    );
    
    if ($json === false) {
        // Fallback: try with simpler encoding
        $json = json_encode($flat_array);
    }

    echo $json;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage()
    ]);
}
?>