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
            z-index: 1000;
            transition: all 0.3s ease;
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
        }

        /* --- Main Content --- */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            transition: margin 0.3s ease;
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
        .bg-icon-blue { background: rgba(111, 149, 232, 0.2); color: var(--btn-blue); }
        .bg-icon-green { background: rgba(46, 204, 113, 0.2); color: var(--success-green); }

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

        /* --- Forms --- */
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

        @media (max-width: 992px) {
            .sidebar { left: -260px; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; }
            .mobile-toggle { display: block; }
            .overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            .overlay.active { display: block; }
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
                <svg width="30" height="30" viewBox="0 0 100 100" fill="none" stroke="var(--chp-gold)" stroke-width="5">
                    <path d="M50 10 L90 30 L90 70 L50 90 L10 70 L10 30 Z" />
                    <circle cx="50" cy="50" r="20" />
                </svg>
                <span class="brand-font h5 m-0 text-white">POWER <span class="text-gold">ADMIN</span></span>
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
                <a href="#" class="menu-link">
                    <i class="fas fa-box-open"></i> Inventory
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-shopping-bag"></i> Orders
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-users"></i> Customers
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-3">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=100&q=80" alt="Admin" class="rounded-circle" width="40" height="40">
                <div>
                    <p class="m-0 small fw-bold text-white">Admin User</p>
                    <p class="m-0 small text-muted">Manager</p>
                </div>
                <a href="#" class="ms-auto text-muted hover-gold"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Header -->
        <header class="top-header">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h2 class="h4 m-0 brand-font text-white" id="pageTitle">Overview</h2>
            </div>
            <div class="d-flex gap-3">
                <button class="btn btn-outline-gold rounded-circle p-2" style="width: 40px; height: 40px;"><i class="fas fa-bell"></i></button>
                <button class="btn btn-gold">Visit Store <i class="fas fa-external-link-alt ms-2"></i></button>
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
                        <p class="text-muted m-0">Total Sales (This Month)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="stat-icon bg-icon-blue">
                            <i class="fas fa-box"></i>
                        </div>
                        <h3 class="h2 fw-bold text-white mb-1">251</h3>
                        <p class="text-muted m-0">Total Products</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="stat-icon bg-icon-green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="h2 fw-bold text-white mb-1">18</h3>
                        <p class="text-muted m-0">New Orders</p>
                    </div>
                </div>
            </div>

            <!-- Recent Products Table -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-white m-0">Recent Inventory</h4>
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
                                <td><span class="badge bg-success">In Stock</span></td>
                                <td>
                                    <button class="btn btn-sm text-info"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=50&q=80" class="product-thumb"></td>
                                <td>Silver Mesh Band Watch</td>
                                <td>Casio</td>
                                <td>LKR 14,500</td>
                                <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                                <td>
                                    <button class="btn btn-sm text-info"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
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
                                <input type="text" class="form-control" placeholder="e.g. Titan Quartz Analog Blue Dial">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Brand</label>
                                <select class="form-select">
                                    <option selected disabled>Select Brand</option>
                                    <option value="1">Titan</option>
                                    <option value="2">Casio</option>
                                    <option value="3">Citizen</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select">
                                    <option selected disabled>Select Category</option>
                                    <option value="1">Wristwatch</option>
                                    <option value="2">Wall Decor</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="4" placeholder="Product details..."></textarea>
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
                                    <span class="small text-muted">KOKO Installment (Auto-calc)</span>
                                    <span class="fw-bold text-white" id="kokoDisplay">LKR 0.00 x 3</span>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <h5 class="text-white mb-3">Product Image</h5>
                        <div class="mb-4">
                            <div class="drop-zone">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="mb-1 text-white">Drag & drop product image here</p>
                                <p class="small text-muted">or click to browse (Max 2MB)</p>
                                <input type="file" hidden id="fileInput">
                            </div>
                        </div>

                        <!-- Toggles -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Stock Status</label>
                                <select class="form-select">
                                    <option value="In Stock">In Stock</option>
                                    <option value="Out of Stock">Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="luxurySwitch">
                                    <label class="form-check-label" for="luxurySwitch">Show in "Luxury Collection"</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="choiceSwitch">
                                    <label class="form-check-label" for="choiceSwitch">Show in "People's Choice"</label>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-3 pt-3 border-top border-secondary">
                            <button type="button" class="btn btn-outline-light px-4" onclick="switchView('dashboard', document.querySelectorAll('.menu-link')[0])">Cancel</button>
                            <button type="submit" class="btn btn-gold px-5">Save Product</button>
                        </div>
                    </form>
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
            document.getElementById('view-dashboard').classList.add('d-none-view');
            document.getElementById('view-add-product').classList.add('d-none-view');
            
            // Show selected view
            document.getElementById('view-' + viewId).classList.remove('d-none-view');
            
            // Update Title
            const titles = {
                'dashboard': 'Overview',
                'add-product': 'Add New Product'
            };
            document.getElementById('pageTitle').innerText = titles[viewId];

            // Update Sidebar Active State
            document.querySelectorAll('.menu-link').forEach(el => el.classList.remove('active'));
            if(linkElement) linkElement.classList.add('active');

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
        document.querySelector('.drop-zone').addEventListener('click', () => {
            document.getElementById('fileInput').click();
        });
    </script>
</body>
</html>