<?php
session_start();

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// In production, fetch this from database
// For now, using sample data
$product = [
    'id' => $product_id,
    'name' => 'Titan Quartz Analog Blue Dial',
    'brand' => 'Titan',
    'price' => 12000,
    'original_price' => 15000,
    'discount' => 20,
    'koko_installment' => 4000,
    'description' => 'Premium analog watch with blue dial and stainless steel strap',
    'images' => [
        'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=800&q=80'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --prm-blue: #0A111F;
            --chp-gold: #D4AF37;
            --chp-gold-hover: #b5952f;
            --text-light: #f8f9fa;
            --text-muted: #adb5bd;
            --dark-grey: #394150;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--prm-blue);
            color: var(--text-light);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        .text-gold { color: var(--chp-gold); }
        .text-muted { color: var(--text-muted) !important; }
        
        /* Top Bar & Navbar */
        .top-bar {
            background-color: #C8A030;
            color: #000;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 8px 0;
        }

        .navbar {
            background-color: var(--prm-blue);
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
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
        }

        .nav-link:hover {
            color: var(--chp-gold) !important;
        }

        .cart-icon-wrapper {
            position: relative;
            display: inline-block;
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
            display: none;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Breadcrumb */
        .breadcrumb-section {
            background-color: rgba(255,255,255,0.05);
            padding: 15px 0;
        }

        .breadcrumb a {
            color: var(--text-muted);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--chp-gold);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #666;
        }

        /* Product Gallery */
        .product-gallery-img {
            width: 100%;
            height: 450px;
            object-fit: contain;
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 15px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .thumbnail-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
        }

        .thumbnail {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border: 2px solid #333;
            border-radius: 8px;
            cursor: pointer;
            padding: 8px;
            background: white;
            opacity: 0.6;
            transition: all 0.3s;
        }

        .thumbnail:hover, .thumbnail.active {
            border-color: var(--chp-gold);
            opacity: 1;
            transform: scale(1.05);
        }

        /* Product Info */
        .product-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--chp-gold);
        }

        .original-price {
            font-size: 1.2rem;
            text-decoration: line-through;
            color: var(--text-muted);
        }

        .discount-badge {
            background: linear-gradient(135deg, #990000, #cc0000);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }

        .koko-box {
            background: var(--dark-grey);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }

        .koko-amount {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--chp-gold);
        }

        /* Quantity Selector */
        .qty-selector {
            display: inline-flex;
            border: 1px solid #555;
            border-radius: 8px;
            overflow: hidden;
            background: #1a2332;
        }

        .qty-btn {
            background: transparent;
            border: none;
            color: white;
            padding: 10px 18px;
            cursor: pointer;
            transition: 0.2s;
            font-size: 1.2rem;
        }

        .qty-btn:hover {
            background: var(--chp-gold);
            color: #000;
        }

        .qty-input {
            background: transparent;
            border: none;
            color: white;
            text-align: center;
            width: 60px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Buttons */
        .btn-gold {
            background-color: var(--chp-gold);
            color: #000;
            border: none;
            font-weight: 700;
            border-radius: 8px;
            padding: 14px 32px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1rem;
        }

        .btn-gold:hover {
            background-color: var(--chp-gold-hover);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }

        .btn-outline-gold {
            background: transparent;
            color: var(--chp-gold);
            border: 2px solid var(--chp-gold);
            font-weight: 600;
            border-radius: 8px;
            padding: 12px 30px;
            transition: all 0.3s;
        }

        .btn-outline-gold:hover {
            background-color: var(--chp-gold);
            color: #000;
        }

        /* Features Grid */
        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(212, 175, 55, 0.1);
            border: 2px solid var(--chp-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--chp-gold);
            margin: 0 auto 15px;
        }

        .feature-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 30px;
            transition: 0.3s;
            text-align: center;
        }

        .feature-card:hover {
            background: rgba(212, 175, 55, 0.05);
            border-color: var(--chp-gold);
            transform: translateY(-5px);
        }

        /* Related Products */
        .related-product-card {
            background: #151f32;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
            cursor: pointer;
        }

        .related-product-card:hover {
            border-color: var(--chp-gold);
            transform: translateY(-5px);
        }

        .related-img-wrapper {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: white;
        }

        .related-img-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .related-body {
            padding: 15px;
        }

        /* Footer */
        footer {
            background-color: #0d1626;
            color: var(--text-muted);
            padding: 3rem 0 1.5rem;
            margin-top: 5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        footer h5 {
            color: white;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        footer ul {
            list-style: none;
            padding: 0;
        }

        footer ul li {
            margin-bottom: 0.5rem;
        }

        footer a {
            color: var(--text-muted);
            text-decoration: none;
            transition: 0.3s;
        }

        footer a:hover {
            color: var(--chp-gold);
        }

        @media (max-width: 768px) {
            .product-price {
                font-size: 2rem;
            }

            .koko-amount {
                font-size: 1.4rem;
            }

            .product-gallery-img {
                height: 300px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar text-center">
        <div class="container">
            <i class="fas fa-shipping-fast me-2"></i> FREE SHIPPING on orders over LKR 10,000
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/brand-logos/logo5.png" alt="Logo" class="brand-logo-img">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Men's</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Women's</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Luxury</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Wall Decor</a></li>
                </ul>
                
                <div class="d-flex gap-3 align-items-center">
                    <a href="#" class="text-white"><i class="fas fa-search"></i></a>
                    <a href="#" class="text-white"><i class="fas fa-user"></i></a>
                    <a href="#" class="text-white cart-icon-wrapper" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge" id="cartBadge">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Men's Watches</a></li>
                    <li class="breadcrumb-item active"><?php echo $product['name']; ?></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Product Detail Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Left: Gallery -->
                <div class="col-lg-6 mb-4">
                    <img src="<?php echo $product['images'][0]; ?>" alt="<?php echo $product['name']; ?>" class="product-gallery-img" id="mainImage">
                    
                    <div class="thumbnail-container">
                        <?php foreach ($product['images'] as $index => $img): ?>
                            <img src="<?php echo $img; ?>" alt="Thumbnail" class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage(this)">
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="col-lg-6">
                    <h1 class="h2 text-white mb-2"><?php echo $product['name']; ?></h1>
                    <p class="text-muted mb-4">By <?php echo $product['brand']; ?></p>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="product-price">LKR <?php echo number_format($product['price']); ?></span>
                        <span class="original-price">LKR <?php echo number_format($product['original_price']); ?></span>
                        <span class="discount-badge">-<?php echo $product['discount']; ?>% OFF</span>
                    </div>

                    <!-- KOKO Installment -->
                    <div class="koko-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted mb-2">Pay with <span style="color:#6F95E8; font-weight:bold;">KOKO</span></div>
                                <div class="koko-amount">LKR <?php echo number_format($product['koko_installment']); ?> <small class="text-muted" style="font-size: 1rem;">x 3</small></div>
                            </div>
                            <i class="fas fa-credit-card fa-2x text-gold"></i>
                        </div>
                        <small class="text-muted d-block mt-2">No interest. No hidden fees.</small>
                    </div>

                    <p class="text-muted mb-4"><?php echo $product['description']; ?></p>

                    <!-- Quantity Selector -->
                    <div class="mb-4">
                        <label class="text-muted d-block mb-2">Quantity</label>
                        <div class="qty-selector">
                            <button class="qty-btn" onclick="changeQty(-1)">−</button>
                            <input type="number" class="qty-input" id="qtyInput" value="1" min="1" readonly>
                            <button class="qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    <!-- Add to Cart Buttons -->
                    <div class="d-grid gap-3 mb-4">
                        <button class="btn btn-gold" onclick="handleAddToCart()">
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                        <button class="btn btn-outline-gold">
                            <i class="fas fa-heart me-2"></i> Add to Wishlist
                        </button>
                    </div>

                    <!-- Product Features -->
                    <div class="row g-3 mt-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-shield-alt text-gold"></i>
                                <small class="text-muted">2 Year Warranty</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-truck text-gold"></i>
                                <small class="text-muted">Free Shipping</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-undo text-gold"></i>
                                <small class="text-muted">7 Day Returns</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-certificate text-gold"></i>
                                <small class="text-muted">100% Authentic</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" style="background: rgba(255,255,255,0.02);">
        <div class="container">
            <h3 class="text-center text-white mb-5">Why Choose This Watch?</h3>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h5 class="text-white mb-3">Premium Quality</h5>
                        <p class="text-muted small mb-0">Crafted with precision using the finest materials for lasting durability.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h5 class="text-white mb-3">Swiss Movement</h5>
                        <p class="text-muted small mb-0">Powered by reliable Japanese quartz movement for accurate timekeeping.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-water"></i>
                        </div>
                        <h5 class="text-white mb-3">Water Resistant</h5>
                        <p class="text-muted small mb-0">50m water resistance suitable for daily wear and light swimming.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section class="py-5">
        <div class="container">
            <h3 class="text-center text-white mb-5">You May Also Like</h3>
            
            <div class="row g-4">
                <?php
                $related = [
                    ['name' => 'Titan Silver Analog', 'price' => 14500, 'img' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Titan Black Dial', 'price' => 18000, 'img' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Titan Gold Edition', 'price' => 22000, 'img' => 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Classic Leather', 'price' => 12000, 'img' => 'https://images.unsplash.com/photo-1434056838489-293029c62689?auto=format&fit=crop&w=400&q=80']
                ];
                
                foreach ($related as $item):
                ?>
                <div class="col-6 col-md-3">
                    <div class="related-product-card">
                        <div class="related-img-wrapper">
                            <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>">
                        </div>
                        <div class="related-body">
                            <h6 class="text-white mb-2"><?php echo $item['name']; ?></h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-gold">LKR <?php echo number_format($item['price']); ?></span>
                                <button class="btn btn-sm btn-gold"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4">
                    <a class="navbar-brand mb-3" href="index.php">
                        <span>POWER <span class="text-gold">WATCH</span></span>
                    </a>
                    <p>Your trusted source for premium timepieces since 2020.</p>
                </div>
                <div class="col-md-2 col-6">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#">Men's</a></li>
                        <li><a href="#">Women's</a></li>
                        <li><a href="#">About Us</a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-6">
                    <h5>Customer Care</h5>
                    <ul>
                        <li><a href="#">Warranty Info</a></li>
                        <li><a href="#">Returns Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <p>No. 123, Main Street<br>Panadura, Sri Lanka</p>
                    <p>Phone: +94 77 123 4567</p>
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-top border-secondary d-flex justify-content-between flex-wrap">
                <p class="mb-0">&copy; 2026 Power Watch. All rights reserved.</p>
                <div>
                    <span class="me-2">We Accept:</span>
                    <i class="fab fa-cc-mastercard fa-lg me-2"></i>
                    <i class="fab fa-cc-visa fa-lg"></i>
                </div>
            </div>
        </div>
    </footer>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel" style="background-color: var(--prm-blue); border-left: 1px solid var(--chp-gold);">
        <div class="offcanvas-header border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
            <h5 class="offcanvas-title text-white font-oswald text-uppercase" id="cartOffcanvasLabel" style="font-family: 'Oswald', sans-serif; letter-spacing: 1px;">
                <i class="fas fa-shopping-bag text-gold me-2"></i> Your Cart
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        
        <div class="offcanvas-body d-flex flex-column p-0">
            <div id="sideCartItems" class="flex-grow-1 overflow-auto p-3">
                </div>

            <div class="p-3" style="background-color: var(--dark-grey);">
                <h6 class="text-white mb-3" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Recommended Add-ons</h6>
                
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(212, 175, 55, 0.2);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-box-open text-gold fs-4"></i>
                        <div>
                            <p class="m-0 text-white" style="font-size: 0.8rem;">Premium Gift Box</p>
                            <span class="text-gold fw-bold" style="font-size: 0.75rem;">+ LKR 1,500</span>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-gold px-2 py-1" onclick="addAddonToCart('Premium Gift Box', 1500)" style="font-size: 0.7rem;">Add</button>
                </div>

                <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(212, 175, 55, 0.2);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-shield-alt text-gold fs-4"></i>
                        <div>
                            <p class="m-0 text-white" style="font-size: 0.8rem;">+1 Year Ext. Warranty</p>
                            <span class="text-gold fw-bold" style="font-size: 0.75rem;">+ LKR 2,500</span>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-gold px-2 py-1" onclick="addAddonToCart('Extended Warranty', 2500)" style="font-size: 0.7rem;">Add</button>
                </div>
            </div>

            <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.1) !important; background-color: var(--prm-blue);">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Subtotal</span>
                    <span class="text-white fw-bold fs-5" id="sideCartTotal">LKR 0.00</span>
                </div>
                <button onclick="window.location.href='checkout.php'" class="btn btn-gold w-100 py-3 text-uppercase fw-bold" style="letter-spacing: 1px;">
                    Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                </button>
                <button class="btn btn-link text-muted w-100 mt-2 text-decoration-none" data-bs-dismiss="offcanvas">
                    Continue Shopping
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cart.js"></script>

    <script>
        // Product data from PHP
        const productData = {
            id: <?php echo $product['id']; ?>,
            name: '<?php echo addslashes($product['name']); ?>',
            price: <?php echo $product['price']; ?>,
            image: '<?php echo $product['images'][0]; ?>',
            quantity: 1,
            options: {}
        };

        // Change main image
        function changeImage(element) {
            document.getElementById('mainImage').src = element.src;
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            element.classList.add('active');
        }

        // Quantity controls
        function changeQty(delta) {
            const input = document.getElementById('qtyInput');
            let value = parseInt(input.value) + delta;
            if (value < 1) value = 1;
            if (value > 10) value = 10;
            input.value = value;
            productData.quantity = value;
        }

        // Add to cart handler
        function handleAddToCart() {
            productData.quantity = parseInt(document.getElementById('qtyInput').value);
            addToCart(productData);
        }
    </script>
</body>
</html>
