<?php
    session_start();
    require "../includes/connection.php"; 

    // CRITICAL: Connect to DB before prepare()
    if (empty(Database::$connection)) {
        Database::setUpConnection();
    }

    // 1. Basic Auth Check
    if(!isset($_SESSION["u"]) || !isset($_SESSION["session_token"])){
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION["u"]["id"];
    $local_token = $_SESSION["session_token"];

    // 2. CONCURRENT LOGIN CHECK
    $stmt = Database::$connection->prepare("SELECT active_session_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $rs = $stmt->get_result();

    if($rs->num_rows == 1) {
        $db_token = $rs->fetch_assoc()['active_session_id'];
        
        if($local_token !== $db_token) {
            session_unset();
            session_destroy();
            header("Location: login.php?err=concurrent");
            exit();
        }
    }

    // 3. INACTIVITY TIMEOUT
    $timeout_duration = 1800; 

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
        $stmt = Database::$connection->prepare("UPDATE users SET active_session_id = NULL, last_active_time = 0 WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        session_unset();     
        session_destroy();   
        header("Location: login.php?err=timeout"); 
        exit();
    }

    // Update last activity
    $current_time = time();
    $_SESSION['last_activity'] = $current_time;

    $stmt = Database::$connection->prepare("UPDATE users SET last_active_time = ? WHERE id = ?");
    $stmt->bind_param("ii", $current_time, $user_id);
    $stmt->execute();

    $user_data = $_SESSION["u"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --prm-blue: #0A111F;
            --sec-blue: #151f32;
            --chp-gold: #D4AF37;
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
            z-index: 1050;
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
            overflow-y: auto;
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
            background-color: var(--sec-blue);
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
            white-space: nowrap;
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
            color: var(--text-muted);
            opacity: 1;
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

        /* Search Dropdown */
        .search-result-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background-color: rgba(212, 175, 55, 0.1);
        }

        .search-result-item img {
            width: 35px;
            height: 35px;
            object-fit: contain;
            background: white;
            border-radius: 4px;
            padding: 2px;
        }

        .search-no-results {
            padding: 2rem 1rem;
            text-align: center;
            color: var(--text-muted);
        }

        .search-no-results i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
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

        /* Image Preview Grid */
        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        
        .preview-item {
            position: relative;
            width: 100%;
            padding-top: 100%;
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

        /* Toast */
        .toast {
            background-color: var(--sec-blue);
            color: var(--text-light);
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            min-width: 300px;
        }
        .toast.toast-success { border-left: 4px solid var(--success-green); }
        .toast.toast-success i { color: var(--success-green); }
        .toast.toast-error { border-left: 4px solid var(--danger-red); }
        .toast.toast-error i { color: var(--danger-red); }

        /* --- Custom Notification Dropdown --- */
        .notification-dropdown {
            width: 350px; /* Slightly wider for better readability */
            max-width: 95vw; /* CRITICAL FOR MOBILE: Prevents it from bleeding off the screen on tiny phones */
            max-height: 400px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* CRITICAL FIX: Overrides Bootstrap's default behavior so text drops to the next line instead of cutting off */
        .notification-dropdown .dropdown-item {
            white-space: normal; 
            line-height: 1.4;
        }

        /* Webkit Styling: Makes it look sleek like a native mobile/Mac app */
        .notification-dropdown::-webkit-scrollbar {
            width: 5px; 
        }
        .notification-dropdown::-webkit-scrollbar-track {
            background: transparent; 
        }
        .notification-dropdown::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.46); 
            border-radius: 10px;
        }
        .notification-dropdown::-webkit-scrollbar-thumb:hover {
            background-color: rgba(212, 175, 55, 0.5); 
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .mobile-toggle { display: block; }
        }

        @media (max-width: 576px) {
            .main-content { padding: 1rem; }
            .top-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            .top-header .d-flex:last-child {
                justify-content: space-between;
                width: 100%;
            }
            .dashboard-card { padding: 1.25rem; }
            .btn-gold, .btn-outline-gold { padding: 8px 16px; font-size: 0.9rem; }
            .filter-bar { flex-direction: column; gap: 10px; }
            .filter-bar > * { width: 100%; }
        }
    </style>
</head>
<body>

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
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background-color: var(--chp-gold); color: #000; font-weight: 700; font-size: 1.2rem;">
                    <?php echo strtoupper(substr($user_data["fname"], 0, 1)); ?>
                </div>
                <div>
                    <p class="m-0 small fw-bold text-white"><?php echo $user_data["fname"]; ?></p>
                    <p class="m-0 small text-muted">Admin</p>
                </div>
                <a href="#" onclick="logoutAdmin()" class="ms-auto text-muted hover-gold" title="Sign Out">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
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
            <div class="d-flex gap-3 align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-gold rounded-circle p-0 d-flex align-items-center justify-content-center position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-dark rounded-circle d-none" id="notificationBadge">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow p-0 mt-2 notification-dropdown" style="z-index: 1060;">
                        <div class="p-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
                            <h6 class="m-0 text-white">Action Required</h6>
                            <span class="badge bg-danger rounded-pill" id="notificationCount">0</span>
                        </div>
                        <div id="notificationItems">
                            <div class="p-4 text-center text-muted small">No pending orders</div>
                        </div>
                    </div>
                </div>

                <a href="../index.php" target="_blank" class="btn btn-gold text-decoration-none">
                    Visit Store <i class="fas fa-external-link-alt ms-2 d-none d-sm-inline"></i>
                </a>
            </div>
        </header>

        <!-- View: Dashboard Overview -->
        <div id="view-dashboard">
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="stat-icon bg-icon-gold"><i class="fas fa-coins"></i></div>
                        <h3 id="stat-total-sales" class="h2 fw-bold text-white mb-1">LKR 0</h3>
                        <p class="text-muted m-0">Total Sales (This Month)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="stat-icon bg-icon-blue"><i class="fas fa-box"></i></div>
                        <h3 id="prod-num" class="h2 fw-bold text-white mb-1">0</h3>
                        <p class="text-muted m-0">Total Products</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="stat-icon bg-icon-green"><i class="fas fa-check-circle"></i></div>
                        <h3 id="stat-new-orders" class="h2 fw-bold text-white mb-1">0</h3>
                        <p class="text-muted m-0">New Orders (This Month)</p>
                    </div>
                </div>
            </div>

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
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="recentProductsTableBody">
                            <tr>
                                <td colspan="7" class="text-center py-4">
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
                        <h4 id="formTitle" class="text-white mb-4 border-bottom border-secondary pb-3" style="color: var(--chp-gold) !important;">Add New Product</h4>
                        
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
                                    <option value="10">Not specified</option>
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
                            <h5 class="text-white mb-3 mt-4 w-100 border-top border-secondary pt-4">Marketing & Specifications</h5>
                            <div class="col-12">
                                <label class="form-label">Romance Hook (2-Sentence Marketing Copy)</label>
                                <textarea class="form-control" id="romanceCopy" rows="2" placeholder="Sell the lifestyle and aesthetics..."></textarea>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Diameter (mm)</label>
                                <input type="number" step="0.1" class="form-control" id="specDiameter" placeholder="42">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Thickness (mm)</label>
                                <input type="number" step="0.1" class="form-control" id="specThickness" placeholder="10">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Materials</label>
                                <input type="text" class="form-control" id="specMaterials" placeholder="e.g. Stainless Steel">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Glass/Crystal</label>
                                <input type="text" class="form-control" id="specGlass" placeholder="e.g. Sapphire Crystal">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Water Res. (ATM)</label>
                                <input type="number" class="form-control" id="specWaterATM" placeholder="e.g. 3 (for 30m)">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Movement</label>
                                <input type="text" class="form-control" id="specMovement" placeholder="e.g. Japanese Quartz">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Clasp Type</label>
                                <input type="text" class="form-control" id="specClasp" placeholder="e.g. Deployment Buckle">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Warranty Period</label>
                                <input type="number" class="form-control" id="specWarranty" placeholder="e.g. 2 Months">
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
                                    <span class="small text-muted">KOKO Installment (Auto-calc)</span>
                                    <span class="fw-bold text-white" id="kokoDisplay">LKR 0.00 x 3</span>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <h5 class="text-white mb-3">Product Images <small class="text-muted fs-6">(Max 4)</small></h5>
                        <div class="mb-4">
                            <div class="drop-zone" id="dropZoneContainer">
                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                <p class="mb-1 text-white">Drag & drop product images here</p>
                                <p class="small text-muted">or click to browse (JPG, PNG, WEBP)</p>
                            </div>
                            <input type="file" hidden id="fileInput" accept="image/*" multiple onchange="handleFileSelect(this)">

                            <div id="uploadProgressBarContainer" class="mt-3 d-none">
                                <div class="progress" style="height: 5px; background-color: var(--sec-blue);">
                                    <div class="progress-bar" role="progressbar" style="width: 0%; background-color: var(--chp-gold);" id="uploadProgressBar"></div>
                                </div>
                                <small class="text-muted mt-1 d-block text-end" id="progressText">Processing...</small>
                            </div>

                            <div id="imagePreviewGrid" class="image-preview-grid"></div>
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
                            <button type="button" class="btn btn-outline-light px-4" onclick="resetFormAndGoToDashboard()">Cancel</button>
                            <button type="button" class="btn btn-gold px-5" id="saveProductBtn" onclick="addProduct()">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View: Inventory -->
        <div id="view-inventory" class="d-none-view">
            <div class="dashboard-card mb-4 p-3 filter-bar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex flex-column flex-md-row gap-2 w-100 w-md-auto position-relative">
                    <div class="position-relative w-100 w-md-auto" style="min-width: 250px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search products..." oninput="handleSearch(this.value)" autocomplete="off">
                        
                        <div id="searchDropdown" class="position-absolute w-100 mt-1 d-none" style="z-index: 1000; max-height: 300px; overflow-y: auto; background-color: var(--sec-blue); border: 1px solid var(--border-color); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);"></div>
                    </div>
                    
                    <select class="form-select w-auto" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="Men's Watches">Men's Watches</option>
                        <option value="Women's Watches">Women's Watches</option>
                        <option value="Smart Watches">Smart Watches</option>
                        <option value="Luxury Collection">Luxury Collection</option>
                        <option value="Wall Clocks">Wall Clocks</option>
                        <option value="Photo Frames">Photo Frames</option>
                        <option value="Wall Art">Wall Art</option>
                        <option value="Mirrors">Mirrors</option>
                    </select>
                </div>
                <div class="d-flex gap-2 w-100 w-md-auto">
                    <button class="btn btn-outline-gold flex-fill flex-md-grow-0" onclick="applyFilters()"><i class="fas fa-filter me-2"></i>Filter</button>
                    <button class="btn btn-gold flex-fill flex-md-grow-0" onclick="resetFormAndSwitchToAdd()"><i class="fas fa-plus me-2"></i>Add Product</button>
                </div>
            </div>

            <div class="custom-table-container">
                <div class="table-responsive">
                    <table class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="spinner-border text-gold spinner-border-sm" role="status"></div>
                                    <span class="ms-2 text-muted">Loading inventory...</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div id="paginationContainer" class="p-3 d-none justify-content-between align-items-center border-top border-secondary flex-column flex-md-row gap-3">
                    <div class="text-muted small" id="paginationInfo">Showing 1-7 of 0 products</div>
                    <nav>
                        <ul class="pagination pagination-sm m-0" id="paginationControls"></ul>
                    </nav>
                </div>
            </div>
        </div>

        <div id="view-orders" class="d-none-view">
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="dashboard-card p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon bg-icon-blue mb-0" style="width:40px; height:40px; font-size:1rem;"><i class="fas fa-clipboard-list"></i></div>
                        <div><h5 class="m-0 text-white" id="count-pending">0</h5><small class="text-muted">Pending</small></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="dashboard-card p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon bg-icon-gold mb-0" style="width:40px; height:40px; font-size:1rem;"><i class="fas fa-box"></i></div>
                        <div><h5 class="m-0 text-white" id="count-processing">0</h5><small class="text-muted">Processing</small></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="dashboard-card p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon bg-icon-green mb-0" style="width:40px; height:40px; font-size:1rem;"><i class="fas fa-truck"></i></div>
                        <div><h5 class="m-0 text-white" id="count-shipped">0</h5><small class="text-muted">Shipped</small></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="dashboard-card p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon bg-icon-orange mb-0" style="width:40px; height:40px; font-size:1rem;"><i class="fas fa-check"></i></div>
                        <div><h5 class="m-0 text-white" id="count-delivered">0</h5><small class="text-muted">Delivered</small></div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card mb-4 p-3 d-flex justify-content-between align-items-center">
                <input type="text" id="orderSearch" class="form-control w-100 w-md-50" placeholder="Search by Order ID (e.g. #PWORD1) or Customer Name..." oninput="filterOrders()">
            </div>

            <div class="custom-table-container">
                <div class="table-responsive" style="overflow-y: visible;">
                    <table class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Method</th> <th>Payment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="spinner-border text-gold spinner-border-sm" role="status"></div>
                                    <span class="ms-2 text-muted">Loading orders...</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- View: Customers -->
        <div id="view-customers" class="d-none-view">
            <div class="dashboard-card mb-4 p-3 d-flex justify-content-between align-items-center">
                <input type="text" id="customerSearch" class="form-control w-100 w-md-50" placeholder="Search customers by name or email..." oninput="filterCustomers()">
            </div>

            <div class="custom-table-container">
                <div class="table-responsive">
                    <table class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Total Orders</th>
                                <th>Total Spent</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customersTableBody">
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="spinner-border text-gold spinner-border-sm" role="status"></div>
                                    <span class="ms-2 text-muted">Loading customers...</span>
                                </td>
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
        let selectedFiles = [];
        let allProducts = [];
        let filteredProducts = [];
        let currentPage = 1;
        const itemsPerPage = 7;

        // ========== SIDEBAR & VIEW SWITCHING ==========
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('active');
        }

        function switchView(viewId, linkElement) {
            document.querySelectorAll('[id^="view-"]').forEach(el => el.classList.add('d-none-view'));
            document.getElementById('view-' + viewId).classList.remove('d-none-view');
            
            const titles = {
                'dashboard': 'Overview',
                'add-product': 'Add New Product',
                'inventory': 'Product Inventory',
                'orders': 'Customer Orders',
                'customers': 'Customer Management'
            };
            document.getElementById('pageTitle').innerText = titles[viewId] || 'Dashboard';

            if(linkElement) {
                document.querySelectorAll('.menu-link').forEach(el => el.classList.remove('active'));
                linkElement.classList.add('active');
            }

            if(window.innerWidth < 992) toggleSidebar();
        }

        // ========== DATA FETCHING ==========
        document.addEventListener('DOMContentLoaded', () => {
            getProductsData();
        });

        async function getProductsData() {
            try {
                const response = await fetch('actions/products-data.php');
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                
                const data = await response.json();
                let parsedData = Array.isArray(data) ? [...data] : Object.values(data);
                allProducts = parsedData.reverse();
                filteredProducts = [...allProducts];

                document.getElementById('prod-num').innerText = allProducts.length;
                renderRecentProducts(allProducts);
                renderInventoryProducts(filteredProducts, 1);
            } catch (error) {
                console.error('Failed to fetch products:', error);
                document.getElementById('recentProductsTableBody').innerHTML = `
                    <tr><td colspan="7" class="text-center text-danger py-4">Failed to load products.</td></tr>
                `;
            }
        }

        // ========== RENDER FUNCTIONS ==========
        function renderRecentProducts(products) {
            const tbody = document.getElementById('recentProductsTableBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (!products || products.length === 0) {
                tbody.innerHTML = `
                    <tr><td colspan="7" class="text-center text-muted py-5">
                        <i class="fa-solid fa-box-open mb-3" style="font-size: 32px;"></i>
                        <h5>No products found</h5>
                    </td></tr>
                `;
                return;
            }

            const recentProducts = products.slice(0, 5);

            recentProducts.forEach(product => {
                const currentPrice = product.pricing?.current_price || 0;
                const formattedPrice = new Intl.NumberFormat('en-LK', {
                    style: 'currency', currency: 'LKR', minimumFractionDigits: 0, maximumFractionDigits: 0
                }).format(currentPrice);

                let badgeClass = '';
                const status = product.inventory?.stock_status || 'Unknown';
                if (status === 'In Stock') badgeClass = 'bg-success text-white';
                else if (status === 'Low Stock') badgeClass = 'bg-warning text-dark';
                else badgeClass = 'bg-danger text-white';

                const imgPath = product.primary_thumbnail ? `../${product.primary_thumbnail}` : '../assets/images/products/default.png';
                const brandName = product.brand?.name || 'N/A';

                const trHtml = `
                    <tr>
                        <td><img src="${imgPath}" class="product-thumb" onerror="this.onerror=null; this.src='../assets/images/products/default.png'"></td>
                        <td>${product.name.slice(0, 20)}</td>
                        <td>${brandName}</td>
                        <td>${product.category || 'N/A'}</td>
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

        function renderInventoryProducts(products, page = 1) {
            const tbody = document.getElementById('inventoryTableBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (!products || products.length === 0) {
                tbody.innerHTML = `
                    <tr><td colspan="8" class="text-center text-muted py-5">
                        <i class="fa-solid fa-box-open mb-3" style="font-size: 32px;"></i>
                        <h5>No products found</h5>
                    </td></tr>
                `;
                document.getElementById('paginationContainer').classList.add('d-none');
                return;
            }

            // Pagination Logic
            const totalProducts = products.length;
            const totalPages = Math.ceil(totalProducts / itemsPerPage);
            currentPage = page;

            const startIdx = (currentPage - 1) * itemsPerPage;
            const endIdx = startIdx + itemsPerPage;
            const pageProducts = products.slice(startIdx, endIdx);

            pageProducts.forEach(product => {
                const currentPrice = product.pricing?.current_price || 0;
                const formattedPrice = new Intl.NumberFormat('en-LK', {
                    style: 'currency', currency: 'LKR', minimumFractionDigits: 0, maximumFractionDigits: 0
                }).format(currentPrice);

                let badgeClass = '';
                const status = product.inventory?.stock_status || 'Unknown';
                if (status === 'In Stock') badgeClass = 'bg-success text-white';
                else if (status === 'Low Stock') badgeClass = 'bg-warning text-dark';
                else badgeClass = 'bg-danger text-white';

                const imgPath = product.primary_thumbnail ? `../${product.primary_thumbnail}` : '../assets/images/products/default.png';
                const brandName = product.brand?.name || 'N/A';

                const trHtml = `
                    <tr>
                        <td><img src="${imgPath}" class="product-thumb" onerror="this.onerror=null; this.src='../assets/images/products/default.png'"></td>
                        <td>${product.name.slice(0, 20)}</td>
                        <td>${brandName}</td>
                        <td>${product.sub_category || 'N/A'}</td>
                        <td>${formattedPrice}</td>
                        <td>${product.inventory?.stock_count || 0}</td>
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

            // Show/Hide Pagination
            if (totalProducts <= itemsPerPage) {
                document.getElementById('paginationContainer').classList.add('d-none');
            } else {
                document.getElementById('paginationContainer').classList.remove('d-none');
                document.getElementById('paginationContainer').classList.add('d-flex');
                renderPagination(totalPages, currentPage);
                
                const showing = `Showing ${startIdx + 1}-${Math.min(endIdx, totalProducts)} of ${totalProducts} products`;
                document.getElementById('paginationInfo').textContent = showing;
            }
        }

        function renderPagination(totalPages, currentPage) {
            const controls = document.getElementById('paginationControls');
            controls.innerHTML = '';

            // Previous Button
            const prevClass = currentPage === 1 ? 'disabled' : '';
            controls.innerHTML += `
                <li class="page-item ${prevClass}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">Previous</a>
                </li>
            `;

            // Page Numbers
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = i === currentPage ? 'active' : '';
                controls.innerHTML += `
                    <li class="page-item ${activeClass}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>
                `;
            }

            // Next Button
            const nextClass = currentPage === totalPages ? 'disabled' : '';
            controls.innerHTML += `
                <li class="page-item ${nextClass}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Next</a>
                </li>
            `;
        }

        function changePage(page) {
            const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;
            renderInventoryProducts(filteredProducts, page);
        }

        // ========== SEARCH FUNCTIONALITY ==========
        let searchTimeout;
        function handleSearch(query) {
            clearTimeout(searchTimeout);
            const dropdown = document.getElementById('searchDropdown');

            if (!query.trim()) {
                dropdown.classList.add('d-none');
                return;
            }

            searchTimeout = setTimeout(() => {
                const results = allProducts.filter(p => 
                    p.name.toLowerCase().includes(query.toLowerCase())
                );

                if (results.length === 0) {
                    dropdown.innerHTML = `
                        <div class="search-no-results">
                            <i class="fas fa-search d-block"></i>
                            <p class="mb-0">No matching products</p>
                        </div>
                    `;
                } else {
                    dropdown.innerHTML = results.slice(0, 5).map(product => {
                        const imgPath = product.primary_thumbnail ? `../${product.primary_thumbnail}` : '../assets/images/products/default.png';
                        return `
                            <div class="search-result-item" onclick="selectSearchResult(${product.id}, '${product.name.replace(/'/g, "\\'")}')">
                                <img src="${imgPath}" onerror="this.onerror=null; this.src='../assets/images/products/default.png'">
                                <span class="text-white">${product.name}</span>
                            </div>
                        `;
                    }).join('');
                }

                dropdown.classList.remove('d-none');
            }, 300);
        }

        function selectSearchResult(id, name) {
            document.getElementById('searchInput').value = name;
            document.getElementById('searchDropdown').classList.add('d-none');
            
            filteredProducts = allProducts.filter(p => p.id === id);
            renderInventoryProducts(filteredProducts, 1);
        }

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#searchInput') && !e.target.closest('#searchDropdown')) {
                document.getElementById('searchDropdown').classList.add('d-none');
            }
        });

        // Enter key to search
        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        // ========== FILTER FUNCTIONALITY ==========
        function applyFilters() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;

            filteredProducts = allProducts.filter(product => {
                const matchesSearch = !searchQuery || product.name.toLowerCase().includes(searchQuery);
                const matchesCategory = !categoryFilter || product.sub_category === categoryFilter;
                return matchesSearch && matchesCategory;
            });

            document.getElementById('searchDropdown').classList.add('d-none');
            renderInventoryProducts(filteredProducts, 1);
        }

        // ========== ADD PRODUCT BUTTON ==========
        function resetFormAndSwitchToAdd() {
            resetProductForm();
            switchView('add-product', document.querySelectorAll('.menu-link')[1]);
        }

        function resetFormAndGoToDashboard() {
            resetProductForm();
            switchView('dashboard', document.querySelectorAll('.menu-link')[0]);
        }

        // ========== DELETE PRODUCT ==========
        let productIdToDelete = null;
        let deleteModalInstance = null;

        function deleteProduct(id, name) {
            productIdToDelete = id;
            document.getElementById('deleteProductName').innerText = name.slice(0,19) || "this product";
            
            if (!deleteModalInstance) {
                deleteModalInstance = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            }
            deleteModalInstance.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!productIdToDelete) return;

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
                deleteModalInstance.hide();

                if (data.trim() === "success") {
                    showNotification("Product deleted successfully!", "success");
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
                showNotification("Connection error.", "error");
            });
        });

        // ========== PRICING & STOCK ==========
        function calculateDiscount() {
            const original = parseFloat(document.getElementById('originalPrice').value) || 0;
            const current = parseFloat(document.getElementById('currentPrice').value) || 0;
            
            if(original > 0 && current > 0 && original > current) {
                const discount = ((original - current) / original) * 100;
                document.getElementById('discountDisplay').value = Math.round(discount) + '%';
            } else {
                document.getElementById('discountDisplay').value = '0%';
            }

            if(current > 0) {
                const installment = (current / 3).toFixed(2);
                document.getElementById('kokoDisplay').innerText = `LKR ${installment} x 3`;
            } else {
                document.getElementById('kokoDisplay').innerText = 'LKR 0.00 x 3';
            }
        }

        function updateStockStatus() {
            const qty = parseInt(document.getElementById('stockQty').value) || 0;
            const statusInput = document.getElementById('stockStatus');
            
            if (qty > 0 && qty < 10) {
                statusInput.value = "Low Stock";
                statusInput.style.color = "var(--warning-orange)";
            } else if (qty > 9) {
                statusInput.value = "In Stock";
                statusInput.style.color = "var(--success-green)";
            } else {
                statusInput.value = "Out of Stock";
                statusInput.style.color = "var(--danger-red)";
            }
        }

        // ========== FILE UPLOAD ==========
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

        function handleFileSelect(input) {
            if (input.files && input.files.length > 0) {
                handleFiles(input.files);
            }
        }

        function handleFiles(files) {
            const progressContainer = document.getElementById('uploadProgressBarContainer');
            const progressBar = document.getElementById('uploadProgressBar');
            const progressText = document.getElementById('progressText');
            
            if (selectedFiles.length + files.length > 4) {
                showNotification("Maximum 4 images allowed.", "error");
                return;
            }

            progressContainer.classList.remove('d-none');
            let processedCount = 0;

            Array.from(files).forEach(file => {
                if(!file.type.startsWith('image/')){
                    showNotification("Only image files are allowed.", "error");
                    return;
                }

                selectedFiles.push(file);
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    renderPreview(e.target.result, selectedFiles.length - 1);
                    
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

        function removeImage(indexToRemove) {
            selectedFiles.splice(indexToRemove, 1);
            
            const grid = document.getElementById('imagePreviewGrid');
            grid.innerHTML = '';
            
            selectedFiles.forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    renderPreview(e.target.result, idx);
                };
                reader.readAsDataURL(file);
            });
            
            document.getElementById('fileInput').value = '';
        }

        // ========== EDIT PRODUCT ==========
        function setSelectByText(selectId, text) {
            const select = document.getElementById(selectId);
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].text === text) {
                    select.selectedIndex = i;
                    return;
                }
            }
        }

        function editProduct(id) {
            const product = allProducts.find(p => p.id === id);
            if (!product) return;

            document.getElementById('editProductId').value = product.id;
            document.getElementById('formTitle').innerText = 'Edit Product: ' + product.name.slice(0,15);
            document.getElementById('saveProductBtn').innerText = 'Update Product';

            document.getElementById('productName').value = product.name;
            document.getElementById('productDesc').value = product.description;

            document.getElementById('romanceCopy').value = product.romance_copy || '';
            document.getElementById('specDiameter').value = product.specs?.case_diameter_mm || '';
            document.getElementById('specThickness').value = product.specs?.case_thickness_mm || '';
            document.getElementById('specMaterials').value = product.specs?.materials || '';
            document.getElementById('specGlass').value = product.specs?.glass || '';
            document.getElementById('specWaterATM').value = product.specs?.water_resistance_atm || '';
            document.getElementById('specMovement').value = product.specs?.movement || '';
            document.getElementById('specClasp').value = product.specs?.clasp || '';
            document.getElementById('specWarranty').value = product.specs?.warranty || '';

            document.getElementById('originalPrice').value = product.pricing.original_price;
            document.getElementById('currentPrice').value = product.pricing.current_price;
            document.getElementById('stockQty').value = product.inventory.stock_count;

            document.getElementById('productBrand').value = product.brand.id;
            setSelectByText('productCategory', product.sub_category);

            document.getElementById('luxurySwitch').checked = product.is_luxury;
            document.getElementById('choiceSwitch').checked = product.is_peoples_choice;

            calculateDiscount();
            updateStockStatus();

            switchView('add-product');
            
            showNotification("Editing mode enabled.", "success");
        }

        // ========== RESET FORM ==========
        function resetProductForm() {
            document.getElementById('editProductId').value = '';
            document.getElementById('formTitle').innerText = 'Add New Product';
            document.getElementById('saveProductBtn').innerText = 'Save Product';
            
            document.getElementById('productName').value = '';
            document.getElementById('productDesc').value = '';
            
            document.getElementById('romanceCopy').value = '';
            document.getElementById('specDiameter').value = '';
            document.getElementById('specThickness').value = '';
            document.getElementById('specMaterials').value = '';
            document.getElementById('specGlass').value = '';
            document.getElementById('specWaterATM').value = '';
            document.getElementById('specMovement').value = '';
            document.getElementById('specClasp').value = '';
            document.getElementById('specWarranty').value = '';

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

        // ========== SAVE/UPDATE PRODUCT ==========
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

            if(!title.value.trim()) { showNotification("Error: Product name is required.", "error"); title.focus(); return; }
            if(brand.value === "0") { showNotification("Error: Please select a Brand.", "error"); brand.focus(); return; }
            if(category.value === "0") { showNotification("Error: Please select a Category.", "error"); category.focus(); return; }
            if(!cprice.value || cprice.value <= 0) { showNotification("Error: Please enter a valid Current Price.", "error"); cprice.focus(); return; }
            if(qty.value === "" || parseInt(qty.value) < 0) { showNotification("Error: Please enter a valid Stock Quantity.", "error"); qty.focus(); return; }
            
            if (!editId && selectedFiles.length === 0) {
                showNotification("Please upload at least one image.", "error");
                return;
            }

            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            const form = new FormData();
            if (editId) form.append("id", editId);
            
            form.append("title", title.value);
            form.append("brand", brand.value);
            form.append("category", category.value);
            form.append("desc", desc.value);
            form.append("romance_copy", document.getElementById('romanceCopy').value);
            form.append("diameter", document.getElementById('specDiameter').value);
            form.append("thickness", document.getElementById('specThickness').value);
            form.append("materials", document.getElementById('specMaterials').value);
            form.append("glass", document.getElementById('specGlass').value);
            form.append("water_atm", document.getElementById('specWaterATM').value);
            form.append("movement", document.getElementById('specMovement').value);
            form.append("clasp", document.getElementById('specClasp').value);
            form.append("warranty", document.getElementById('specWarranty').value);
            form.append("oprice", oprice.value);
            form.append("cprice", cprice.value);
            form.append("qty", qty.value);
            form.append("luxury", luxury.checked ? 'true' : 'false');
            form.append("choice", choice.checked ? 'true' : 'false');
            
            selectedFiles.forEach((file) => {
                form.append("images[]", file);
            });

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
                    getProductsData();
                    
                    setTimeout(() => {
                        switchView('inventory', document.querySelectorAll('.menu-link')[2]);
                    }, 1000);
                } else {
                    showNotification("Error: " + data, "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                saveBtn.innerHTML = editId ? 'Update Product' : 'Save Product';
                saveBtn.disabled = false;
                showNotification("Connection error.", "error");
            });
        }

        // ========== NOTIFICATIONS ==========
        function showNotification(message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            const toastBody = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            
            toastBody.textContent = message;
            toastEl.className = 'toast align-items-center border-0';
            
            if (type === 'success') {
                toastEl.classList.add('toast-success');
                toastIcon.className = 'fas fa-check-circle fa-lg';
            } else {
                toastEl.classList.add('toast-error');
                toastIcon.className = 'fas fa-exclamation-circle fa-lg';
            }

            new bootstrap.Toast(toastEl, { delay: 4000 }).show();
        }
        // ========== LOGOUT FUNCTION ==========
        function logoutAdmin() {
            // Optional: Show a quick loading state if you want
            showNotification("Logging out...", "success");

            fetch('actions/logout.php')
            .then(response => response.text())
            .then(data => {
                if(data.trim() === "success") {
                    // Redirect to login page immediately
                    window.location.href = "login.php";
                } else {
                    showNotification("Logout failed.", "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification("Connection error during logout.", "error");
            });
        }

        // ========== ORDERS & CUSTOMERS FETCHING ==========
        let allCustomers = [];
        let allOrders = []; // Added global variable for Orders to allow searching

        document.addEventListener('DOMContentLoaded', () => {
            getProductsData();
            getOrdersData();
            getCustomersData();
        });

        // --- Fetch & Render Orders ---
        async function getOrdersData() {
            try {
                const response = await fetch('actions/orders-data.php');
                allOrders = await response.json();
                
                // Track standard order statuses
                let counts = { pending: 0, processing: 0, shipped: 0, delivered: 0 };
                
                // Track monthly stats
                let monthlySales = 0;
                let newOrdersThisMonth = 0;
                
                // Get current month and year to compare against the database timestamps
                const now = new Date();
                const currentMonth = now.getMonth();
                const currentYear = now.getFullYear();

                allOrders.forEach(order => {
                    // 1. Calculate Status Counts
                    if (counts[order.order_status] !== undefined) counts[order.order_status]++;
                    
                    // 2. Calculate Monthly Stats
                    const orderDate = new Date(order.created_at);
                    if (orderDate.getMonth() === currentMonth && orderDate.getFullYear() === currentYear) {
                        newOrdersThisMonth++;
                        
                        // Add to sales total (ignoring cancelled orders for accurate revenue)
                        if (order.order_status !== 'cancelled' && order.payment_status === 'paid') {
                            monthlySales += parseFloat(order.total_amount);
                        }
                    }
                });
                
                // Update specific status tiles
                document.getElementById('count-pending').innerText = counts.pending;
                document.getElementById('count-processing').innerText = counts.processing;
                document.getElementById('count-shipped').innerText = counts.shipped;
                document.getElementById('count-delivered').innerText = counts.delivered;

                // Update the Top Overview Dashboard Tiles
                document.getElementById('stat-new-orders').innerText = newOrdersThisMonth;
                document.getElementById('stat-total-sales').innerText = new Intl.NumberFormat('en-LK', { 
                    style: 'currency', 
                    currency: 'LKR', 
                    maximumFractionDigits: 0 
                }).format(monthlySales);

                // --- NOTIFICATION ENGINE ---
                const pendingOrders = allOrders.filter(o => o.order_status === 'pending');
                const badge = document.getElementById('notificationBadge');
                const countText = document.getElementById('notificationCount');
                const notifItems = document.getElementById('notificationItems');
                
                if (pendingOrders.length > 0) {
                    badge.classList.remove('d-none');
                    countText.innerText = pendingOrders.length;
                    
                    notifItems.innerHTML = pendingOrders.slice(0, 5).map(order => `
                        <a href="#" class="dropdown-item border-bottom border-secondary py-3 px-3" onclick="viewOrderFromNotif('${order.order_number}')">
                            <div class="d-flex align-items-center">
                                <div class="bg-icon-orange rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <div style="min-width: 0;">
                                    <div class="small fw-bold text-white mb-1">Order ${order.order_number} <span class="text-muted fw-normal">requires attention</span></div>
                                    <div class="text-muted text-truncate" style="font-size: 0.8rem;">${order.customer_fname} • LKR ${parseFloat(order.total_amount).toLocaleString('en-LK')}</div>
                                </div>
                            </div>
                        </a>
                    `).join('');
                    
                    if (pendingOrders.length > 5) {
                        notifItems.innerHTML += `<div class="p-2 text-center"><a href="#" class="small text-gold text-decoration-none" onclick="switchView('orders', document.querySelectorAll('.menu-link')[3])">View all ${pendingOrders.length} pending orders</a></div>`;
                    }
                } else {
                    badge.classList.add('d-none');
                    countText.innerText = "0";
                    notifItems.innerHTML = `<div class="p-4 text-center text-muted small"><i class="fas fa-check-circle fa-2x mb-2 opacity-50 d-block"></i>You're all caught up!</div>`;
                }
                // --------------------------------

                renderOrders(allOrders);
            } catch (error) {
                console.error('Failed to fetch orders:', error);
            }
        }

        // --- NEW: Helper function to open an order directly from a notification ---
        function viewOrderFromNotif(orderNum) {
            // Switch to the Orders tab
            switchView('orders', document.querySelectorAll('.menu-link')[3]);
            
            // Put the order number in the search box and trigger the filter
            const searchInput = document.getElementById('orderSearch');
            if (searchInput) {
                searchInput.value = orderNum;
                filterOrders();
            }
        }

        // --- Order Search Filter ---
        function filterOrders() {
            const query = document.getElementById('orderSearch').value.toLowerCase();
            const filtered = allOrders.filter(o => 
                o.order_number.toLowerCase().includes(query) || 
                o.customer_fname.toLowerCase().includes(query) || 
                o.customer_lname.toLowerCase().includes(query)
            );
            renderOrders(filtered);
        }

        function renderOrders(orders) {
            const tbody = document.getElementById('ordersTableBody');
            tbody.innerHTML = '';

            if (!orders || orders.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr>`;
                return;
            }

            orders.forEach(order => {
                const formattedPrice = new Intl.NumberFormat('en-LK', { style: 'currency', currency: 'LKR' }).format(order.total_amount);
                const date = new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                const paymentBadge = order.payment_status === 'paid' ? 'status-paid' : (order.payment_status === 'refunded' ? 'status-cancelled' : 'status-unpaid');
                const orderBadge = `status-${order.order_status === 'cancelled' ? 'cancelled' : (order.order_status === 'shipped' || order.order_status === 'delivered' ? 'success' : 'pending')}`;

                const trHtml = `
                    <tr>
                        <td class="fw-bold" style="color: var(--chp-gold);">${order.order_number}</td>
                        <td>${date}</td>
                        <td>${order.customer_fname} ${order.customer_lname}</td>
                        <td>${formattedPrice}</td>
                        <td><span class="text-uppercase small fw-bold text-muted">${order.payment_method}</span></td>
                        <td><span class="badge ${paymentBadge} text-capitalize">${order.payment_status}</span></td>
                        <td><span class="badge ${orderBadge} text-capitalize">${order.order_status}</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn-icon-action border-0 bg-transparent text-white" type="button" data-bs-toggle="dropdown" data-bs-boundary="window" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" style="font-size: 0.85rem; z-index: 1060;">
                                    <li><h6 class="dropdown-header text-gold">Payment</h6></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus(${order.order_id}, 'payment_status', 'paid')">Mark as Paid</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus(${order.order_id}, 'payment_status', 'unpaid')">Mark as Unpaid</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header text-gold">Fulfillment</h6></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus(${order.order_id}, 'order_status', 'processing')">Processing</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus(${order.order_id}, 'order_status', 'shipped')">Shipped</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus(${order.order_id}, 'order_status', 'delivered')">Delivered</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus(${order.order_id}, 'order_status', 'cancelled')">Cancel Order</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', trHtml);
            });
        }

        function updateOrderStatus(orderId, column, newStatus) {
            const form = new FormData();
            form.append("order_id", orderId);
            form.append("column", column);
            form.append("status", newStatus);

            fetch('actions/update-order.php', { method: 'POST', body: form })
            .then(res => res.text())
            .then(data => {
                if(data.trim() === 'success') {
                    showNotification(`Status updated to ${newStatus}`, 'success');
                    getOrdersData(); // Refreshes UI and Stats
                } else {
                    showNotification("Failed to update status", "error");
                }
            })
            .catch(err => showNotification("Connection error", "error"));
        }

        // --- Fetch & Render Customers ---
        async function getCustomersData() {
            try {
                const response = await fetch('actions/customers-data.php');
                allCustomers = await response.json();
                renderCustomers(allCustomers);
            } catch (error) {
                console.error('Failed to fetch customers:', error);
            }
        }

        function renderCustomers(customers) {
            const tbody = document.getElementById('customersTableBody');
            tbody.innerHTML = '';

            if (!customers || customers.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">No customers found.</td></tr>`;
                return;
            }

            customers.forEach(customer => {
                const formattedPrice = new Intl.NumberFormat('en-LK', { style: 'currency', currency: 'LKR' }).format(customer.total_spent);
                
                const trHtml = `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="customer-avatar d-flex align-items-center justify-content-center text-white fw-bold bg-secondary">
                                    ${customer.fname.charAt(0)}${customer.lname.charAt(0)}
                                </div>
                                <span class="text-white">${customer.fname} ${customer.lname}</span>
                            </div>
                        </td>
                        <td>${customer.email}</td>
                        <td>${customer.order_count}</td>
                        <td style="color: var(--chp-gold); font-weight: 500;">${formattedPrice}</td>
                        <td><span class="badge status-instock">Active</span></td>
                        <td>
                            <a href="mailto:${customer.email}?subject=Power Watch Inquiry" class="btn-icon-action text-decoration-none" title="Send Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', trHtml);
            });
        }

        function filterCustomers() {
            const query = document.getElementById('customerSearch').value.toLowerCase();
            const filtered = allCustomers.filter(c => 
                c.fname.toLowerCase().includes(query) || 
                c.lname.toLowerCase().includes(query) || 
                c.email.toLowerCase().includes(query)
            );
            renderCustomers(filtered);
        }
    </script>
</body>
</html>