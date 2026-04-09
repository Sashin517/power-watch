<?php
session_start();
require_once 'includes/connection.php';

// Get filters from URL
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$price_min = isset($_GET['price_min']) ? intval($_GET['price_min']) : 0;
$price_max = isset($_GET['price_max']) ? intval($_GET['price_max']) : 999999;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build query
$where_clauses = [];
$params = [];
$types = '';

// Category filter
if ($category !== 'all') {
    $where_clauses[] = "sc.sub_category_name = ?";
    $params[] = $category;
    $types .= 's';
}

// Brand filter
if (!empty($brand)) {
    $where_clauses[] = "b.brand_name = ?";
    $params[] = $brand;
    $types .= 's';
}

// Price range
$where_clauses[] = "p.current_price BETWEEN ? AND ?";
$params[] = $price_min;
$params[] = $price_max;
$types .= 'ii';

// Search
if (!empty($search)) {
    $where_clauses[] = "(p.product_name LIKE ? OR p.description LIKE ? OR b.brand_name LIKE ?)";
    $search_term = "%{$search}%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= 'sss';
}

// Sort
$order_by = 'p.created_at DESC'; // default: newest
switch($sort) {
    case 'price_low':
        $order_by = 'p.current_price ASC';
        break;
    case 'price_high':
        $order_by = 'p.current_price DESC';
        break;
    case 'name_az':
        $order_by = 'p.product_name ASC';
        break;
    case 'discount':
        $order_by = 'p.discount_percentage DESC';
        break;
}

$where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

// Fetch products
$query = "SELECT 
    p.product_id as id,
    p.product_name as name,
    p.description,
    b.brand_name as brand,
    b.brand_id,
    sc.sub_category_name as category,
    p.original_price,
    p.current_price as price,
    p.discount_percentage as discount,
    p.stock_status,
    p.is_luxury,
    p.is_peoples_choice,
    p.image_path as image
FROM products p
LEFT JOIN brands b ON p.brand_id = b.brand_id
LEFT JOIN sub_categories sc ON p.sub_category_id = sc.sub_category_id
{$where_sql}
ORDER BY {$order_by}";

$stmt = Database::$connection->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Get all brands for filter
$brands_query = "SELECT DISTINCT brand_name FROM brands ORDER BY brand_name";
$brands_result = Database::$connection->query($brands_query);
$all_brands = [];
while ($b = $brands_result->fetch_assoc()) {
    $all_brands[] = $b['brand_name'];
}

