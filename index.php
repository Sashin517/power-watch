<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Power Watch - Define Your Legacy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/global.css">

    <style>
        body { background-color: #f4f4f4; }
        .section-title { text-align: center; margin-bottom: 2.5rem; font-weight: 500; letter-spacing: 1px; }

        /* --- Hero Section --- */
        .hero-section { position: relative; height: 600px; background-color: #000; color: white; overflow: hidden; }
        .hero-img { object-fit: cover; width: 100%; height: 100%; opacity: 0.8; object-position: center; }
        .hero-overlay { position: absolute; top: 50%; left: 10%; transform: translateY(-50%); z-index: 2; max-width: 600px; padding: 0 15px; }
        .hero-title { font-size: 4rem; font-weight: 700; line-height: 1.1; margin-bottom: 1rem; text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7); }
        .hero-subtitle { font-size: 1.2rem; margin-bottom: 1.5rem; font-weight: 300; opacity: 0.9; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7); }
        .hero-btn { padding: 12px 30px; font-size: 1rem; letter-spacing: 1px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); }
        .hero-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4); }

        .carousel-control-prev, .carousel-control-next { width: 60px; opacity: 0.7; transition: all 0.3s ease; }
        .carousel-control-prev:hover, .carousel-control-next:hover { opacity: 1; }
        .carousel-control-prev-icon, .carousel-control-next-icon { background-size: 30px; width: 50px; height: 50px; background-color: rgba(0, 0, 0, 0.3); border-radius: 50%; }
        .carousel-indicators { margin-bottom: 2rem; }
        .carousel-indicators [data-bs-target] { width: 12px; height: 12px; border-radius: 50%; margin: 0 6px; border: 2px solid white; opacity: 0.6; transition: all 0.3s ease; }
        .carousel-indicators .active { opacity: 1; background-color: var(--chp-gold); border-color: var(--chp-gold); }

        /* --- Brand Logos --- */
        .brand-grid { background-color: var(--prm-blue); padding: 3rem 0; }
        .brand-item { background-color: var(--fd-blue); border-radius: 6px; height: 64px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; transition: transform 0.2s; padding: 12px; }
        .brand-item:hover { transform: translateY(-3px); }
        .brand-item img { width: 100%; height: 100%; object-fit: contain; display: block; }
        
        /* Carousel Structure Resets */
        #premiumCarousel, #premiumCarousel .carousel-inner, #premiumCarousel .carousel-item,
        #peoplesChoiceCarousel, #peoplesChoiceCarousel .carousel-inner, #peoplesChoiceCarousel .carousel-item { height: auto; }
        #premiumCarousel .row, #peoplesChoiceCarousel .row { display: flex; flex-wrap: wrap; }
        #premiumCarousel .row > [class*='col-'], #peoplesChoiceCarousel .row > [class*='col-'] { display: flex; flex-direction: column; }

        /* --- Product Cards (COMPACT VERSION for Index) --- */
        .product-card { background: white; border: none; border-radius: 8px; overflow: hidden; box-shadow: 0 3px 15px rgba(0,0,0,0.08); transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 6px 25px rgba(0,0,0,0.12); }
        .card-img-wrapper { position: relative; padding: 15px; text-align: center; background: #fff; height: 180px; min-height: 180px; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid rgba(0,0,0,0.05); flex-shrink: 0; }
        .discount-badge { position: absolute; top: 8px; left: 8px; background: linear-gradient(135deg, var(--discount-green) 0%, #2bc04e 100%); color: white; padding: 4px 8px; border-radius: 4px; font-weight: 700; font-size: 0.7rem; box-shadow: 0 2px 6px rgba(60, 231, 74, 0.3); z-index: 10; letter-spacing: 0.3px; }
        .product-img { max-height: 100%; max-width: 100%; object-fit: contain; }
        .card-body { padding: 1.25rem; background-color: var(--card-bg); color: white; display: flex; flex-direction: column; flex-grow: 1; }
        .card-title { font-size: 1rem; font-weight: 500; margin-bottom: 0.5rem; line-height: 1.4; min-height: 34px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; }
        .price-section { margin-bottom: 0.5rem; }
        .price-row { display: flex; justify-content: space-between; align-items: center; }
        .price-container { display: flex; flex-direction: column; gap: 2px; }
        .original-price { font-size: 0.8rem; color: var(--fd-blue); text-decoration: line-through; font-weight: 500; }
        .price { font-weight: 700; font-size: 1rem; color: var(--chp-gold); }
        .eye-icon { background: rgba(255,255,255,0.15); padding: 5px 6px; border-radius: 4px; color: var(--chp-gold); transition: all 0.3s; cursor: pointer; font-size: 0.85rem; }
        .eye-icon:hover { background: rgba(255,255,255,0.25); transform: scale(1.1); }
        .installment-text { font-size: 0.75rem; color: #bbb; margin-bottom: 0.75rem; line-height: 1.3; }
        .koko-brand { font-weight: 700; color: #7191D9; }
        .btn-card { width: 100%; margin-top: auto; text-transform: uppercase; font-size: 0.7rem; padding: 8px; font-weight: 600; letter-spacing: 0.3px; transition: all 0.3s ease; }
        .btn-card:hover { transform: translateY(-2px); box-shadow: 0 3px 10px rgba(0,0,0,0.15); }

        /* --- Banner Sections --- */
        .banner-section { position: relative; height: 400px; background-size: cover; background-position: center; display: flex; align-items: center; }
        .banner-overlay { background: rgba(0,0,0,0.5); position: absolute; top: 0; left: 0; right: 0; bottom: 0; }
        .banner-content { position: relative; z-index: 2; color: white; padding: 2rem; }
        .banner-street { background-image: url('assets/images/home/body-banners/bdy-bnr-img-3.png'); }
        .banner-gold-watch { background-image: url('assets/images/home/body-banners/bdy-bnr-img-1.png'); }
        .banner-wall-clock { background-image: url('assets/images/home/body-banners/bdy-bnr-img-2.png'); }

        /* Responsive Breakpoints */
        @media (max-width: 991px) {
            .hero-section { height: 500px; }
            .hero-overlay { left: 5%; max-width: 500px; }
            .hero-title { font-size: 3rem; margin-bottom: 0.8rem; }
            .card-img-wrapper { height: 170px; min-height: 170px; padding: 12px; }
            .card-body { padding: 1.1rem; }
        }
        @media (max-width: 767px) {
            .hero-section { height: 450px; }
            .hero-overlay { left: 50%; top: 50%; transform: translate(-50%, -50%); text-align: center; max-width: 90%; width: 100%; }
            .hero-title { font-size: 2.5rem; }
            .section-title { font-size: 1.5rem; }
            .banner-street { background-image: url('assets/images/home/body-banners/bdy-bnr-img-3-mobile.png'); }
            .banner-gold-watch { background-image: url('assets/images/home/body-banners/bdy-bnr-img-1-mobile.png'); }
            .banner-wall-clock { background-image: url('assets/images/home/body-banners/bdy-bnr-img-2-mobile.png'); }
            .card-img-wrapper { height: 160px; min-height: 160px; padding: 10px; }
            .card-title { font-size: 0.875rem; }
            .carousel-control-prev, .carousel-control-next { width: 45.6px;}
            .carousel-control-prev-icon, .carousel-control-next-icon { background-size: 22.8px; width: 38px; height: 38px; }
        }
        @media (max-width: 575px) {
            .hero-section { height: 400px; }
            .hero-title { font-size: 2rem; }
            .card-img-wrapper { height: 140px; min-height: 140px; }
            .product-card:hover { transform: none; }
        }
        @media (min-width: 1400px) {
            .hero-section { height: 700px; }
            .hero-title { font-size: 4.5rem; }
        }
    </style>
</head>
    <body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="marquee-container">
            <div class="marquee-content">
                <span><i class="fas fa-phone-alt me-2"></i> 011-22264589</span>
                <span><i class="fas fa-truck me-2"></i> Island-wide cash on delivery</span>
                <span><i class="fas fa-tag me-2"></i> 10% limited-time offer</span>
            </div>
            <div class="marquee-content">
                <span><i class="fas fa-phone-alt me-2"></i> 011-22264589</span>
                <span><i class="fas fa-truck me-2"></i> Island-wide cash on delivery</span>
                <span><i class="fas fa-tag me-2"></i> 10% limited-time offer</span>
            </div>
        </div>
    </div>
    
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div id="heroCarousel" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="5000">
            
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner h-100">
                
                <div class="carousel-item active h-100">
                    <picture>
                        <source media="(max-width: 576px)" srcset="assets/images/home/hero-section-banners/hr-sec-img-1-mobile.png">
                        <source media="(max-width: 992px)" srcset="assets/images/home/hero-section-banners/hr-sec-img-1-tablet.png">
                        <img src="assets/images/home/hero-section-banners/hr-sec-img-1.png" alt="Man with watch" class="hero-img d-block w-100">
                    </picture>
                    <div class="hero-overlay">
                        <div class="container">
                            <h1 class="hero-title text-uppercase">Define<br>Your Legacy</h1>
                            <p class="hero-subtitle d-none d-md-block">Discover timepieces that tell your story</p>
                            <a href="#" class="btn btn-gold hero-btn">SHOP PREMIUM COLLECTION</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item h-100">
                    <picture>
                        <source media="(max-width: 576px)" srcset="assets/images/home/hero-section-banners/hr-sec-img-2-mobile.png">
                        <source media="(max-width: 992px)" srcset="assets/images/home/hero-section-banners/hr-sec-img-2-tablet.png">
                        <img src="assets/images/home/hero-section-banners/hr-sec-img-2.png" alt="Premium Watch" class="hero-img d-block w-100">
                    </picture>
                    <div class="hero-overlay">
                        <div class="container">
                            <h1 class="hero-title text-uppercase">Timeless<br>Elegance</h1>
                            <p class="hero-subtitle d-none d-md-block">Where craftsmanship meets sophistication</p>
                            <a href="#" class="btn btn-gold hero-btn">VIEW NEW ARRIVALS</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item h-100">
                    <picture>
                        <source media="(max-width: 576px)" srcset="assets/images/home/hero-section-banners/hr-sec-img-3-mobile.png">
                        <source media="(max-width: 992px)" srcset="assets/images/home/hero-section-banners/hr-sec-img-3-tablet.png">
                        <img src="assets/images/home/hero-section-banners/hr-sec-img-3.png" alt="Gold Watch" class="hero-img d-block w-100">
                    </picture>
                    <div class="hero-overlay">
                        <div class="container">
                            <h1 class="hero-title text-uppercase">Golden<br>Moments</h1>
                            <p class="hero-subtitle d-none d-md-block">Celebrate life's precious moments in style</p>
                            <a href="#" class="btn btn-gold hero-btn">SHOP EXCLUSIVE</a>
                        </div>
                    </div>
                </div>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Brand Logos Grid -->
    <section class="brand-grid">
        <div class="container">
            <div class="row g-3 d-flex justify-content-center">
                <div class="col-4 col-md-2">
                    <div class="brand-item">
                        <img src="assets/images/watch-brand-logos/aviator.png" alt="Aviator">
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="brand-item">
                        <img src="assets/images/watch-brand-logos/casio.png" alt="Casio">
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="brand-item">
                        <img src="assets/images/watch-brand-logos/police.png" alt="Police">
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="brand-item">
                        <img src="assets/images/watch-brand-logos/seiko.png" alt="Seiko">
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="brand-item">
                        <img src="assets/images/watch-brand-logos/citizen.png" alt="Citizen">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- The Premium Collection -->
    <section class="py-5" style="background-color: var(--cream-bg);">
        <div class="container">
            <h2 class="section-title text-uppercase" style="color: var(--prm-blue) !important; font-weight: 700;">The Premium Collection</h2>
            
            <div id="premiumCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="premiumCarouselInner">
                    <div class="text-center py-5"><div class="spinner-border text-gold" role="status"></div></div>
                </div>
            </div>
            <!-- closes #premiumCarousel -->
            <div class="text-center mt-3" id="premiumDots"></div>
        </div>
    </section>

    <!-- Banner 1: More Than A Watch Shop -->
    <section class="banner-section banner-street">
        <div class="banner-overlay"></div>
        <div class="container">
            <div class="banner-content">
                <h2 class="text-uppercase fw-bold mb-3" style="font-size: 2.5rem;">More Than A Watch Shop</h2>
                <p class="fs-5">Panadura's trusting choice for more than 20 years</p>
            </div>
        </div>
    </section>

    <!-- People's Choice -->
    <section class="py-5" style="background-color: var(--prm-blue); color: white;">
        <div class="container">
            <h2 class="section-title text-uppercase text-white">Popular Choice</h2>
            
            <div id="peoplesChoiceCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="peoplesChoiceCarouselInner">
                    <div class="text-center py-5"><div class="spinner-border text-white" role="status"></div></div>
                </div>
            </div>
            <!-- closes #peoplesChoiceCarousel -->
            <div class="text-center mt-3" id="peoplesChoiceDots"></div>
        </div>
    </section>

    <!-- Banner 2: Well Recognized Brands -->
    <section class="banner-section banner-gold-watch">
        <div class="banner-overlay"></div>
        <div class="container text-end">
            <div class="banner-content">
                    <h2 class="text-uppercase fw-bold" style="font-size: 3rem;">Well Recognized Brands</h2>
                    <p class="text-end">World's most popular recognized brands</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Favorite Brands -->
    <section class="py-5" style="background-color: var(--prm-blue); color: white;">
        <div class="container">
            <h2 class="section-title text-uppercase text-white">Favorite Brands</h2>
            
            <!-- Tabs -->
            <div class="d-flex justify-content-center gap-2 mb-4" id="brandTabs">
                <button class="btn btn-light rounded-pill px-4 brand-tab" onclick="filterByBrand('Casio'); updateActiveTab(this);">Casio</button>
                <button class="btn btn-outline-light rounded-pill px-4 brand-tab" onclick="filterByBrand('Titan'); updateActiveTab(this);">Titan</button>
                <button class="btn btn-outline-light rounded-pill px-4 brand-tab" onclick="filterByBrand('Seiko'); updateActiveTab(this);">Seiko</button>
            </div>

            <!-- Carousel Grid (similar to Premium Collection) -->
            <div id="favoriteBrandsCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner" id="favoriteBrandsCarouselInner">
                    <div class="text-center py-5"><div class="spinner-border text-white" role="status"></div></div>
                </div>
            </div>
            <!-- closes #favoriteBrandsCarousel -->
            <div class="text-center mt-3" id="favoriteBrandsDots"></div>

            <div class="text-center">
                <button class="btn btn-gold rounded-pill px-5">See more</button>
            </div>
        </div>
    </section>

    <!-- Banner 3: Decorate Your Home -->
    <section class="banner-section banner-wall-clock">
        <div class="banner-overlay"></div>
        <div class="container">
            <div class="banner-content">
                <h2 class="text-uppercase fw-bold mb-3" style="font-size: 2.5rem;">Decorate Your Home</h2>
                <p class="fs-5">Statement pieces that transform your living space</p>
            </div>
        </div>
    </section>

    <!-- Elevate Your Style (Wall Clocks) -->
    <section class="py-5" style="background-color: var(--prm-blue); color: white;">
        <div class="container">
            <h2 class="section-title text-uppercase text-white">Elevate Your Style</h2>
            
            <div id="wallClockCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="wallClockCarouselInner">
                    <div class="text-center py-5"><div class="spinner-border text-white" role="status"></div></div>
                </div>
            </div>
            <!-- closes #wallClockCarousel -->
            <div class="text-center mt-3" id="wallClockDots"></div>
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
                            <p class="m-0 text-white fw-medium" style="font-size: 0.85rem;">Premium Gift Box</p>
                            <span class="text-white" style="font-size: 0.75rem;">LKR 1,500</span>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-gold rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;" onclick="quickAddToCart(19, 'Premium Gift Box', 1500, 'assets/images/products/69dcb97048195_img0.png')">Add</button>
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

    <script src="js/cart.js"></script>
    <script>
        function updateActiveTab(clickedBtn) {
            // Remove active styling from all tabs
            document.querySelectorAll('.brand-tab').forEach(btn => {
                btn.classList.remove('btn-light');
                btn.classList.add('btn-outline-light');
            });
            // Add active styling to clicked tab
            clickedBtn.classList.remove('btn-outline-light');
            clickedBtn.classList.add('btn-light');
        }
    </script>
    <script>
        // Global variable to store all products for brand filtering
        let globalProducts = [];

        document.addEventListener('DOMContentLoaded', async () => {
            try {
                // Fetch data from your PHP endpoint
                const response = await fetch('admin/actions/products-data.php');
                if (!response.ok) throw new Error('Network response was not ok');
                
                globalProducts = await response.json();
                
                // 1. Filter for Premium Collection (is_premium == true)
                const premiumProducts = globalProducts.filter(p => p.is_premium === true);
                renderCarouselGrid(premiumProducts, 'premiumCarouselInner', 'btn-gold');
                
                // 2. Filter for People's Choice (is_peoples_choice == true)
                const popularProducts = globalProducts.filter(p => p.is_peoples_choice === true);
                renderCarouselGrid(popularProducts, 'peoplesChoiceCarouselInner', 'btn-blue');

                // 3. Filter for Wall Decor (Category Name === 'Wall Decor')
                const wallDecorProducts = globalProducts.filter(p => p.category === 'Wall Decor');
                renderCarouselGrid(wallDecorProducts, 'wallClockCarouselInner', 'btn-brown');

                // 4. Initial load for Favorite Brands (Default to 'Casio')
                filterByBrand('Casio');

            } catch (error) {
                console.error("Give us a second. We’re winding the collection.:", error);
                document.getElementById('premiumCarouselInner').innerHTML = '<div class="text-center text-danger py-4">Give us a second. We’re winding the collection.</div>';
                document.getElementById('peoplesChoiceCarouselInner').innerHTML = '<div class="text-center text-danger py-4">Give us a second. We’re winding the collection.</div>';
                document.getElementById('favoriteBrandsCarouselInner').innerHTML = '<div class="text-center text-danger py-4">Give us a second. We’re winding the collection.</div>';
                document.getElementById('wallClockCarouselInner').innerHTML = '<div class="text-center text-danger py-4">Give us a second. We’re winding the collection.</div>';
            }
        });

        // Function to filter the bottom section by Brand Name
        function filterByBrand(brandName) {
            const brandProducts = globalProducts.filter(p => p.brand && p.brand.name.toLowerCase() === brandName.toLowerCase());
            renderCarouselGrid(brandProducts, 'favoriteBrandsCarouselInner', 'btn-brown');
        }

        // The Master Function that builds the HTML
        function renderCarouselGrid(products, containerId, btnClass) {
            const container = document.getElementById(containerId);
            if(!container) return;

            const dotsMap = {
                'premiumCarouselInner':       'premiumDots',
                'peoplesChoiceCarouselInner':'peoplesChoiceDots',
                'favoriteBrandsCarouselInner':'favoriteBrandsDots',
                'wallClockCarouselInner':    'wallClockDots'
            };

            if (products.length === 0) {
                container.innerHTML = '<div class="text-center text-secondary py-5">No products found in this category.</div>';
                return;
            }

            let html = '';
            const itemsPerSlide = window.innerWidth >= 992 ? 6
                    : window.innerWidth >= 768 ? 4
                    : window.innerWidth >= 576 ? 3
                    : 2;

            // Loop through products in chunks of 6
            for (let i = 0; i < products.length; i += itemsPerSlide) {
                const chunk = products.slice(i, i + itemsPerSlide);
                const isActive = i === 0 ? 'active' : '';
                
                html += `<div class="carousel-item ${isActive}"><div class="row g-3 justify-content-center">`;
                
                chunk.forEach(p => {
                    // Format Prices
                    const currentPrice = new Intl.NumberFormat('en-LK').format(p.pricing.current_price);
                    const kokoInstallment = new Intl.NumberFormat('en-LK').format(p.pricing.koko_installment);
                    
                    // Handle Discount Badge & Old Price
                    let oldPriceHtml = '';
                    let badgeHtml = '';
                    if (p.pricing.discount_percent > 0) {
                        const originalPrice = new Intl.NumberFormat('en-LK').format(p.pricing.original_price);
                        oldPriceHtml = `<span class="original-price">LKR ${originalPrice}</span>`;
                        badgeHtml = `<div class="discount-badge">-${p.pricing.discount_percent}% OFF</div>`;
                    }

                    // Handle Image
                    const img = p.primary_thumbnail ? p.primary_thumbnail : 'assets/images/products/default.png';

                    // Ensure safe string passing to JS functions
                    const safeName = p.name.replace(/'/g, "\\'").replace(/"/g, '&quot;');

                    // Build Card HTML
                    html += `
                    <div class="col-6 col-lg-2">
                        <div class="product-card">
                            <div class="card-img-wrapper" style="cursor:pointer;" onclick="window.location.href='product-page.php?id=${p.id}'">
                                ${badgeHtml}
                                <img src="${img}" class="product-img" alt="${safeName}" onerror="this.onerror=null; this.src='assets/images/products/default.png'">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title" style="cursor:pointer;" onclick="window.location.href='product-page.php?id=${p.id}'">${p.name}</h5>
                                <div class="price-section">
                                    <div class="price-row">
                                        <div class="price-container">
                                            ${oldPriceHtml}
                                            <span class="price">LKR ${currentPrice}</span>
                                        </div>
                                        <i class="fa-solid fa-eye eye-icon" onclick="window.location.href='product-page.php?id=${p.id}'"></i>
                                    </div>
                                </div>
                                <p class="installment-text">or pay in 3 x Rs ${kokoInstallment} with <span class="koko-brand">KOKO</span></p>
                                <button class="btn ${btnClass} btn-card" onclick="quickAddToCart(${p.id}, '${safeName}', ${p.pricing.current_price}, '${img}')">Add to Cart</button>
                            </div>
                        </div>
                    </div>`;
                });
                
                html += `</div></div>`;
            }
            
            container.innerHTML = html;

            // Show/hide navigation arrows based on number of slides
            const totalSlides = Math.ceil(products.length / itemsPerSlide);
            // Only inject nav buttons if more than 1 slide
            const carouselEl = container.closest('.carousel');
            if (carouselEl) {
                // Remove any previously injected buttons first (for re-renders e.g. brand tab switch)
                carouselEl.querySelectorAll('.carousel-control-prev, .carousel-control-next').forEach(b => b.remove());

                if (totalSlides > 1) {
                    const carouselId = carouselEl.id;
                    carouselEl.insertAdjacentHTML('beforeend', `
                        <button class="carousel-control-prev" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#${carouselId}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    `);
                }
            }

            // Render dynamic dots
            const dotsContainer = document.getElementById(dotsMap[containerId]);
            if (dotsContainer) {
                if (totalSlides <= 1) {
                    dotsContainer.innerHTML = ''; // hide dots if only one slide
                } else {
                    let dotsHtml = '';
                    for (let d = 0; d < totalSlides; d++) {
                        // If the container is the premium carousel (cream bg), use dark blue. Otherwise, use white.
                        const activeBg = d === 0 ? (containerId === 'premiumCarouselInner' ? 'var(--prm-blue)' : 'white') : '#666';
                        dotsHtml += `<span 
                            data-slide="${d}" 
                            data-carousel="${carouselEl ? carouselEl.id : ''}"
                            style="display:inline-block; width:8px; height:8px; background:${activeBg}; border-radius:50%; margin:0 3px; cursor:pointer; transition:background 0.3s;"
                        ></span>`;
                    }
                    dotsContainer.innerHTML = dotsHtml;

                    // Click on dot to go to that slide
                    dotsContainer.querySelectorAll('span').forEach(dot => {
                        dot.addEventListener('click', () => {
                            const carousel = bootstrap.Carousel.getOrCreateInstance(
                                document.getElementById(dot.dataset.carousel)
                            );
                            carousel.to(parseInt(dot.dataset.slide));
                        });
                    });

                    // Sync active dot when carousel slides
                    if (carouselEl) {
                        carouselEl.addEventListener('slide.bs.carousel', (e) => {
                            dotsContainer.querySelectorAll('span').forEach((dot, i) => {
                                dot.style.background = i === e.to ? 'white' : '#666';
                            });
                        });
                    }
                }
            }
        }

        // Quick Add to Cart from Home Page
        function quickAddToCart(id, name, price, img) {
            const productData = { 
                id: id, 
                name: name, 
                price: parseFloat(price), 
                image: img, 
                quantity: 1,
                options: { Type: 'Standard' } 
            };
            
            // This calls the function inside your cart.js
            addToCart(productData); 
        }

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                // Re-render all sections with current product data
                const premiumProducts = globalProducts.filter(p => p.is_premium === true);
                renderCarouselGrid(premiumProducts, 'premiumCarouselInner', 'btn-gold');

                const popularProducts = globalProducts.filter(p => p.is_peoples_choice === true);
                renderCarouselGrid(popularProducts, 'peoplesChoiceCarouselInner', 'btn-blue');

                const wallDecorProducts = globalProducts.filter(p => p.category === 'Wall Decor');
                renderCarouselGrid(wallDecorProducts, 'wallClockCarouselInner', 'btn-brown');

                // Re-render active brand tab
                const activeTab = document.querySelector('.brand-tab.btn-light');
                if (activeTab) filterByBrand(activeTab.textContent.trim());
            }, 300); // debounce — waits 300ms after resize stops
        });
    </script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>