<?php
    session_start();

    if(!isset($_SESSION["u"])){
        // Not logged in? Send them back to signin.php
        header("Location: login.php");
        exit();
    }

    $user_data = $_SESSION["u"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Power Watch</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --prm-blue: #0A111F; /* Deep Navy Main BG */
            --sec-blue: #151f32; /* Sidebar/Card BG */
            --chp-gold: #D4AF37; /* Gold Accent */
            --chp-gold-hover: #b5952f;
            --text-light: #f8f9fa;
            --text-muted: #adb5bd;
            --border-color: #2d3748;
            --input-bg: #0f1623;
            --success-green: #2ecc71;
            --danger-red: #e74c3c;
            --info-blue: #3498db;
            --warning-orange: #f39c12;
        }

        .brand-logo-img {
            max-height: 40px;
            width: 100%;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--prm-blue);
            color: var(--text-light);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: var(--sec-blue);
            position: fixed;
            top: 0;
            left: 0;
            border-right: 1px solid var(--border-color);
            z-index: 1050; /* Higher z-index for mobile overlap */
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            padding: 1.5rem 1rem;
            flex-grow: 1;
            list-style: none;
            overflow-y: auto; /* Allow scrolling within menu if tall */
        }

        .menu-item {
            margin-bottom: 0.5rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .menu-link i {
            width: 25px;
            font-size: 1.1rem;
        }

        .menu-link:hover, .menu-link.active {
            background-color: rgba(212, 175, 55, 0.1);
            color: var(--chp-gold);
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            background-color: var(--sec-blue); /* Ensure opaque background */
        }

        /* --- Main Content --- */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        /* --- Header --- */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 0;
            margin-right: 15px;
            cursor: pointer;
        }

        /* --- Cards --- */
        .dashboard-card {
            background-color: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.2s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            border-color: var(--chp-gold);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .bg-icon-gold { background: rgba(212, 175, 55, 0.2); color: var(--chp-gold); }
        .bg-icon-blue { background: rgba(111, 149, 232, 0.2); color: var(--info-blue); }
        .bg-icon-green { background: rgba(46, 204, 113, 0.2); color: var(--success-green); }
        .bg-icon-orange { background: rgba(243, 156, 18, 0.2); color: var(--warning-orange); }

        /* --- Table --- */
        .custom-table-container {
            background-color: var(--sec-blue);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .table-dark-custom {
            --bs-table-bg: var(--sec-blue);
            --bs-table-color: var(--text-light);
            --bs-table-border-color: var(--border-color);
            margin-bottom: 0;
            white-space: nowrap; /* Prevent wrapping on small screens for cleaner scroll */
        }
        
        .table-dark-custom th {
            background-color: #1a253a;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 1rem;
        }
        
        .table-dark-custom td {
            padding: 1rem;
            vertical-align: middle;
        }

        .product-thumb {
            width: 40px;
            height: 40px;
            object-fit: contain;
            background: white;
            border-radius: 4px;
            padding: 2px;
        }

        .text-muted{
            color: var(--text-muted) !important;
        }
        
        .customer-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            background-color: var(--border-color);
        }

        /* Placeholder color */
        .form-control::placeholder {
            color: var(--text-muted); /* #adb5bd — matches your muted text color */
            opacity: 1;               /* Firefox reduces opacity by default, this fixes it */
        }

        .form-select::placeholder {
            color: var(--text-muted);
            opacity: 1;
        }

        /* --- Forms & Filters --- */
        .form-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg);
            border-color: var(--chp-gold);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.15);
        }
        
        /* Pagination */
        .pagination .page-link {
            background-color: var(--sec-blue);
            border-color: var(--border-color);
            color: var(--text-muted);
        }
        .pagination .page-link:hover {
            background-color: var(--input-bg);
            color: var(--chp-gold);
            border-color: var(--chp-gold);
        }
        .pagination .page-item.active .page-link {
            background-color: var(--chp-gold);
            border-color: var(--chp-gold);
            color: #000;
        }

        /* Drag Drop Zone */
        .drop-zone {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background-color: rgba(255,255,255,0.02);
            transition: all 0.3s;
            cursor: pointer;
        }
        .drop-zone:hover {
            border-color: var(--chp-gold);
            background-color: rgba(212, 175, 55, 0.05);
        }

        /* New Image Preview Grid */
        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        
        .preview-item {
            position: relative;
            width: 100%;
            padding-top: 100%; /* 1:1 Aspect Ratio */
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            background-color: var(--input-bg);
        }
        
        .preview-item img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(231, 76, 60, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 10px;
            transition: 0.2s;
        }
        
        .remove-btn:hover {
            background: #c0392b;
            transform: scale(1.1);
        }

        /* Buttons */
        .btn-gold {
            background-color: var(--chp-gold);
            color: #000;
            font-weight: 600;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            transition: 0.3s;
        }
        .btn-gold:hover {
            background-color: var(--chp-gold-hover);
            color: #000;
        }

        .btn-outline-gold {
            border: 1px solid var(--chp-gold);
            color: var(--chp-gold);
            background: transparent;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 6px;
            transition: 0.3s;
        }
        .btn-outline-gold:hover {
            background-color: var(--chp-gold);
            color: #000;
        }
        
        .btn-icon-action {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            width: 32px;
            height: 32px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        .btn-icon-action:hover {
            border-color: var(--chp-gold);
            color: var(--chp-gold);
        }

        /* Status Badges */
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
        }
        .status-paid, .status-delivered, .status-instock { background-color: rgba(46, 204, 113, 0.15); color: var(--success-green); }
        .status-pending, .status-processing, .status-lowstock { background-color: rgba(243, 156, 18, 0.15); color: var(--warning-orange); }
        .status-unpaid, .status-cancelled, .status-outstock { background-color: rgba(231, 76, 60, 0.15); color: var(--danger-red); }
        .status-shipped { background-color: rgba(52, 152, 219, 0.15); color: var(--info-blue); }

        /* Toggles */
        .form-check-input {
            background-color: var(--input-bg);
            border-color: var(--border-color);
        }
        .form-check-input:checked {
            background-color: var(--chp-gold);
            border-color: var(--chp-gold);
        }

        /* Utils */
        .d-none-view { display: none !important; }
        
        /* Mobile Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(2px);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .overlay.active {
            display: block;
            opacity: 1;
        }

        /* --- Custom Toast Styles --- */
        .toast {
            background-color: var(--sec-blue); /* Your Sidebar Color */
            color: var(--text-light);
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            min-width: 300px;
        }

        /* Success Type */
        .toast.toast-success {
            border-left: 4px solid var(--success-green);
        }
        .toast.toast-success i { color: var(--success-green); }

        /* Error Type */
        .toast.toast-error {
            border-left: 4px solid var(--danger-red);
        }
        .toast.toast-error i { color: var(--danger-red); }
        /* --- RESPONSIVE QUERIES --- */
        
        /* Tablet & Mobile (Below 992px) */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .mobile-toggle {
                display: block;
            }
        }

        /* Mobile (Below 576px) */
        @media (max-width: 576px) {
            .main-content {
                padding: 1rem;
            }
            
            .top-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            
            .top-header .d-flex:last-child { /* Action buttons container */
                justify-content: space-between;
                width: 100%;
            }
            
            .dashboard-card {
                padding: 1.25rem;
            }
            
            .btn-gold, .btn-outline-gold {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
            
            /* Stack filter bars */
            .filter-bar {
                flex-direction: column;
                gap: 10px;
            }
            .filter-bar > * {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Overlay -->
    <div class="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="../index.php" class="sidebar-brand">
                <img src="../assets/images/brand-logos/logo5.png" alt="Logo" class="brand-logo-img">
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="#" class="menu-link active" onclick="switchView('dashboard', this)">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link" onclick="switchView('add-product', this)">
                    <i class="fas fa-plus-circle"></i> Add Product
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link" onclick="switchView('inventory', this)">
                    <i class="fas fa-box-open"></i> Inventory
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link" onclick="switchView('orders', this)">
                    <i class="fas fa-shopping-bag"></i> Orders
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link" onclick="switchView('customers', this)">
                    <i class="fas fa-users"></i> Customers
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-3">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=100&q=80" alt="Admin" class="rounded-circle" width="40" height="40">
                <div>
                    <p class="m-0 small fw-bold text-white"><?php echo $user_data["fname"]; ?></p>
                    <p class="m-0 small text">Admin</p>
                </div>
                <a href="#" class="ms-auto text hover-gold"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Header -->
        <header class="top-header">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h2 class="h4 m-0 brand-font text-white" id="pageTitle">Overview</h2>
            </div>
            <div class="d-flex gap-3">
                <button class="btn btn-outline-gold rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-bell"></i></button>
                <button class="btn btn-gold">Visit Store <i class="fas fa-external-link-alt ms-2 d-none d-sm-inline"></i></button>
            </div>
        </header>

        <!-- View: Dashboard Overview -->
        <div id="view-dashboard">
            <!-- Stats Row -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="stat-icon bg-icon-gold">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3 class="h2 fw-bold text-white mb-1">LKR 450k</h3>
                        <p class="text m-0">Total Sales (This Month)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="stat-icon bg-icon-blue">
                            <i class="fas fa-box"></i>
                        </div>
                        <h3 class="h2 fw-bold text-white mb-1">251</h3>
                        <p class="text m-0">Total Products</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="stat-icon bg-icon-green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="h2 fw-bold text-white mb-1">18</h3>
                        <p class="text m-0">New Orders</p>
                    </div>
                </div>
            </div>

            <!-- Recent Products Table -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-white m-0 h5">Recent Inventory</h4>
                <button class="btn btn-sm btn-outline-gold" onclick="switchView('add-product', document.querySelectorAll('.menu-link')[1])">Add New</button>
            </div>

<div class="custom-table-container">
                <div class="table-responsive">
                    <table class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                            <tbody id="recentProductsTableBody">
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="spinner-border text-gold spinner-border-sm" role="status"></div>
                                        <span class="ms-2 text-muted">Loading inventory...</span>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- View: Add Product Form -->
        <div id="view-add-product" class="d-none-view">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form class="dashboard-card p-4" id="productForm">
                        <h4 id="formTitle" class="text-gold mb-4 border-bottom border-secondary pb-3">Add New Product</h4>
                        
                        <input type="hidden" id="editProductId" value="">
                        
                        <!-- Basic Info -->
                        <div class="row g-4 mb-4">
                            <div class="col-12">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="productName" placeholder="e.g. Titan Quartz Analog Blue Dial">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Brand</label>
                                <select class="form-select" id="productBrand">
                                    <option selected disabled value="0">Select Brand</option>
                                    <option value="1">Casio</option>
                                    <option value="2">Titan</option>
                                    <option value="3">Seiko</option>
                                    <option value="4">Rolex</option>
                                    <option value="5">Omega</option>
                                    <option value="6">Fossil</option>
                                    <option value="7">Citizen</option>
                                    <option value="8">Police</option>
                                    <option value="9">Aviator</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sub Category</label>
                                <select class="form-select" id="productCategory">
                                    <option selected disabled value="0">Select Category</option>
                                    <optgroup label="Wristwatch">
                                        <option value="1">Men's Watches</option>
                                        <option value="2">Women's Watches</option>
                                        <option value="3">Smart Watches</option>
                                        <option value="4">Luxury Collection</option>
                                    </optgroup>
                                    <optgroup label="Wall Decor">
                                        <option value="5">Wall Clocks</option>
                                        <option value="6">Photo Frames</option>
                                        <option value="7">Wall Art</option>
                                        <option value="8">Mirrors</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="productDesc" rows="4" placeholder="Product details..."></textarea>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <h5 class="text-white mb-3">Pricing & Stock</h5>
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Original Price (LKR)</label>
                                <input type="number" class="form-control" id="originalPrice" placeholder="0.00" oninput="calculateDiscount()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Current Price (LKR)</label>
                                <input type="number" class="form-control" id="currentPrice" placeholder="0.00" oninput="calculateDiscount()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount %</label>
                                <input type="text" class="form-control" id="discountDisplay" placeholder="0%" readonly style="background-color: #2d3748; color: var(--chp-gold);">
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded bg-dark border border-secondary d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                    <span class="small text">KOKO Installment (Auto-calc)</span>
                                    <span class="fw-bold text-white" id="kokoDisplay">LKR 0.00 x 3</span>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <h5 class="text-white mb-3">Product Images <small class="text-muted fs-6">(Max 4)</small></h5>
                        <div class="mb-4">
                            <!-- Drop Zone -->
                            <div class="drop-zone" id="dropZoneContainer">
                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                <p class="mb-1 text-white">Drag & drop product images here</p>
                                <p class="small text-muted">or click to browse (JPG, PNG, WEBP)</p>
                            </div>
                            <!-- Allow multiple file selection -->
                            <input type="file" hidden id="fileInput" accept="image/*" multiple onchange="handleFileSelect(this)">

                            <!-- Progress Bar -->
                            <div id="uploadProgressBarContainer" class="mt-3 d-none">
                                <div class="progress" style="height: 5px; background-color: var(--sec-blue);">
                                    <div class="progress-bar" role="progressbar" style="width: 0%; background-color: var(--chp-gold);" id="uploadProgressBar"></div>
                                </div>
                                <small class="text-muted mt-1 d-block text-end" id="progressText">Processing...</small>
                            </div>

                            <!-- Image Preview Grid -->
                            <div id="imagePreviewGrid" class="image-preview-grid">
                                <!-- Dynamic Items will be added here -->
                            </div>
                        </div>

                        <!-- Toggles -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" id="stockQty" placeholder="0" min="0" oninput="updateStockStatus()">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Stock Status (Auto)</label>
                                <input type="text" class="form-control" id="stockStatus" value="Out of Stock" readonly 
                                    style="background-color: var(--sec-blue); color: var(--danger-red); font-weight: 600; border: 1px solid var(--border-color);">
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex flex-column gap-3 mt-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="luxurySwitch">
                                        <label class="form-check-label" for="luxurySwitch">Show in "Luxury Collection"</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="choiceSwitch">
                                        <label class="form-check-label" for="choiceSwitch">Show in "People's Choice"</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-3 pt-3 border-top border-secondary">
                            <button type="button" class="btn btn-outline-light px-4" onclick="switchView('dashboard', document.querySelectorAll('.menu-link')[0])">Cancel</button>
                            <button type="button" class="btn btn-gold px-5" id="saveProductBtn" onclick="addProduct()">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View: Inventory -->
        <div id="view-inventory" class="d-none-view">
            <!-- Filter Toolbar -->
            <div class="dashboard-card mb-4 p-3 filter-bar d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2 w-100 w-md-auto">
                    <input type="text" class="form-control" placeholder="Search products...">
                    <select class="form-select w-auto">
                        <option>All Categories</option>
                        <option>Wristwatch</option>
                        <option>Wall Decor</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-gold"><i class="fas fa-filter"></i> Filter</button>
                    <button class="btn btn-gold" onclick="switchView('add-product', document.querySelectorAll('.menu-link')[1])"><i class="fas fa-plus"></i> Add Product</button>
                </div>
            </div>

            <div class="custom-table-container">
                <div class="table-responsive">
                    <table class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=50&q=80" class="product-thumb">
                                        <span class="text-white">Titan Quartz Blue</span>
                                    </div>
                                </td>
                                <td>Wristwatch</td>
                                <td>LKR 12,000</td>
                                <td>45</td>
                                <td><span class="badge status-instock">In Stock</span></td>
                                <td>
                                    <button class="btn-icon-action me-1"><i class="fas fa-edit"></i></button>
                                    <button class="btn-icon-action text-danger border-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <!-- More rows mock -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=50&q=80" class="product-thumb">
                                        <span class="text-white">Titan Black Dial</span>
                                    </div>
                                </td>
                                <td>Wristwatch</td>
                                <td>LKR 18,000</td>
                                <td>0</td>
                                <td><span class="badge status-outstock">Out of Stock</span></td>
                                <td>
                                    <button class="btn-icon-action me-1"><i class="fas fa-edit"></i></button>
                                    <button class="btn-icon-action text-danger border-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-3 d-flex justify-content-end border-top border-secondary">
                    <nav>
                        <ul class="pagination pagination-sm m-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- View: Orders -->
        <div id="view-orders" class="d-none-view">
            <!-- Order Status Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="dashboard-card p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon bg-icon-blue mb-0" style="width:40px; height:40px; font-size:1rem;"><i class="fas fa-clipboard-list"></i></div>
                        <div>
                            <h5 class="m-0 text-white">12</h5>
                            <small class="text">Pending</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="dashboard-card p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon bg-icon-gold mb-0" style="width:40px; height:40px; font-size:1rem;"><i class="fas fa-box"></i></div>
                        <div>
                            <h5 class="m-0 text-white">5</h5>
                            <small class="text">Processing</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="dashboard-card p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon bg-icon-green mb-0" style="width:40px; height:40px; font-size:1rem;"><i class="fas fa-truck"></i></div>
                        <div>
                            <h5 class="m-0 text-white">48</h5>
                            <small class="text">Shipped</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="dashboard-card p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon bg-icon-orange mb-0" style="width:40px; height:40px; font-size:1rem;"><i class="fas fa-undo"></i></div>
                        <div>
                            <h5 class="m-0 text-white">2</h5>
                            <small class="text">Returns</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="custom-table-container">
                <div class="table-responsive">
                    <table class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-gold fw-bold">#ORD-2451</td>
                                <td>Oct 24, 2025</td>
                                <td>Kamal Perera</td>
                                <td>LKR 12,000</td>
                                <td><span class="badge status-paid">Paid</span></td>
                                <td><span class="badge status-pending">Pending</span></td>
                                <td><button class="btn btn-sm btn-outline-light">View</button></td>
                            </tr>
                            <tr>
                                <td class="text-gold fw-bold">#ORD-2450</td>
                                <td>Oct 23, 2025</td>
                                <td>Nimali Silva</td>
                                <td>LKR 36,500</td>
                                <td><span class="badge status-unpaid">COD</span></td>
                                <td><span class="badge status-shipped">Shipped</span></td>
                                <td><button class="btn btn-sm btn-outline-light">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- View: Customers -->
        <div id="view-customers" class="d-none-view">
            <!-- Search Bar -->
            <div class="dashboard-card mb-4 p-3 d-flex justify-content-between align-items-center">
                <input type="text" class="form-control w-100 w-md-50" placeholder="Search customers by name or email...">
                <button class="btn btn-outline-gold ms-3"><i class="fas fa-file-export"></i> Export</button>
            </div>

            <div class="custom-table-container">
                <div class="table-responsive">
                    <table class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Orders</th>
                                <th>Total Spent</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=50&q=80" class="customer-avatar">
                                        <span class="text-white">Kamal Perera</span>
                                    </div>
                                </td>
                                <td>kamal@gmail.com</td>
                                <td>5</td>
                                <td>LKR 45,000</td>
                                <td><span class="badge status-instock">Active</span></td>
                                <td><button class="btn-icon-action"><i class="fas fa-ellipsis-v"></i></button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=50&q=80" class="customer-avatar">
                                        <span class="text-white">Nimali Silva</span>
                                    </div>
                                </td>
                                <td>nimali@yahoo.com</td>
                                <td>2</td>
                                <td>LKR 36,500</td>
                                <td><span class="badge status-instock">Active</span></td>
                                <td><button class="btn-icon-action"><i class="fas fa-ellipsis-v"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1100;">
        <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i id="toastIcon" class="fas fa-check-circle fa-lg"></i>
                    <div id="toastMessage" class="fw-semibold">Hello, world!</div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Toast Notification Function -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--sec-blue); border: 1px solid var(--border-color);">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-white" id="deleteConfirmModalLabel"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-muted pt-3">
                    Are you sure you want to delete <strong id="deleteProductName" class="text-white">this product</strong>? This action cannot be undone.
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global array to store selected files
        let selectedFiles = [];

        // Sidebar Toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('active');
        }

        // View Switcher (SPA feel)
        function switchView(viewId, linkElement) {
            // Hide all views
            document.querySelectorAll('[id^="view-"]').forEach(el => el.classList.add('d-none-view'));
            
            // Show selected view
            document.getElementById('view-' + viewId).classList.remove('d-none-view');
            
            // Update Title
            const titles = {
                'dashboard': 'Overview',
                'add-product': 'Add New Product',
                'inventory': 'Product Inventory',
                'orders': 'Customer Orders',
                'customers': 'Customer Management'
            };
            document.getElementById('pageTitle').innerText = titles[viewId] || 'Dashboard';

            // Update Sidebar Active State
            if(linkElement) {
                document.querySelectorAll('.menu-link').forEach(el => el.classList.remove('active'));
                linkElement.classList.add('active');
            }

            // Close sidebar on mobile after selection
            if(window.innerWidth < 992) {
                toggleSidebar();
            }
        }

        // Product Data Retrieval Logic
        let allProducts = [];

        // Fetch data when the page loads (Make sure you delete the duplicate call at the bottom of your script!)
        document.addEventListener('DOMContentLoaded', () => {
            getProductsData();
        });

        async function getProductsData() {
            const url = 'actions/products-data.php';

            try {
                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                
                // Safely extract and reverse the array without mutating original data
                let parsedData = Array.isArray(data) ? [...data] : Object.values(data);
                allProducts = parsedData.reverse();

                renderRecentProducts(allProducts);

            } catch (error) {
                console.error('Failed to fetch products:', error);
                const tbody = document.getElementById('recentProductsTableBody');
                if (tbody) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-danger py-4">Failed to load products. Please try again later.</td>
                        </tr>
                    `;
                }
            }
        }

        // Global variables to store the product being deleted
        let productIdToDelete = null;
        let deleteModalInstance = null;

        function deleteProduct(id, name) {
            productIdToDelete = id;
            
            // Set the product name in the modal text
            document.getElementById('deleteProductName').innerText = name || "this product";
            
            // Initialize and show the modal
            if (!deleteModalInstance) {
                deleteModalInstance = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            }
            deleteModalInstance.show();
        }

        // Attach event listener to the confirm button inside the modal
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!productIdToDelete) return;

            // Change button state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            btn.disabled = true;

            const form = new FormData();
            form.append("id", productIdToDelete);

            fetch("actions/delete-product.php", {
                method: "POST",
                body: form
            })
            .then(response => response.text())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                deleteModalInstance.hide(); // Hide modal

                if (data.trim() === "success") {
                    showNotification("Product deleted successfully!", "success");
                    // Refresh the table data!
                    getProductsData(); 
                } else {
                    showNotification("Error: " + data, "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalText;
                btn.disabled = false;
                deleteModalInstance.hide();
                showNotification("Connection error. Check console.", "error");
            });
        });

        // Render recent products into the table
        function renderRecentProducts(products) {
            const tbody = document.getElementById('recentProductsTableBody');
            if (!tbody) return;

            // Clear existing rows (removes the loading spinner)
            tbody.innerHTML = ''; 

            if (!products || products.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fa-solid fa-box-open mb-3" style="font-size: 32px; color: #555;"></i>
                            <h5>No products found</h5>
                        </td>
                    </tr>
                `;
                return;
            }

            // Slice the array to only show the 5 most recent products on the overview dashboard
            const recentProducts = products.slice(0, 5);

            recentProducts.forEach(product => {
                // Safely handle missing nested pricing data to prevent JS crashes
                const currentPrice = product.pricing?.current_price || 0;
                
                const formattedPrice = new Intl.NumberFormat('en-LK', {
                    style: 'currency',
                    currency: 'LKR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(currentPrice);

                // Safely handle inventory status
                let badgeClass = '';
                const status = product.inventory?.stock_status || 'Unknown';
                
                if (status === 'In Stock') {
                    badgeClass = 'bg-success text-white'; 
                } else if (status === 'Low Stock') {
                    badgeClass = 'bg-warning text-dark';  
                } else {
                    badgeClass = 'bg-danger text-white';  
                }

                // Handle image path safely
                const imgPath = product.primary_thumbnail ? `../${product.primary_thumbnail}` : '../assets/images/products/default.png';
                
                // Safely handle brand name
                const brandName = product.brand?.name || 'N/A';

                // Added "this.onerror=null;" to prevent infinite image loading loops if the default image is also missing
                const trHtml = `
                    <tr>
                        <td>
                            <img src="${imgPath}" 
                                 class="product-thumb" 
                                 alt="${product.name}" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"
                                 onerror="this.onerror=null; this.src='../assets/images/products/default.png'">
                        </td>
                        <td>${product.name}</td>
                        <td>${brandName}</td>
                        <td>${formattedPrice}</td>
                        <td><span class="badge ${badgeClass}">${status}</span></td>
                        <td>
                            <button class="btn-icon-action me-1 bg-transparent border-0 text-white" onclick="editProduct(${product.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon-action bg-transparent border-0 text-danger" onclick="deleteProduct(${product.id}, '${product.name.replace(/'/g, "\\'")}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                
                tbody.insertAdjacentHTML('beforeend', trHtml);
            });
        }
        // Product Data Retrieval Logic End
    

        // Pricing Logic
        function calculateDiscount() {
            const original = parseFloat(document.getElementById('originalPrice').value) || 0;
            const current = parseFloat(document.getElementById('currentPrice').value) || 0;
            
            // Calc Discount
            if(original > 0 && current > 0 && original > current) {
                const discount = ((original - current) / original) * 100;
                document.getElementById('discountDisplay').value = Math.round(discount) + '%';
            } else {
                document.getElementById('discountDisplay').value = '0%';
            }

            // Calc KOKO (Current / 3)
            if(current > 0) {
                const installment = (current / 3).toFixed(2);
                document.getElementById('kokoDisplay').innerText = `LKR ${installment} x 3`;
            } else {
                document.getElementById('kokoDisplay').innerText = 'LKR 0.00 x 3';
            }
        }
        
        // File Upload Trigger
        const dropZone = document.querySelector('.drop-zone');
        const fileInput = document.getElementById('fileInput');

        if(dropZone) {
            dropZone.addEventListener('click', () => fileInput.click());
            
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.style.borderColor = 'var(--chp-gold)';
                dropZone.style.backgroundColor = 'rgba(212, 175, 55, 0.05)';
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.style.borderColor = 'var(--border-color)';
                dropZone.style.backgroundColor = 'rgba(255,255,255,0.02)';
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.style.borderColor = 'var(--border-color)';
                dropZone.style.backgroundColor = 'rgba(255,255,255,0.02)';
                
                if (e.dataTransfer.files.length) {
                    handleFiles(e.dataTransfer.files);
                }
            });
        }

        // Handle Input Change
        function handleFileSelect(input) {
            if (input.files && input.files.length > 0) {
                handleFiles(input.files);
            }
        }

        // Process Files
        function handleFiles(files) {
            const progressContainer = document.getElementById('uploadProgressBarContainer');
            const progressBar = document.getElementById('uploadProgressBar');
            const progressText = document.getElementById('progressText');
            
            // Limit total files to 4
            if (selectedFiles.length + files.length > 4) {
                showNotification("Maximum 4 images allowed.", "error");
                return;
            }

            progressContainer.classList.remove('d-none');
            let processedCount = 0;

            Array.from(files).forEach(file => {
                // Basic type validation
                if(!file.type.startsWith('image/')){
                    showNotification("Only image files are allowed.", "error");
                    return;
                }

                selectedFiles.push(file);
                
                // Simulate read progress
                const reader = new FileReader();
                reader.onload = function(e) {
                    renderPreview(e.target.result, selectedFiles.length - 1); // Pass index
                    
                    processedCount++;
                    const progress = (processedCount / files.length) * 100;
                    progressBar.style.width = progress + '%';
                    progressText.innerText = Math.round(progress) + '%';

                    if(processedCount === files.length) {
                        setTimeout(() => {
                            progressContainer.classList.add('d-none');
                            progressBar.style.width = '0%';
                        }, 500);
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        // Render Preview
        function renderPreview(src, index) {
            const grid = document.getElementById('imagePreviewGrid');
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.id = `preview-${index}`;
            div.innerHTML = `
                <img src="${src}" alt="Product Image">
                <button type="button" class="remove-btn" onclick="removeImage(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            grid.appendChild(div);
        }

        // Remove Image
        function removeImage(indexToRemove) {
            // Remove from array
            selectedFiles.splice(indexToRemove, 1);
            
            // Clear Grid and Re-render all
            const grid = document.getElementById('imagePreviewGrid');
            grid.innerHTML = '';
            
            selectedFiles.forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    renderPreview(e.target.result, idx);
                };
                reader.readAsDataURL(file);
            });
            
            // Clear the actual input value so change event fires if same file selected again
            document.getElementById('fileInput').value = '';
        }

        // --- UPDATED: Auto-update Stock Status based on Quantity ---
        function updateStockStatus() {
            const qtyInput = document.getElementById('stockQty');
            const statusInput = document.getElementById('stockStatus');
            const qty = parseInt(qtyInput.value) || 0; // Default to 0 if empty

            if (qty > 0 && qty < 10) {
                statusInput.value = "Low Stock";
                statusInput.style.color = "var(--warning-orange)"; // Yellow text
            }else if (qty > 9) {
                statusInput.value = "In Stock";
                statusInput.style.color = "var(--success-green)"; // Green text
            } else {
                statusInput.value = "Out of Stock";
                statusInput.style.color = "var(--danger-red)"; // Red text
            }
        }

        // --- Notification Helper ---
        function showNotification(message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            const toastBody = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            
            // Set Message
            toastBody.textContent = message;

            // Reset Classes
            toastEl.className = 'toast align-items-center border-0'; // Base classes
            
            // Apply Type Styling
            if (type === 'success') {
                toastEl.classList.add('toast-success');
                toastIcon.className = 'fas fa-check-circle fa-lg';
            } else if (type === 'error') {
                toastEl.classList.add('toast-error');
                toastIcon.className = 'fas fa-exclamation-circle fa-lg';
            }

            // Initialize and Show Bootstrap Toast
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 }); // 4 seconds
            toast.show();
        }

        // Helper function to select dropdown options by text (since your JSON returns text for category)
        function setSelectByText(selectId, text) {
            const select = document.getElementById(selectId);
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].text === text) {
                    select.selectedIndex = i;
                    return;
                }
            }
        }

        // --- EDIT PRODUCT LOGIC ---
        function editProduct(id) {
            // Find the product data from our globally fetched array
            const product = allProducts.find(p => p.id === id);
            if (!product) return;

            // 1. Set Hidden ID and Titles
            document.getElementById('editProductId').value = product.id;
            document.getElementById('formTitle').innerText = 'Edit Product: ' + product.name;
            document.getElementById('saveProductBtn').innerText = 'Update Product';

            // 2. Populate text inputs
            document.getElementById('productName').value = product.name;
            document.getElementById('productDesc').value = product.description;
            document.getElementById('originalPrice').value = product.pricing.original_price;
            document.getElementById('currentPrice').value = product.pricing.current_price;
            document.getElementById('stockQty').value = product.inventory.stock_count;

            // 3. Populate Selects (Dropdowns)
            document.getElementById('productBrand').value = product.brand.id;
            setSelectByText('productCategory', product.sub_category);

            // 4. Populate Toggles
            document.getElementById('luxurySwitch').checked = product.is_luxury;
            document.getElementById('choiceSwitch').checked = product.is_peoples_choice;

            // 5. Trigger automatic calculations
            calculateDiscount();
            updateStockStatus();

            // 6. Switch to the form view
            switchView('add-product');
            
            showNotification("Editing mode enabled. Uploading new images will replace old ones.", "success");
        }

        // --- RESET FORM LOGIC ---
        function resetProductForm() {
            document.getElementById('editProductId').value = '';
            document.getElementById('formTitle').innerText = 'Add New Product';
            document.getElementById('saveProductBtn').innerText = 'Save Product';
            
            document.getElementById('productName').value = '';
            document.getElementById('productDesc').value = '';
            document.getElementById('originalPrice').value = '';
            document.getElementById('currentPrice').value = '';
            document.getElementById('stockQty').value = '';
            document.getElementById('productBrand').value = '0';
            document.getElementById('productCategory').value = '0';
            document.getElementById('luxurySwitch').checked = false;
            document.getElementById('choiceSwitch').checked = false;
            
            selectedFiles = [];
            document.getElementById('imagePreviewGrid').innerHTML = '';
            document.getElementById('fileInput').value = '';
            
            calculateDiscount();
            updateStockStatus();
        }

        // --- UPDATED SAVE/UPDATE LOGIC ---
        // (Replace your existing addProduct function with this one)
        function addProduct() {
            const editId = document.getElementById('editProductId').value;
            const title = document.getElementById('productName');
            const brand = document.getElementById('productBrand');
            const category = document.getElementById('productCategory');
            const desc = document.getElementById('productDesc');
            const oprice = document.getElementById('originalPrice');
            const cprice = document.getElementById('currentPrice');
            const qty = document.getElementById('stockQty'); 
            const luxury = document.getElementById('luxurySwitch');
            const choice = document.getElementById('choiceSwitch');
            const saveBtn = document.getElementById('saveProductBtn');

            // Validation
            if(!title.value.trim()) { showNotification("Error: Product name is required.", "error"); title.focus(); return; }
            if(brand.value === "0" || brand.value === "") { showNotification("Error: Please select a Brand.", "error"); brand.focus(); return; }
            if(category.value === "0" || category.value === "") { showNotification("Error: Please select a Category.", "error"); category.focus(); return; }
            if(!cprice.value || cprice.value <= 0) { showNotification("Error: Please enter a valid Current Price.", "error"); cprice.focus(); return; }
            if(qty.value === "" || parseInt(qty.value) < 0) { showNotification("Error: Please enter a valid Stock Quantity.", "error"); qty.focus(); return; }
            
            // Require images ONLY if we are adding a NEW product
            if (!editId && selectedFiles.length === 0) {
                showNotification("Please upload at least one image.", "error");
                return;
            }

            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            const form = new FormData();
            // If editId has a value, send it so the PHP knows to UPDATE instead of INSERT
            if (editId) form.append("id", editId); 
            
            form.append("title", title.value);
            form.append("brand", brand.value);
            form.append("category", category.value);
            form.append("desc", desc.value);
            form.append("oprice", oprice.value);
            form.append("cprice", cprice.value);
            form.append("qty", qty.value);
            form.append("luxury", luxury.checked ? 'true' : 'false');
            form.append("choice", choice.checked ? 'true' : 'false');
            
            selectedFiles.forEach((file) => {
                form.append("images[]", file);
            });

            // Determine which PHP file to hit
            const targetUrl = editId ? "actions/edit-product.php" : "actions/create-product.php";

            fetch(targetUrl, {
                method: "POST",
                body: form
            })
            .then(response => response.text())
            .then(data => {
                saveBtn.innerHTML = editId ? 'Update Product' : 'Save Product';
                saveBtn.disabled = false;

                if (data.trim() === "success") {
                    showNotification(editId ? "Product updated successfully!" : "Product saved successfully!", "success");
                    resetProductForm();
                    getProductsData(); // Refresh the table data
                    setTimeout(() => { switchView('inventory'); }, 1000); 
                } else {
                    showNotification("Error: " + data, "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                saveBtn.innerHTML = editId ? 'Update Product' : 'Save Product';
                saveBtn.disabled = false;
                showNotification("Connection error. Check console.", "error");
            });
        }
    </script>
</body>
</html>