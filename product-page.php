<?php
session_start();
require_once "includes/connection.php";

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch Real Product Data from DB
$query = "SELECT p.*, b.brand_name, c.category_name 
          FROM products p 
          LEFT JOIN brands b ON p.brand_id = b.brand_id 
          LEFT JOIN sub_categories sc ON p.sub_category_id = sc.sub_category_id
          LEFT JOIN categories c ON sc.category_id = c.category_id
          WHERE p.product_id = '" . $product_id . "'";

$result = Database::search($query);
if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    // Redirect or show error if product not found
    header("Location: index.php");
    exit();
}

// Fetch Multiple Images
$images = [];
$img_query = Database::search("SELECT image_path FROM product_images WHERE product_id = '".$product_id."' ORDER BY is_primary DESC");
if ($img_query && $img_query->num_rows > 0) {
    while($img = $img_query->fetch_assoc()) {
        $images[] = $img['image_path'];
    }
}
if(empty($images)) {
    $images[] = $product['image_path']; // Fallback to main image
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --prm-blue: #0A111F;
            --sec-blue: #151f32;
            --chp-gold: #D4AF37;
            --chp-gold-hover: #b5952f;
            --btn-blue: #6F95E8; /* Periwinkle blue button */
            --btn-blue-hover: #5b7dc4;
            --text-light: #f8f9fa;
            --text-faded: #adb5bd;
            --dark-grey: #394150;
            --border-color: #2d3748;
            --input-bg: #1a2332;
        }

        body {
            font-family: 'Montserrat', sans-serif; 
            background-color: var(--prm-blue);
            color: var(--text-light);
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, h6, .font-oswald, .navbar-brand { 
            font-family: 'Oswald', sans-serif; text-transform: uppercase;
        }
        .text-gold { color: var(--chp-gold) !important; }
        .bg-gold { background-color: var(--chp-gold) !important; }
        .hover-gold:hover { color: var(--chp-gold) !important; transition: 0.3s; }

        .brand-logo-img {
            height: 40px;
            width: auto;
        }

        /* --- Top Bar --- */
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
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        .marquee-container:hover .marquee-content {
            animation-play-state: paused;
        }

        /* --- Navbar --- */
        .navbar {
            background-color: var(--prm-blue);
            padding: 1rem 0;
        }
        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            margin: 0 10px;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        .nav-link:hover {
            color: var(--chp-gold) !important;
        }
        
        /* Dropdown specific styles */
        .nav-link.dropdown-toggle::after {
            display: none;
        }
        
        .nav-link i.fa-chevron-down {
            font-size: 0.7rem;
            transition: transform 0.3s ease;
        }
        
        .nav-link[aria-expanded="true"] i.fa-chevron-down {
            transform: rotate(180deg);
        }
        
        .dropdown-menu {
            background-color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
            margin-top: 0.5rem;
            padding: 0.5rem 0;
            min-width: 200px;
        }
        
        .dropdown-item {
            padding: 0.6rem 1.5rem;
            color: #333;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--prm-blue);
            color: white;
            padding-left: 2rem;
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(0,0,0,0.1);
        }
        
        /* Mobile dropdown styles */
        @media (max-width: 991px) {
            .dropdown-menu {
                background-color: rgba(255,255,255,0.1);
                border-left: 3px solid var(--chp-gold);
                margin-left: 1rem;
                box-shadow: none;
            }
            
            .dropdown-item {
                color: rgba(255,255,255,0.8);
                font-size: 0.85rem;
            }
            
            .dropdown-item:hover {
                background-color: rgba(255,255,255,0.1);
                color: var(--chp-gold);
                padding-left: 1.5rem;
            }
            
            .nav-link {
                padding: 0.5rem 0;
            }
        }
        
        .nav-icons .btn {
            color: white;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .nav-icons .btn:hover {
            color: var(--chp-gold);
            transform: scale(1.1);
        }
        
        /* Cart badge */
        .cart-badge {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
        }
        
        /* Navbar toggler animation */
        .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
            transition: all 0.3s ease;
        }
        
        .navbar-toggler:hover {
            border-color: var(--chp-gold);
        }
        
        .navbar-toggler:hover i {
            color: var(--chp-gold) !important;
        }

        /* Global Cart Styles */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 0px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.4); border-radius: 10px; }
        .qty-pill { background: var(--input-bg); border: 1px solid var(--border-color); border-radius: 50px; display: inline-flex; align-items: center; }
        .qty-pill button { background: transparent; border: none; color: var(--text-light); padding: 2px 10px; transition: 0.2s; }
        .qty-pill button:hover { color: var(--chp-gold); }
        .cart-item-title { font-size: 0.85rem; font-weight: 500; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* Breadcrumb */
        .breadcrumb a { color: var(--text-faded); text-decoration: none; font-size: 0.85rem; }
        .breadcrumb-item.active { color: var(--chp-gold); font-size: 0.85rem; }
        .breadcrumb-item + .breadcrumb-item::before { color: #666; }

        /* --- UI/UX Upgrades for Product Page --- */

        /* --- Premium Cart Scroll & Text Fixes --- */
        #cartOffcanvas {
            width: 400px;
            max-width: 100vw;
            background-color: var(--prm-blue);        /* ADDED */
            border-left: 1px solid var(--border-color); /* ADDED */
        }

        #sideCartItems {
            overflow-x: hidden !important; 
            overflow-y: auto; 
        }

        .offcanvas-header {                                          /* ADDED */
            border-color: rgba(255,255,255,0.05) !important;
            padding: 1.5rem;
        }

        .offcanvas-title {                                           /* ADDED */
            font-family: 'Oswald', sans-serif;
            letter-spacing: 1px;
            font-size: 1.2rem;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px; 
            height: 0px !important;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(212, 175, 55, 0.4);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {          
            background: rgba(212, 175, 55, 0.8);
        }

        /* --- Free Shipping Bar --- */
        #freeShippingContainer {
            background: linear-gradient(180deg, rgba(212,175,55,0.05) 0%, transparent 100%);
            padding: 1rem;
            text-align: center;
        }
        #freeShippingText {
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        #freeShippingBar {
            border-radius: 10px;
            transition: width 0.4s ease;
        }

        .cart-item-title {
            font-size: 0.85rem; 
            font-weight: 500; 
            line-height: 1.3;
            color: white;
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            word-break: break-word;
        }

        .qty-pill {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            overflow: hidden;
        }
        .qty-pill button {
            background: transparent; border: none; color: var(--text-light); padding: 2px 10px; transition: 0.2s;
        }
        .qty-pill button:hover { color: var(--chp-gold); }
        .qty-pill span { font-size: 0.85rem; font-weight: 600; min-width: 20px; text-align: center; }

        /* --- Cart Addon Section --- */                       
        .cart-addon-section {
            padding: 1rem;
            border-color: rgba(255,255,255,0.05) !important;
            background-color: rgba(0,0,0,0.2);
        }
        .cart-addon-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .cart-addon-item {
            background: var(--input-bg);
            border: 1px solid transparent;
            transition: border-color 0.3s;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .cart-addon-item:hover { border-color: var(--chp-gold); }
        .cart-addon-icon-wrap {
            background-color: var(--prm-blue);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
        }
        .cart-addon-name { font-size: 0.85rem; font-weight: 500; margin: 0; }
        .cart-addon-price { font-size: 0.75rem; }

        /* --- Cart Totals Section --- */                     
        .cart-totals-section {
            padding: 1.5rem;
            border-color: rgba(255,255,255,0.05) !important;
            background-color: var(--sec-blue);
        }
        .cart-subtotal-label { font-size: 0.9rem; }
        #sideCartTotal {
            font-family: 'Oswald', sans-serif;
            font-size: 1.5rem;
            line-height: 1;
            font-weight: 700;
            display: block;
        }
        .cart-tax-note { font-size: 0.7rem; }
        .cart-checkout-btn { letter-spacing: 1px; border-radius: 6px; padding: 0.75rem; }
        .cart-secure-note { font-size: 0.7rem; margin: 0; }

        .btn-gold {
            background-color: var(--chp-gold);
            color: #000;
            border: none;
            font-weight: 600;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background-color: var(--chp-gold-hover);
            color: #000;
        }

        /* --- btn-outline-gold --- */
        .btn-outline-gold {
            background: transparent;
            color: var(--chp-gold);
            border: 2px solid var(--chp-gold);
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-outline-gold:hover {
            background-color: var(--chp-gold);
            color: #000;
        }

        /* --- 1. Fixed Image Gallery Responsiveness --- */
        .product-gallery-img { 
            width: 100%; 
            aspect-ratio: 1 / 1; /* Forces a perfect square */
            max-height: 500px;
            object-fit: contain; 
            background: white; 
            border-radius: 12px; 
            padding: 2rem; 
            border: 1px solid rgba(255,255,255,0.05); 
        }
        .thumbnail-container {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
            padding-bottom: 5px;
        }
        .thumbnail-container::-webkit-scrollbar { display: none; }
        
        .thumbnail {
            flex: 0 0 85px;
            width: 85px;
            height: 85px;
            object-fit: contain;
            border: 2px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            padding: 5px;
            background: white;
            opacity: 0.5;
            transition: all 0.3s ease;
        }
        .thumbnail:hover, .thumbnail.active { border-color: var(--chp-gold); opacity: 1; }

        /* 2. Text Hierarchy */
        .product-brand-label { color: var(--chp-gold); font-weight: 600; letter-spacing: 2px; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.5rem; display: block; }
        .product-main-title { font-size: 2.8rem; line-height: 1.1; font-weight: 700; margin-bottom: 1rem; }
        .product-price { font-size: 2.2rem; font-weight: 700; color: var(--text-light); font-family: 'Oswald', sans-serif; letter-spacing: 1px; }
        .original-price { font-size: 1.2rem; text-decoration: line-through; color: var(--text-faded); margin-left: 10px; font-weight: 400; }

        /* 3. Koko Box */
        .koko-box { background: var(--sec-blue); border-left: 3px solid #7191D9; border-radius: 6px; padding: 15px 20px; margin: 25px 0; }
        
        /* 4. Actions */
        .btn-add-cart { background-color: var(--chp-gold); color: #000; font-family: 'Oswald', sans-serif; font-size: 1.2rem; letter-spacing: 1px; padding: 15px; border-radius: 6px; border: none; transition: 0.3s; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2); }
        .btn-add-cart:hover { background-color: var(--chp-gold-hover); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4); }

        /* 5. Trust Badges */
        .trust-item { display: flex; align-items: center; gap: 10px; color: var(--text-faded); font-size: 0.85rem; }
        .trust-item i { color: var(--chp-gold); font-size: 1.2rem; }

        /* Related Products */
        .product-card { 
            background: #4A5568; /* Slate gray background from design */
            border: none; 
            border-radius: 12px; 
            overflow: hidden; 
            transition: 0.3s; 
            height: 100%; 
            display: flex; 
            flex-direction: column; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.3); }
        
        .card-img-wrapper { 
            aspect-ratio: 4 / 3; 
            background: white; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            position: relative; 
            overflow: hidden;
        }
        .card-img-wrapper img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; /* Makes image fill the area perfectly */
        }
        
        .card-body { 
            padding: 1.5rem; 
            display: flex; 
            flex-direction: column; 
            flex-grow: 1; 
        }
        
        .card-title { 
            font-size: 18px; 
            font-weight: 700; 
            margin-bottom: 1rem; 
            line-height: 1.3;
            color: white;
            display: -webkit-box; 
            -webkit-line-clamp: 3; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
        }
        
        .price-row { 
            display: flex; 
            align-items: baseline; 
            gap: 12px;
            margin-bottom: 0.5rem; 
        }
        .current-price { font-size: 18px; font-weight: 700; color: var(--chp-gold); }
        .old-price { font-size: 16px; font-weight: 400; color: var(--text-faded); text-decoration: line-through;}
        
        .koko-text { font-size: 12px; color: #e2e8f0; margin-bottom: 1.5rem; }
        .koko-logo { font-weight: 800; color: #7191D9; letter-spacing: 1px; font-style: italic; }

        .card-actions { display: flex; gap: 12px; margin-top: auto; flex-direction: row-reverse;}
        
        .btn-add-cart-outline { 
            flex-grow: 1; 
            background: transparent; 
            border: 2px solid var(--chp-gold); 
            color: white; 
            border-radius: 25px; /* Pill shape from design */
            font-weight: 600; 
            font-size: 14px; 
            transition: 0.3s; 
            padding: 10px;
        }
        .btn-add-cart-outline:hover { background: var(--chp-gold); color: #000; }
        
        .btn-view-solid { 
            background: var(--chp-gold); 
            color: #000; 
            border: none; 
            border-radius: 50%; /* Circle from design */
            width: 45px; 
            height: 45px;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            transition: 0.3s;
            font-size: 16px;
            flex-shrink: 0;
        }
        .btn-view-solid:hover { background: var(--chp-gold-hover); transform: scale(1.05); }

        /* Recently view btn style */
        .btn-recent-add-cart-outline { 
            flex-grow: 1; 
            background: transparent; 
            border: 2px solid var(--btn-blue); 
            color: white; 
            border-radius: 25px; /* Pill shape from design */
            font-weight: 600; 
            font-size: 10px; 
            transition: 0.3s; 
            padding: 8px;
        }
        .btn-recent-add-cart-outline:hover { background: var(--btn-blue-hover); color: #fff; }
        
        .btn-recent-view-solid { 
            background: var(--btn-blue); 
            color: #000; 
            border: none; 
            border-radius: 50%; /* Circle from design */
            width: 42px; 
            height: 42px;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            transition: 0.3s;
            font-size: 16px;
            flex-shrink: 0;
        }
        .btn-recent-view-solid:hover { background: var(--btn-blue-hover); color: #fff; transform: scale(1.05); }

        /* Responsive scaling so 26px font doesn't break mobile */
        @media (max-width: 768px) {
            .btn-recent-add-cart-outline { font-size: 8px; padding: 8px; }
        }
        /* --- Flat Design Product Cards (For Recently Viewed) --- */
        .flat-product-card {
            background: #505B6D; /* Slightly lighter slate for contrast */
            border: none;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            height: 100%;
            transition: 0.3s;
            cursor: pointer;
        }
        
        .flat-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .flat-img-wrapper {
            flex: 0 0 40%; /* 40% width for the image. Change this number to adjust! */
            max-width: 40%; 
            height: 100%; 
            background: #e2e8f0; 
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .flat-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain; 
            padding: 10px;
        }

        .flat-card-body {
            flex: 1; /* This forces the text to take up exactly the remaining space (60%) */
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .flat-card-title {
            font-size: 16px; /* Scaled down slightly to fit the flat design better */
            font-weight: 700;
            color: white;
            line-height: 1.3;
            margin-bottom: 0.75rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .flat-price-row {
            display: block;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 0.5rem;
        }

        .flat-current-price {
            font-size: 16px;
            font-weight: 700;
            color: var(--chp-gold);
        }

        .flat-old-price {
            font-size: 10px;
            font-weight: 500;
            color: #cbd5e1;
            text-decoration: line-through;
        }

        .flat-koko-text {
            font-size: 10px;
            color: #e2e8f0;
            margin-bottom: 0.875rem;
        }

        .flat-card-actions {
            display: flex;
            gap: 12px;
            margin-top: auto;
            flex-direction: row-reverse;
        }      

        /* --- Footer --- */
        footer {
            background-color: #000;
            color: #aaa;
            padding: 4rem 0 2rem;
            font-size: 0.9rem;
        }
        .footer-brand-logo-img {
            height: 68px;
            width: auto;
        }
        footer h5 {
            color: white;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }
        footer ul {
            list-style: none;
            padding: 0;
        }
        footer ul li {
            margin-bottom: 10px;
        }
        footer a {
            color: #aaa;
            text-decoration: none;
            transition: color 0.3s;
        }
        footer a:hover {
            color: var(--chp-gold);
        }
        .footer-bottom {
            border-top: 1px solid #333;
            margin-top: 3rem;
            padding-top: 1.5rem;
        }
        .social-icons a {
            font-size: 1.2rem;
            margin-right: 15px;
        }
        /* --- 3. Sticky Mobile Cart Bar --- */
        .mobile-sticky-cart {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(10, 17, 31, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid var(--chp-gold);
            padding: 10px 15px;
            z-index: 1030;
            transform: translateY(100%); /* Hidden by default */
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.5);
        }
        .mobile-sticky-cart.show {
            transform: translateY(0); /* Slides up */
        }
        @media (max-width: 576px) {
            .card-body { padding: 0.75rem; }
            .card-title { font-size: 0.75rem; }
        }
        /* --- Responsive Tweaks --- */
        @media (max-width: 768px) {
            body { padding-bottom: 70px; } 
            .brand-logo-img { height: 32px; }
            .footer-brand-logo-img { height: 54px; }
            .product-main-title { font-size: 1.8rem; line-height: 1.2; }
            .product-price { font-size: 1.8rem; }
            .product-gallery-img { padding: 1rem; }
        }
    </style>
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="container mt-4 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo htmlspecialchars($product['category_name'] ?? 'Shop'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['product_name']); ?></li>
            </ol>
        </nav>
    </div>

    <section class="py-4 mb-5">
        <div class="container">
            <div class="row gx-lg-5">
                
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="<?php echo htmlspecialchars($images[0]); ?>" id="mainImage" class="product-gallery-img" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    
                    <div class="thumbnail-container mt-3">
                        <?php foreach($images as $index => $img): ?>
                            <img src="<?php echo htmlspecialchars($img); ?>" class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage(this)">
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-lg-6">
                    <span class="product-brand-label"><?php echo htmlspecialchars($product['brand_name']); ?></span>
                    <h1 class="product-main-title font-oswald text-white"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                    
                    <div class="d-flex align-items-baseline mb-3 mt-4">
                        <span class="product-price">LKR <?php echo number_format($product['current_price'], 2); ?></span>
                        <?php if($product['discount_percentage'] > 0): ?>
                            <span class="original-price">LKR <?php echo number_format($product['original_price'], 2); ?></span>
                            <span class="badge bg-danger ms-3" style="font-size: 0.9rem; padding: 6px 10px;">Save <?php echo $product['discount_percentage']; ?>%</span>
                        <?php endif; ?>
                    </div>

                    <div class="koko-box d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-white d-block mb-1" style="font-size: 0.9rem;">Pay in 3 installments of <strong>LKR <?php echo number_format($product['koko_installment'], 2); ?></strong></span>
                            <span class="text-faded" style="font-size: 0.75rem;">0% interest. No hidden fees.</span>
                        </div>
                        <span class="fw-bold" style="color: #7191D9; font-size: 1.2rem;">KOKO</span>
                    </div>

                    <p class="text-faded mb-4" style="line-height: 1.6; font-size: 0.95rem;">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>

                    <hr style="border-color: rgba(255,255,255,0.1); margin: 2rem 0;">

                    <div class="row g-3 align-items-center mb-4">
                        <div class="col-auto">
                            <div class="qty-pill" style="height: 54px; padding: 0 10px;">
                                <button onclick="changeQty(-1)"><i class="fas fa-minus"></i></button>
                                <input type="number" id="qtyInput" value="1" min="1" max="<?php echo $product['stock_count']; ?>" style="background: transparent; border: none; color: white; width: 40px; text-align: center; font-weight: bold; pointer-events: none;">
                                <button onclick="changeQty(1)"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col">
                            <button class="btn-add-cart w-100" onclick="handleAddToCart()">
                                <i class="fas fa-shopping-bag me-2"></i> Add to Cart
                            </button>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-6"><div class="trust-item"><i class="fas fa-shield-alt"></i> 2 Year Warranty</div></div>
                        <div class="col-6"><div class="trust-item"><i class="fas fa-truck"></i> Island-wide Delivery</div></div>
                        <div class="col-6"><div class="trust-item"><i class="fas fa-undo"></i> 7 Day Returns</div></div>
                        <div class="col-6"><div class="trust-item"><i class="fas fa-certificate"></i> 100% Authentic</div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mobile-sticky-cart d-md-none" id="stickyCartBar">
        <div>
            <p class="m-0 text-white font-oswald" style="font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">
                <?php echo htmlspecialchars($product['product_name']); ?>
            </p>
            <p class="m-0 text-gold fw-bold">LKR <?php echo number_format($product['current_price'], 2); ?></p>
        </div>
        <button class="btn btn-gold btn-sm px-4 py-2 text-uppercase fw-bold" onclick="handleAddToCart()">
            Add to Cart
        </button>
    </div>
    <!-- May like section -->
    <section class="py-5" style="background-color: #0f1724;">
        <div class="container">
            <h3 class="text-center font-oswald text-white mb-5" style="font-size: 2rem;">You May Also Like</h3>
            <div class="row g-4" id="relatedProductsContainer">
                <div class="text-center py-4"><div class="spinner-border text-gold" role="status"></div></div>
            </div>
        </div>
    </section>
    <!-- Recently viewed section -->
    <section class="py-5" id="recentlyViewedSection" style="display: none; background-color: var(--prm-blue);">
        <div class="container">
            <h3 class="text-center font-oswald text-white mb-5" style="font-size: 2rem;">Recently Viewed</h3>
            <div class="row g-4" id="recentlyViewedContainer">
                </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Cart -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel" style="background-color: var(--prm-blue); border-left: 1px solid var(--border-color); width: 400px;">
        
        <div class="offcanvas-header border-bottom" style="border-color: rgba(255,255,255,0.05) !important; padding: 1.5rem;">
            <h5 class="offcanvas-title text-white mb-0" id="cartOffcanvasLabel" style="font-family: 'Oswald', sans-serif; letter-spacing: 1px; font-size: 1.2rem;">
                Your Cart <span id="cartHeaderCount" class="text-gold ms-1">(0)</span>
            </h5>
            <button type="button" class="btn-close btn-close-white opacity-50 hover-opacity-100" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        
        <div class="offcanvas-body d-flex flex-column p-0 custom-scrollbar">
            
            <div id="freeShippingContainer" class="p-3 text-center" style="background: linear-gradient(180deg, rgba(212,175,55,0.05) 0%, transparent 100%);">
                <p id="freeShippingText" class="text-white mb-2" style="font-size: 0.8rem; font-weight: 500;">
                    You're <span class="text-gold fw-bold">LKR 5,000</span> away from Free Shipping!
                </p>
                <div class="progress" style="height: 6px; background-color: var(--input-bg); border-radius: 10px;">
                    <div id="freeShippingBar" class="progress-bar bg-gold" role="progressbar" style="width: 60%; border-radius: 10px; transition: width 0.4s ease;"></div>
                </div>
            </div>

            <div id="sideCartItems" class="flex-grow-1 overflow-auto p-3">
                </div>

            <div class="p-3 mt-auto border-top" style="border-color: rgba(255,255,255,0.05) !important; background-color: rgba(0,0,0,0.2);">
                <h6 class="text-white mb-3" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Complete Your Purchase</h6>
                
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded" style="background: var(--input-bg); border: 1px solid transparent; transition: 0.3s;" onmouseover="this.style.borderColor='var(--chp-gold)'" onmouseout="this.style.borderColor='transparent'">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-prm-blue d-flex align-items-center justify-content-center rounded" style="width: 40px; height: 40px;">
                            <i class="fas fa-gift text-gold"></i>
                        </div>
                        <div>
                            <p class="m-0 text-white fw-medium" style="font-size: 0.85rem;">Luxury Gift Box</p>
                            <span class="text-white" style="font-size: 0.75rem;">LKR 1,500</span>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-gold rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;" onclick="addAddonToCart('Luxury Gift Box', 1500)">Add</button>
                </div>
            </div>

            <div class="p-4 border-top" style="border-color: rgba(255,255,255,0.05) !important; background-color: var(--sec-blue);">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <span class="text-white" style="font-size: 0.9rem;">Subtotal</span>
                    <div class="text-end">
                        <span class="text-white fw-bold d-block" id="sideCartTotal" style="font-family: 'Oswald', sans-serif; font-size: 1.5rem; line-height: 1;">LKR 0.00</span>
                        <span class="text-white" style="font-size: 0.7rem;">Taxes and shipping calculated at checkout</span>
                    </div>
                </div>
                
                <button onclick="window.location.href='checkout.php'" class="btn btn-gold w-100 py-3 text-uppercase fw-bold shadow-sm" style="letter-spacing: 1px; border-radius: 6px;">
                    Checkout
                </button>
                
                <div class="text-center mt-3">
                    <p class="text-white m-0" style="font-size: 0.7rem;">
                        <i class="fas fa-lock me-1 text-gold"></i> Secure Encrypted Checkout
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cart.js"></script>

    <script>
        // 1. Current Product Data (Passed from PHP to JS for the Cart)
        const currentProductData = {
            id: <?php echo $product['product_id']; ?>,
            name: "<?php echo addslashes($product['product_name']); ?>",
            price: <?php echo $product['current_price']; ?>,
            image: "<?php echo addslashes($images[0]); ?>",
            quantity: 1,
            options: { Brand: "<?php echo addslashes($product['brand_name']); ?>" }
        };

        // 2. Image Gallery Logic
        function changeImage(element) {
            document.getElementById('mainImage').src = element.src;
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            element.classList.add('active');
        }

        // 3. Quantity Logic
        function changeQty(delta) {
            const input = document.getElementById('qtyInput');
            let value = parseInt(input.value) + delta;
            const maxStock = <?php echo $product['stock_count']; ?>;
            if (value < 1) value = 1;
            if (value > maxStock) {
                value = maxStock;
                alert("Maximum stock reached.");
            }
            input.value = value;
            currentProductData.quantity = value;
        }

        // 4. Add to Cart Trigger
        function handleAddToCart() {
            currentProductData.quantity = parseInt(document.getElementById('qtyInput').value);
            addToCart(currentProductData); // Calls cart.js which opens the offcanvas
        }

        // 5. Fetch Dynamic Related Products & Add to Cart Logic
        function quickAddToCart(id, name, price, img) {
            const pData = { 
                id: id, 
                name: name, 
                price: parseFloat(price), 
                image: img, 
                quantity: 1,
                options: { Type: 'Standard' } 
            };
            addToCart(pData); // Calls cart.js which opens offcanvas
        }

        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch('admin/actions/products-data.php');
                const allProducts = await response.json();
                
                const categoryId = <?php echo $product['category_id'] ?? 1; ?>;
                const related = allProducts.filter(p => p.id !== currentProductData.id).slice(0, 4);

                const container = document.getElementById('relatedProductsContainer');
                container.innerHTML = '';

                related.forEach(p => {
                    const currentPrice = new Intl.NumberFormat('en-LK').format(p.pricing.current_price);
                    const oldPrice = p.pricing.discount_percent > 0 ? `${new Intl.NumberFormat('en-LK').format(p.pricing.original_price)}` : '';
                    const img = p.primary_thumbnail ? p.primary_thumbnail : 'assets/images/products/default.png';
                    const safeName = p.name.replace(/'/g, "\\'").replace(/"/g, '&quot;');

                    // Calculate KOKO installment
                    const priceNum = parseFloat(p.current_price || (p.pricing ? p.pricing.current_price : 0));
                    const kokoInstallment = new Intl.NumberFormat('en-LK').format(priceNum / 3);

                    container.innerHTML += `
                        <div class="col-6 col-md-3">
                            <div class="product-card" onclick="window.location.href='product-page.php?id=${p.id}'">
                                <div class="card-img-wrapper">
                                    <img src="${img}" alt="${safeName}">
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">${p.name}</h6>
                                    
                                    <div class="mt-auto">
                                        <div class="price-row">
                                            <div class="current-price font-oswald">LKR ${currentPrice}</div>
                                            <div class="old-price">${oldPrice}</div>
                                        </div>
                                        
                                        <div class="koko-text">
                                            or pay in 3 x Rs ${kokoInstallment} with <span class="koko-logo">KOKO</span>
                                        </div>

                                        <div class="card-actions">
                                            <button class="btn-add-cart-outline" onclick="event.stopPropagation(); quickAddToCart(${p.id}, '${safeName}', ${priceNum}, '${img}')">
                                                Add to Cart
                                            </button>
                                            <button class="btn-view-solid" onclick="event.stopPropagation(); window.location.href='product-page.php?id=${p.id}'">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

            } catch (error) {
                console.error("Error loading related products:", error);
                document.getElementById('relatedProductsContainer').innerHTML = '<p class="text-center text-muted">No related products found.</p>';
            }
        });

        // 7. Recently Viewed Logic (LocalStorage)
        document.addEventListener('DOMContentLoaded', () => {
            // A. Save Current Product to Recently Viewed
            saveToRecentlyViewed();

            // B. Render the Recently Viewed Section
            renderRecentlyViewed();
        });

        function saveToRecentlyViewed() {
            // We use the currentProductData object you already defined at the top of your script
            if (!currentProductData || !currentProductData.id) return;

            // Get existing history from LocalStorage
            let history = JSON.parse(localStorage.getItem('pw_recently_viewed')) || [];

            // Remove this product if it's already in the list (so we can move it to the front)
            history = history.filter(p => p.id !== currentProductData.id);

            // Add current product to the beginning of the array
            history.unshift({
                id: currentProductData.id,
                name: currentProductData.name,
                price: currentProductData.price,
                image: currentProductData.image
            });

            // Keep only the last 4 viewed items to prevent clutter
            if (history.length > 4) {
                history = history.slice(0, 4);
            }

            // Save back to LocalStorage
            localStorage.setItem('pw_recently_viewed', JSON.stringify(history));
        }

        function renderRecentlyViewed() {
            // Get history, but exclude the CURRENT page's product from showing up in its own "Recently Viewed"
            let history = JSON.parse(localStorage.getItem('pw_recently_viewed')) || [];
            let displayHistory = history.filter(p => p.id !== currentProductData.id);

            const section = document.getElementById('recentlyViewedSection');
            const container = document.getElementById('recentlyViewedContainer');

            // If there's nothing to show, keep the section hidden
            if (displayHistory.length === 0) {
                return;
            }

            // Show the section
            section.style.display = 'block';
            container.innerHTML = '';

            displayHistory.forEach(p => {
                const priceNum = parseFloat(p.price);
                const currentPrice = new Intl.NumberFormat('en-LK').format(priceNum);
                const kokoInstallment = new Intl.NumberFormat('en-LK').format(priceNum / 3);
                
                // Assuming standard 20% markup for "Old Price" since we don't save the full pricing object in history
                const oldPriceNum = priceNum * 1.2;
                const oldPrice = `<span class="old-price">LKR ${new Intl.NumberFormat('en-LK').format(oldPriceNum)}</span>`;
                
                const img = p.image ? p.image : 'assets/images/products/default.png';
                const safeName = p.name.replace(/'/g, "\\'").replace(/"/g, '&quot;');

               // Using the new Flat Design layout
                container.innerHTML += `
                    <div class="col-12 col-md-3">
                        <div class="flat-product-card" onclick="window.location.href='product-page.php?id=${p.id}'">
                            
                            <div class="flat-img-wrapper">
                                <img src="${img}" alt="${safeName}">
                            </div>
                            
                            <div class="flat-card-body">
                                <h6 class="flat-card-title">${p.name}</h6>
                                
                                <div class="mt-auto">
                                    <div class="flat-price-row">
                                        <div class="flat-current-price font-oswald">LKR ${currentPrice}</div>
                                        ${oldPrice.replace('old-price', 'flat-old-price')}
                                    </div>
                                    
                                    <div class="flat-koko-text">
                                        or pay in 3 x Rs ${kokoInstallment} with <span class="koko-logo">KOKO</span>
                                    </div>

                                    <div class="flat-card-actions">
                                        <button class="btn-recent-add-cart-outline w-100" onclick="event.stopPropagation(); quickAddToCart(${p.id}, '${safeName}', ${priceNum}, '${img}')">
                                            Add to Cart
                                        </button>
                                        <button class="btn-recent-view-solid" onclick="event.stopPropagation(); window.location.href='product-page.php?id=${p.id}'">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                `;
            });
        }

        // 6. Sticky Mobile Bar Observer
        // This makes the sticky bar appear ONLY when the main Add to Cart button scrolls out of view
        const mainAddToCartBtn = document.querySelector('.btn-add-cart');
        const stickyBar = document.getElementById('stickyCartBar');
        
        if (mainAddToCartBtn && stickyBar) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        stickyBar.classList.add('show'); // Button is out of view, show sticky bar
                    } else {
                        stickyBar.classList.remove('show'); // Button is in view, hide sticky bar
                    }
                });
            }, { threshold: 0 });
            
            observer.observe(mainAddToCartBtn);
        }
    </script>
    
</body>
</html>