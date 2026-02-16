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
        
        .customer-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            background-color: var(--border-color);
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
            <a href="#" class="sidebar-brand">
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
                    <p class="m-0 small fw-bold text-white">Admin User</p>
                    <p class="m-0 small text">Manager</p>
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
                        <tbody>
                            <tr>
                                <td><img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=50&q=80" class="product-thumb"></td>
                                <td>Titan Quartz Analog Blue Dial</td>
                                <td>Titan</td>
                                <td>LKR 12,000</td>
                                <td><span class="badge status-instock">In Stock</span></td>
                                <td>
                                    <button class="btn-icon-action me-1"><i class="fas fa-edit"></i></button>
                                    <button class="btn-icon-action text-danger border-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=50&q=80" class="product-thumb"></td>
                                <td>Silver Mesh Band Watch</td>
                                <td>Casio</td>
                                <td>LKR 14,500</td>
                                <td><span class="badge status-lowstock">Low Stock</span></td>
                                <td>
                                    <button class="btn-icon-action me-1"><i class="fas fa-edit"></i></button>
                                    <button class="btn-icon-action text-danger border-danger"><i class="fas fa-trash"></i></button>
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
                    <form class="dashboard-card p-4">
                        <h4 class="text-gold mb-4 border-bottom border-secondary pb-3">Add New Product</h4>
                        
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
                                <div class="p-3 rounded bg-dark border border-secondary d-flex justify-content-between align-items-center">
                                    <span class="small text">KOKO Installment (Auto-calc)</span>
                                    <span class="fw-bold text-white" id="kokoDisplay">LKR 0.00 x 3</span>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <h5 class="text-white mb-3">Product Image</h5>
                        <div class="mb-4">
                            <div class="drop-zone">
                                <i class="fas fa-cloud-upload-alt fa-3x text mb-3"></i>
                                <p class="mb-1 text-white" id="fileNameDisplay">Drag & drop product image here</p>
                                <p class="small text">or click to browse (Max 2MB)</p>
                                <input type="file" hidden id="fileInput" onchange="updateFileName()">
                            </div>
                        </div>

                        <!-- Toggles -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Stock Status</label>
                                <select class="form-select" id="stockStatus">
                                    <option value="In Stock">In Stock</option>
                                    <option value="Out of Stock">Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mt-md-4">
                                    <input class="form-check-input" type="checkbox" id="luxurySwitch">
                                    <label class="form-check-label" for="luxurySwitch">Show in "Luxury Collection"</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mt-md-4">
                                    <input class="form-check-input" type="checkbox" id="choiceSwitch">
                                    <label class="form-check-label" for="choiceSwitch">Show in "People's Choice"</label>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
            dropZone.addEventListener('click', () => {
                fileInput.click();
            });
        }

        function updateFileName() {
            if(fileInput.files.length > 0) {
                document.getElementById('fileNameDisplay').innerText = fileInput.files[0].name;
            }
        }

        // Add Product Function
        function addProduct() {
            // Get Elements
            const title = document.getElementById('productName');
            const brand = document.getElementById('productBrand');
            const category = document.getElementById('productCategory');
            const desc = document.getElementById('productDesc');
            const oprice = document.getElementById('originalPrice');
            const cprice = document.getElementById('currentPrice');
            const stock = document.getElementById('stockStatus');
            const luxury = document.getElementById('luxurySwitch');
            const choice = document.getElementById('choiceSwitch');
            const imageFile = document.getElementById('fileInput').files[0];
            const saveBtn = document.getElementById('saveProductBtn');

            // --- STRICT VALIDATION ---
            if(!title.value.trim()) {
                alert("Please enter a Product Name.");
                title.focus();
                return;
            }
            if(brand.value === "0" || brand.value === "") {
                alert("Please select a Brand.");
                brand.focus();
                return;
            }
            if(category.value === "0" || category.value === "") {
                alert("Please select a Category.");
                category.focus();
                return;
            }
            if(!cprice.value || cprice.value <= 0) {
                alert("Please enter a valid Current Price.");
                cprice.focus();
                return;
            }

            // Visual Feedback
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            const form = new FormData();
            form.append("title", title.value);
            form.append("brand", brand.value);
            form.append("category", category.value);
            form.append("desc", desc.value);
            form.append("oprice", oprice.value);
            form.append("cprice", cprice.value);
            form.append("stock", stock.value);
            form.append("luxury", luxury.checked ? 'true' : 'false');
            form.append("choice", choice.checked ? 'true' : 'false');
            form.append("image", imageFile);

            fetch("actions/create-product.php", {
                method: "POST",
                body: form
            })
            .then(response => response.text())
            .then(data => {
                saveBtn.innerHTML = 'Save Product';
                saveBtn.disabled = false;

                if (data.trim() === "success") {
                    alert("Product Saved Successfully!");
                    
                    // Reset Form
                    title.value = '';
                    desc.value = '';
                    oprice.value = '';
                    cprice.value = '';
                    brand.value = '0';
                    category.value = '0';
                    document.getElementById('fileInput').value = '';
                    document.getElementById('fileNameDisplay').innerText = 'Drag & drop product image here';
                    
                    // Go to inventory to see it
                    switchView('inventory'); 
                } else {
                    alert("Database Error: " + data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                saveBtn.innerHTML = 'Save Product';
                saveBtn.disabled = false;
                alert("A connection error occurred. Check console.");
            });
        }
    </script>
</body>
</html>