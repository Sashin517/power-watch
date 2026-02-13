<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Power Watch</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --prm-blue: #0A111F;
            --sec-blue: #111b2e; /* Slightly lighter for form fields/cards */
            --chp-gold: #D4AF37;
            --chp-gold-hover: #b5952f;
            --text-light: #f8f9fa;
            --text-muted: #adb5bd;
            --border-color: #2d3748;
            --input-bg: #1a2332;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--prm-blue);
            color: var(--text-light);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        /* --- Layout & Grid --- */
        .checkout-container {
            display: flex;
            flex-wrap: wrap;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            padding: 2rem 5%;
            border-right: 1px solid var(--border-color);
        }

        .sidebar-summary {
            flex: 0 0 40%;
            background-color: #0f1724; /* Subtle contrast */
            padding: 2rem 5%;
            border-left: 1px solid var(--border-color);
        }

        /* --- Typography & Links --- */
        a { color: var(--chp-gold); text-decoration: none; transition: 0.3s; }
        a:hover { color: var(--chp-gold-hover); }
        
        .breadcrumb-item + .breadcrumb-item::before { color: var(--text-muted); }
        .breadcrumb a { color: var(--text-muted); font-size: 0.9rem; }
        .breadcrumb-item.active { color: var(--text-light); font-weight: 600; }

        /* --- Forms --- */
        .form-control, .form-select {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
            padding: 0.8rem;
            border-radius: 6px;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg);
            border-color: var(--chp-gold);
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.15);
        }
        .form-control::placeholder { color: #6c757d; }
        
        .form-floating label {
            color: #888;
        }
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--chp-gold);
        }

        /* --- Radio Cards (Payment/Shipping) --- */
        .radio-card-group {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
        }
        .radio-card {
            background-color: var(--input-bg);
            padding: 1rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: background 0.2s;
        }
        .radio-card:last-child { border-bottom: none; }
        .radio-card:hover { background-color: #242e42; }
        
        .radio-card input[type="radio"] {
            accent-color: var(--chp-gold);
            width: 1.2em;
            height: 1.2em;
            margin-right: 1rem;
        }

        /* --- Buttons --- */
        .btn-gold {
            background-color: var(--chp-gold);
            color: #000;
            font-weight: 700;
            padding: 1rem;
            border-radius: 6px;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            transition: all 0.3s;
        }
        .btn-gold:hover {
            background-color: var(--chp-gold-hover);
            transform: translateY(-1px);
        }
        .btn-apply {
            background-color: #333;
            color: #ccc;
            border: 1px solid var(--border-color);
        }

        /* --- Product Thumbnail in Summary --- */
        .product-thumbnail-wrapper {
            position: relative;
            width: 70px;
            height: 70px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #fff;
            padding: 5px;
        }
        .product-thumbnail-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .product-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--chp-gold); /* Updated to Gold */
            color: #000;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            z-index: 2;
        }
        
        /* --- Products List Scrollbar --- */
        .products-list {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 5px;
        }
        .products-list::-webkit-scrollbar {
            width: 5px;
        }
        .products-list::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05); 
        }
        .products-list::-webkit-scrollbar-thumb {
            background: #444; 
            border-radius: 5px;
        }

        /* --- Mobile Toggle --- */
        .order-summary-toggle {
            background-color: #162030;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 5%;
            display: none;
            color: var(--chp-gold);
        }

        /* --- Responsive --- */
        @media (max-width: 991px) {
            .checkout-container { flex-direction: column-reverse; }
            .sidebar-summary { 
                flex: auto; 
                border-left: none; 
                border-bottom: 1px solid var(--border-color);
                display: none; /* Hidden by default on mobile, toggled */
            }
            .sidebar-summary.show { display: block; }
            .main-content { border-right: none; }
            .order-summary-toggle { display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
        }
    </style>
</head>
<body>

    <!-- Mobile Order Summary Toggle -->
    <div class="order-summary-toggle" data-bs-toggle="collapse" data-bs-target="#mobileSummary" aria-expanded="false">
        <div class="d-flex align-items-center">
            <span class="me-2">Show order summary</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="fw-bold">LKR 26,500.00</div>
    </div>

    <div class="checkout-container">
        
        <!-- Left Side: Forms -->
        <div class="main-content">
            <!-- Brand Header -->
            <div class="mb-4">
                <a href="PowerWatch_eCommerce.html" class="d-flex align-items-center text-decoration-none">
                    <svg width="30" height="30" viewBox="0 0 100 100" fill="none" stroke="var(--chp-gold)" stroke-width="5" class="me-2">
                        <path d="M50 10 L90 30 L90 70 L50 90 L10 70 L10 30 Z" />
                        <circle cx="50" cy="50" r="20" />
                    </svg>
                    <span class="brand-font h4 mb-0 text-white">POWER <span class="text-gold">WATCH</span></span>
                </a>
            </div>

            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Cart</a></li>
                    <li class="breadcrumb-item active">Information</li>
                    <li class="breadcrumb-item">Shipping</li>
                    <li class="breadcrumb-item">Payment</li>
                </ol>
            </nav>

            <!-- Contact Information -->
            <section class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Contact</h5>
                    <a href="#" class="small">Log in</a>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="name@example.com">
                    <label for="email">Email or mobile phone number</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="newsCheck" style="background-color: var(--input-bg); border-color: #555;">
                    <label class="form-check-label text-muted small" for="newsCheck">
                        Email me with news and offers
                    </label>
                </div>
            </section>

            <!-- Shipping Address -->
            <section class="mb-5">
                <h5 class="mb-3">Delivery</h5>
                <div class="form-floating mb-3">
                    <select class="form-select" id="country">
                        <option value="Sri Lanka" selected>Sri Lanka</option>
                    </select>
                    <label for="country">Country/Region</label>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="fname" placeholder="First Name">
                            <label for="fname">First name</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="lname" placeholder="Last Name">
                            <label for="lname">Last name</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="address" placeholder="Address">
                    <label for="address">Address</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="apartment" placeholder="Apartment">
                    <label for="apartment">Apartment, suite, etc. (optional)</label>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="city" placeholder="City">
                            <label for="city">City</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="postal" placeholder="Postal Code">
                            <label for="postal">Postal code (optional)</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="tel" class="form-control" id="phone" placeholder="Phone">
                    <label for="phone">Phone</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="saveInfo" style="background-color: var(--input-bg); border-color: #555;">
                    <label class="form-check-label text-muted small" for="saveInfo">
                        Save this information for next time
                    </label>
                </div>
            </section>

            <!-- Shipping Method (Static for now) -->
            <section class="mb-5">
                <h5 class="mb-3">Shipping method</h5>
                <div class="p-3 rounded bg-danger bg-opacity-10 border border-danger text-danger small">
                    <i class="fas fa-info-circle me-2"></i> Enter your shipping address to view available shipping methods.
                </div>
            </section>

            <!-- Payment -->
            <section class="mb-5">
                <h5 class="mb-1">Payment</h5>
                <p class="text-muted small mb-3">All transactions are secure and encrypted.</p>
                
                <div class="radio-card-group">
                    <!-- Card Payment -->
                    <label class="radio-card justify-content-between">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="paymentMethod" checked>
                            <span>Credit/Debit Card</span>
                        </div>
                        <div class="d-flex gap-1">
                            <i class="fab fa-cc-visa fa-lg text-white"></i>
                            <i class="fab fa-cc-mastercard fa-lg text-white"></i>
                            <i class="fab fa-cc-amex fa-lg text-white"></i>
                        </div>
                    </label>

                    <!-- KOKO -->
                    <label class="radio-card justify-content-between">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="paymentMethod">
                            <span>Pay with <span style="color:#6F95E8; font-weight:bold;">KOKO</span></span>
                        </div>
                        <span class="badge bg-secondary">3 Installments</span>
                    </label>

                    <!-- COD -->
                    <label class="radio-card">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="paymentMethod">
                            <span>Cash on Delivery (COD)</span>
                        </div>
                    </label>
                </div>
            </section>

            <!-- Billing Address -->
            <section class="mb-5">
                <h5 class="mb-3">Billing address</h5>
                <div class="radio-card-group">
                    <label class="radio-card">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="billingAddress" checked>
                            <span>Same as shipping address</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="billingAddress">
                            <span>Use a different billing address</span>
                        </div>
                    </label>
                </div>
            </section>

            <!-- Actions -->
            <div class="d-flex flex-column-reverse flex-md-row justify-content-between align-items-center mt-4">
                <a href="#" class="mt-3 mt-md-0"><i class="fas fa-chevron-left me-2"></i>Return to cart</a>
                <button class="btn btn-gold px-5 py-3 rounded w-md-auto">Pay now</button>
            </div>
            
            <div class="mt-5 pt-3 border-top border-secondary text-muted small">
                <a href="#" class="text-muted me-3">Refund policy</a>
                <a href="#" class="text-muted me-3">Shipping policy</a>
                <a href="#" class="text-muted me-3">Privacy policy</a>
                <a href="#" class="text-muted">Terms of service</a>
            </div>

        </div>

        <!-- Right Side: Sidebar Summary -->
        <div class="sidebar-summary collapse d-lg-block" id="mobileSummary">
            
            <!-- Order Summary Header -->
            <h5 class="mb-4 text-white">Order Summary</h5>

            <!-- Product List (Scrollable) -->
            <div class="products-list mb-3">
                
                <!-- Item 1 -->
                <div class="d-flex align-items-center mb-4">
                    <div class="product-thumbnail-wrapper">
                        <div class="product-badge">1</div>
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=150&q=80" alt="Titan Watch">
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-1 text-white">Titan Quartz Analog Blue Dial</h6>
                        <small class="text-muted d-block">Color: Blue</small>
                        <small class="text-muted d-block">Strap: Stainless Steel</small>
                    </div>
                    <div class="fw-bold">LKR 12,000.00</div>
                </div>

                <!-- Item 2 -->
                <div class="d-flex align-items-center mb-4">
                    <div class="product-thumbnail-wrapper">
                        <div class="product-badge">1</div>
                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=150&q=80" alt="Titan Silver">
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-1 text-white">Titan Silver Analog</h6>
                        <small class="text-muted d-block">Color: Silver</small>
                        <small class="text-muted d-block">Strap: Premium Leather</small>
                    </div>
                    <div class="fw-bold">LKR 14,500.00</div>
                </div>

            </div>

            <!-- Discount Code -->
            <div class="d-flex gap-2 my-4 pt-4 border-top border-secondary">
                <input type="text" class="form-control" placeholder="Discount code">
                <button class="btn btn-apply">Apply</button>
            </div>

            <!-- Totals -->
            <div class="border-top border-secondary pt-4">
                <div class="d-flex justify-content-between mb-2 text-muted">
                    <span>Subtotal</span>
                    <span>LKR 26,500.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-muted">
                    <span>Shipping</span>
                    <span class="small">Calculated at next step</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
                <span class="h5 mb-0 text-white">Total</span>
                <div class="d-flex align-items-baseline">
                    <small class="text-muted me-2">LKR</small>
                    <span class="h3 mb-0 text-gold">26,500.00</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>