// Get all categories
$categories = [
    "Men's Watches",
    "Women's Watches",
    "Smart Watches",
    "Luxury Collection",
    "Wall Clocks",
    "Photo Frames",
    "Wall Art",
    "Mirrors"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Power Watch Collection</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --prm-blue: #0A111F;
            --sec-blue: #111b2e;
            --chp-gold: #D4AF37;
            --chp-gold-hover: #b5952f;
            --text-light: #f8f9fa;
            --text-muted: #adb5bd;
            --border-color: #2d3748;
            --input-bg: #1a2332;
            --card-bg: #394150;
            --discount-green: #08CB00;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--prm-blue);
            color: var(--text-light);
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        .text-gold { color: var(--chp-gold); }
        .text-muted { color: var(--text-muted) !important; }

        /* === TOP BAR & NAVBAR === */
        .top-bar {
            background-color: #C8A030;
            color: #000;
            padding: 8px 0;
            overflow: hidden;
        }

        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            display: flex;
        }

        .marquee-content {
            display: flex;
            animation: marquee 20s linear infinite;
            flex-shrink: 0;
        }

        .marquee-content span {
            padding: 0 50px;
            flex-shrink: 0;
            font-weight: 600;
            font-size: 0.85rem;
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        .navbar {
            background-color: var(--prm-blue);
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
        }

        .brand-logo-img {
            max-height: 50px;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            margin: 0 10px;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: 0.3s;
            position: relative;
        }

        .nav-link:hover {
            color: var(--chp-gold) !important;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--chp-gold);
            color: #000;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* === PAGE HEADER === */
        .page-header {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(212, 175, 55, 0.05));
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            padding: 2.5rem 0;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* === COLLECTION LAYOUT === */
        .collection-wrapper {
            display: flex;
            gap: 2rem;
            padding: 2rem 0;
        }

        /* === SIDEBAR FILTERS === */
        .filter-sidebar {
            width: 280px;
            flex-shrink: 0;
        }

        .filter-card {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-title {
            font-size: 1rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-title i {
            color: var(--chp-gold);
        }

        .filter-option {
            display: flex;
            align-items: center;
            padding: 0.6rem 0;
            cursor: pointer;
            transition: 0.2s;
        }

        .filter-option:hover {
            color: var(--chp-gold);
        }

        .filter-option input[type="checkbox"],
        .filter-option input[type="radio"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: var(--chp-gold);
            cursor: pointer;
        }

        .filter-option label {
            cursor: pointer;
            flex-grow: 1;
            font-size: 0.9rem;
        }

        .filter-count {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .price-range-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .price-input {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
            padding: 0.6rem 0.75rem;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .price-input:focus {
            outline: none;
            border-color: var(--chp-gold);
        }

        .btn-apply-filters {
            background: var(--chp-gold);
            color: #000;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            width: 100%;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            margin-top: 1rem;
        }

        .btn-apply-filters:hover {
            background: var(--chp-gold-hover);
            transform: translateY(-2px);
        }

        .btn-clear-filters {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            padding: 0.6rem;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: 0.3s;
            margin-top: 0.75rem;
        }

        .btn-clear-filters:hover {
            border-color: var(--chp-gold);
            color: var(--chp-gold);
        }

        /* === MOBILE FILTER BUTTON === */
        .mobile-filter-btn {
            display: none;
            background: var(--sec-blue);
            border: 1px solid var(--chp-gold);
            color: var(--chp-gold);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        /* === PRODUCTS AREA === */
        .products-area {
            flex-grow: 1;
            min-width: 0;
        }

        .toolbar {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .results-count {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .results-count strong {
            color: var(--chp-gold);
            font-weight: 700;
        }

        .toolbar-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .view-toggle {
            display: flex;
            gap: 0.5rem;
        }

        .view-btn {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            width: 40px;
            height: 40px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .view-btn.active,
        .view-btn:hover {
            border-color: var(--chp-gold);
            color: var(--chp-gold);
            background: rgba(212, 175, 55, 0.1);
        }

        .sort-select {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
            padding: 0.6rem 2.5rem 0.6rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23adb5bd' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
        }

        .sort-select:focus {
            outline: none;
            border-color: var(--chp-gold);
        }

        /* === PRODUCT GRID === */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .products-grid.list-view {
            grid-template-columns: 1fr;
        }

        .product-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.5);
            border-color: var(--chp-gold);
        }

        .product-image-wrapper {
            background: white;
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .product-image-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s;
        }

        .product-card:hover .product-image-wrapper img {
            transform: scale(1.08);
        }

        .badge-overlay {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .discount-badge {
            background: linear-gradient(135deg, #990000, #cc0000);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.75rem;
            box-shadow: 0 4px 12px rgba(153,0,0,0.4);
        }

        .luxury-badge {
            background: linear-gradient(135deg, var(--chp-gold), var(--chp-gold-hover));
            color: #000;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.7rem;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        .stock-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.7rem;
        }

        .stock-badge.in-stock {
            background: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
            border: 1px solid rgba(46, 204, 113, 0.3);
        }

        .stock-badge.low-stock {
            background: rgba(243, 156, 18, 0.15);
            color: #f39c12;
            border: 1px solid rgba(243, 156, 18, 0.3);
        }

        .stock-badge.out-stock {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-brand {
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .product-name {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            min-height: 50px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price-row {
            display: flex;
            align-items: baseline;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .product-price {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--chp-gold);
            font-family: 'Oswald', sans-serif;
        }

        .product-price-old {
            font-size: 1rem;
            color: var(--text-muted);
            text-decoration: line-through;
        }

        .product-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-add-cart {
            background: var(--chp-gold);
            color: #000;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            flex-grow: 1;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-add-cart:hover {
            background: var(--chp-gold-hover);
            transform: translateY(-2px);
        }

        .btn-add-cart:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-view {
            background: transparent;
            border: 2px solid var(--chp-gold);
            color: var(--chp-gold);
            padding: 0.75rem;
            border-radius: 8px;
            width: 45px;
            transition: 0.3s;
        }

        .btn-view:hover {
            background: var(--chp-gold);
            color: #000;
        }

        /* === LIST VIEW === */
        .products-grid.list-view .product-card {
            display: grid;
            grid-template-columns: 250px 1fr;
        }

        .products-grid.list-view .product-image-wrapper {
            height: 100%;
        }

        .products-grid.list-view .product-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* === EMPTY STATE === */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-muted);
            opacity: 0.3;
            margin-bottom: 1.5rem;
        }

        .empty-state h3 {
            color: white;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: var(--text-muted);
        }

        /* === PAGINATION === */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .pagination .page-link {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 0.6rem 1rem;
            border-radius: 6px;
            transition: 0.2s;
        }

        .pagination .page-link:hover {
            background: rgba(212, 175, 55, 0.1);
            border-color: var(--chp-gold);
            color: var(--chp-gold);
        }

        .pagination .page-item.active .page-link {
            background: var(--chp-gold);
            border-color: var(--chp-gold);
            color: #000;
        }

        /* === RESPONSIVE === */
        @media (max-width: 991px) {
            .collection-wrapper {
                flex-direction: column;
            }

            .filter-sidebar {
                width: 100%;
            }

            .mobile-filter-btn {
                display: block;
            }

            .filter-sidebar.desktop-only {
                display: none;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 1.5rem;
            }

            .products-grid.list-view {
                grid-template-columns: 1fr;
            }

            .products-grid.list-view .product-card {
                grid-template-columns: 180px 1fr;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 2rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar-controls {
                justify-content: space-between;
            }

            .products-grid.list-view .product-card {
                grid-template-columns: 1fr;
            }

            .products-grid.list-view .product-image-wrapper {
                height: 250px;
            }
        }

        /* TOAST POSITIONING */
        .minimal-toast-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            max-width: 90%;
            width: auto;
        }

        @media (min-width: 576px) {
            .minimal-toast-container {
                max-width: 500px;
            }
        }
    </style>
</head>
<body>

    <?php include 'components/navbar.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title">Watch Collection</h1>
                    <p class="page-subtitle">Discover timeless elegance and precision</p>
                </div>
                <div class="text-gold fw-bold" style="font-size: 1.2rem;">
                    <i class="fas fa-clock me-2"></i><?php echo count($products); ?> Products
                </div>
            </div>
        </div>
    </section>

    <!-- Collection Section -->
    <section class="py-4">
        <div class="container">
            
            <!-- Mobile Filter Toggle -->
            <button class="mobile-filter-btn" data-bs-toggle="collapse" data-bs-target="#mobileFilters">
                <i class="fas fa-filter me-2"></i>Filters & Search
            </button>

            <div class="collection-wrapper">
                
                <!-- Sidebar Filters -->
                <aside class="filter-sidebar desktop-only" id="desktopFilters">
                    
                    <!-- Search -->
                    <div class="filter-card">
                        <div class="filter-title">
                            <i class="fas fa-search"></i>Search
                        </div>
                        <input type="text" id="searchInput" class="price-input w-100" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>

                    <!-- Categories -->
                    <div class="filter-card">
                        <div class="filter-title">
                            <i class="fas fa-th-large"></i>Categories
                        </div>
                        <?php foreach ($categories as $cat): ?>
                        <div class="filter-option">
                            <input type="radio" name="category" value="<?php echo htmlspecialchars($cat); ?>" id="cat-<?php echo str_replace(' ', '-', $cat); ?>" <?php echo $category === $cat ? 'checked' : ''; ?>>
                            <label for="cat-<?php echo str_replace(' ', '-', $cat); ?>"><?php echo $cat; ?></label>
                        </div>
                        <?php endforeach; ?>
                        <div class="filter-option">
                            <input type="radio" name="category" value="all" id="cat-all" <?php echo $category === 'all' ? 'checked' : ''; ?>>
                            <label for="cat-all">All Categories</label>
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="filter-card">
                        <div class="filter-title">
                            <i class="fas fa-award"></i>Brands
                        </div>
                        <?php foreach ($all_brands as $b): ?>
                        <div class="filter-option">
                            <input type="checkbox" class="brand-checkbox" value="<?php echo htmlspecialchars($b); ?>" id="brand-<?php echo str_replace(' ', '-', $b); ?>" <?php echo $brand === $b ? 'checked' : ''; ?>>
                            <label for="brand-<?php echo str_replace(' ', '-', $b); ?>"><?php echo $b; ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-card">
                        <div class="filter-title">
                            <i class="fas fa-tags"></i>Price Range
                        </div>
                        <div class="price-range-inputs">
                            <input type="number" id="priceMin" class="price-input" placeholder="Min" value="<?php echo $price_min > 0 ? $price_min : ''; ?>">
                            <input type="number" id="priceMax" class="price-input" placeholder="Max" value="<?php echo $price_max < 999999 ? $price_max : ''; ?>">
                        </div>
                    </div>

                    <!-- Apply Filters -->
                    <button class="btn-apply-filters" onclick="applyFilters()">
                        <i class="fas fa-check me-2"></i>Apply Filters
                    </button>
                    <button class="btn-clear-filters" onclick="clearFilters()">
                        <i class="fas fa-times me-2"></i>Clear All
                    </button>

                </aside>

                <!-- Mobile Filters (Collapsible) -->
                <aside class="collapse d-lg-none" id="mobileFilters">
                    <div class="filter-sidebar mb-4">
                        <!-- Same filters as desktop -->
                        <div class="filter-card">
                            <div class="filter-title"><i class="fas fa-search"></i>Search</div>
                            <input type="text" id="searchInputMobile" class="price-input w-100" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>

                        <div class="filter-card">
                            <div class="filter-title"><i class="fas fa-th-large"></i>Categories</div>
                            <?php foreach ($categories as $cat): ?>
                            <div class="filter-option">
                                <input type="radio" name="categoryMobile" value="<?php echo htmlspecialchars($cat); ?>" id="cat-m-<?php echo str_replace(' ', '-', $cat); ?>" <?php echo $category === $cat ? 'checked' : ''; ?>>
                                <label for="cat-m-<?php echo str_replace(' ', '-', $cat); ?>"><?php echo $cat; ?></label>
                            </div>
                            <?php endforeach; ?>
                            <div class="filter-option">
                                <input type="radio" name="categoryMobile" value="all" id="cat-m-all" <?php echo $category === 'all' ? 'checked' : ''; ?>>
                                <label for="cat-m-all">All Categories</label>
                            </div>
                        </div>

                        <div class="filter-card">
                            <div class="filter-title"><i class="fas fa-award"></i>Brands</div>
                            <?php foreach ($all_brands as $b): ?>
                            <div class="filter-option">
                                <input type="checkbox" class="brand-checkbox-mobile" value="<?php echo htmlspecialchars($b); ?>" id="brand-m-<?php echo str_replace(' ', '-', $b); ?>" <?php echo $brand === $b ? 'checked' : ''; ?>>
                                <label for="brand-m-<?php echo str_replace(' ', '-', $b); ?>"><?php echo $b; ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="filter-card">
                            <div class="filter-title"><i class="fas fa-tags"></i>Price Range</div>
                            <div class="price-range-inputs">
                                <input type="number" id="priceMinMobile" class="price-input" placeholder="Min" value="<?php echo $price_min > 0 ? $price_min : ''; ?>">
                                <input type="number" id="priceMaxMobile" class="price-input" placeholder="Max" value="<?php echo $price_max < 999999 ? $price_max : ''; ?>">
                            </div>
                        </div>

                        <button class="btn-apply-filters" onclick="applyFilters(true)">
                            <i class="fas fa-check me-2"></i>Apply Filters
                        </button>
                        <button class="btn-clear-filters" onclick="clearFilters()">
                            <i class="fas fa-times me-2"></i>Clear All
                        </button>
                    </div>
                </aside>

                <!-- Products Area -->
                <div class="products-area">
                    
                    <!-- Toolbar -->
                    <div class="toolbar">
                        <div class="results-count">
                            Showing <strong><?php echo count($products); ?></strong> results
                        </div>
                        
                        <div class="toolbar-controls">
                            <div class="view-toggle">
                                <button class="view-btn active" id="gridViewBtn" onclick="setView('grid')">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button class="view-btn" id="listViewBtn" onclick="setView('list')">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                            
                            <select class="sort-select" id="sortSelect" onchange="changeSort(this.value)">
                                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                                <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                <option value="name_az" <?php echo $sort === 'name_az' ? 'selected' : ''; ?>>Name: A-Z</option>
                                <option value="discount" <?php echo $sort === 'discount' ? 'selected' : ''; ?>>Highest Discount</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <?php if (count($products) > 0): ?>
                    <div class="products-grid" id="productsGrid">
                        <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <!-- Badges -->
                                <div class="badge-overlay">
                                    <?php if ($product['discount'] > 0): ?>
                                        <span class="discount-badge">-<?php echo $product['discount']; ?>%</span>
                                    <?php endif; ?>
                                    <?php if ($product['is_luxury']): ?>
                                        <span class="luxury-badge">LUXURY</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Stock Badge -->
                                <?php 
                                $stock_class = '';
                                switch($product['stock_status']) {
                                    case 'In Stock': $stock_class = 'in-stock'; break;
                                    case 'Low Stock': $stock_class = 'low-stock'; break;
                                    default: $stock_class = 'out-stock';
                                }
                                ?>
                                <span class="stock-badge <?php echo $stock_class; ?>">
                                    <?php echo $product['stock_status']; ?>
                                </span>

                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>

                            <div class="product-info">
                                <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                
                                <div class="product-price-row">
                                    <span class="product-price">LKR <?php echo number_format($product['price']); ?></span>
                                    <?php if ($product['original_price'] > $product['price']): ?>
                                        <span class="product-price-old">LKR <?php echo number_format($product['original_price']); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="product-actions">
                                    <button class="btn-add-cart" onclick="addProductToCart(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo addslashes($product['image']); ?>')" <?php echo $product['stock_status'] === 'Out of Stock' ? 'disabled' : ''; ?>>
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        <?php echo $product['stock_status'] === 'Out of Stock' ? 'OUT OF STOCK' : 'Add to Cart'; ?>
                                    </button>
                                    <a href="product-page.php?id=<?php echo $product['id']; ?>" class="btn-view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No Products Found</h3>
                        <p>Try adjusting your filters or search terms</p>
                        <button class="btn btn-gold mt-3" onclick="clearFilters()">Clear All Filters</button>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </section>

    <?php include 'components/footer.php'; ?>
    <?php include 'components/cart-offcanvas.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cart.js"></script>

    <script>
        // Add product to cart
        function addProductToCart(id, name, price, image) {
            const productData = {
                id: id,
                name: name,
                price: price,
                image: image,
                quantity: 1,
                options: {}
            };
            addToCart(productData);
        }

        // Apply filters
        function applyFilters(isMobile = false) {
            const params = new URLSearchParams();
            
            // Get category
            const categoryInput = document.querySelector(`input[name="${isMobile ? 'categoryMobile' : 'category'}"]:checked`);
            if (categoryInput && categoryInput.value !== 'all') {
                params.append('category', categoryInput.value);
            }
            
            // Get brand
            const brandCheckboxes = document.querySelectorAll(isMobile ? '.brand-checkbox-mobile:checked' : '.brand-checkbox:checked');
            if (brandCheckboxes.length > 0) {
                params.append('brand', brandCheckboxes[0].value);
            }
            
            // Get price range
            const priceMin = document.getElementById(isMobile ? 'priceMinMobile' : 'priceMin').value;
            const priceMax = document.getElementById(isMobile ? 'priceMaxMobile' : 'priceMax').value;
            if (priceMin) params.append('price_min', priceMin);
            if (priceMax) params.append('price_max', priceMax);
            
            // Get search
            const search = document.getElementById(isMobile ? 'searchInputMobile' : 'searchInput').value;
            if (search) params.append('search', search);
            
            // Get current sort
            const sort = document.getElementById('sortSelect').value;
            if (sort !== 'newest') params.append('sort', sort);
            
            // Redirect
            window.location.href = 'collection.php?' + params.toString();
        }

        // Clear filters
        function clearFilters() {
            window.location.href = 'collection.php';
        }

        // Change sort
        function changeSort(value) {
            const params = new URLSearchParams(window.location.search);
            if (value === 'newest') {
                params.delete('sort');
            } else {
                params.set('sort', value);
            }
            window.location.href = 'collection.php?' + params.toString();
        }

        // View toggle
        function setView(view) {
            const grid = document.getElementById('productsGrid');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');
            
            if (view === 'list') {
                grid.classList.add('list-view');
                gridBtn.classList.remove('active');
                listBtn.classList.add('active');
            } else {
                grid.classList.remove('list-view');
                listBtn.classList.remove('active');
                gridBtn.classList.add('active');
            }
        }

        // Search on Enter
        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        document.getElementById('searchInputMobile')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters(true);
            }
        });
    </script>
</body>
</html>