<?php
    session_start();
    require_once 'includes/connection.php';

    // Ensure DB is connected
    Database::setUpConnection();

    // Get filters from URL
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    $brand = isset($_GET['brand']) ? $_GET['brand'] : '';
    $price_min = isset($_GET['price_min']) ? intval($_GET['price_min']) : 0;
    $price_max = isset($_GET['price_max']) ? intval($_GET['price_max']) : 999999;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

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

    // Sort Logic
    $order_by = 'p.created_at DESC'; // default
    switch($sort) {
        case 'price_low': $order_by = 'p.current_price ASC'; break;
        case 'price_high': $order_by = 'p.current_price DESC'; break;
        case 'name_az': $order_by = 'p.product_name ASC'; break;
        case 'discount': $order_by = 'p.discount_percentage DESC'; break;
    }

    $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

    // --- 1. PAGINATION LOGIC ---
    $items_per_page = 32; // 8 rows of 4 cards
    $count_query = "SELECT COUNT(DISTINCT p.product_id) as total FROM products p LEFT JOIN brands b ON p.brand_id = b.brand_id LEFT JOIN sub_categories sc ON p.sub_category_id = sc.sub_category_id {$where_sql}";
    $count_stmt = Database::$connection->prepare($count_query);
    if (!empty($params)) { $count_stmt->bind_param($types, ...$params); }
    $count_stmt->execute();
    $total_items = $count_stmt->get_result()->fetch_assoc()['total'];
    $total_pages = max(1, ceil($total_items / $items_per_page));

    // Ensure current page is valid
    $page = max(1, min($total_pages, $page));
    $offset = ($page - 1) * $items_per_page;

    // --- 2. FETCH PRODUCTS ---
    $query = "SELECT 
        p.product_id as id,
        p.product_name as name,
        b.brand_name as brand,
        p.original_price,
        p.current_price as price,
        p.discount_percentage as discount,
        p.stock_status,
        p.image_path as image
    FROM products p
    LEFT JOIN brands b ON p.brand_id = b.brand_id
    LEFT JOIN sub_categories sc ON p.sub_category_id = sc.sub_category_id
    {$where_sql}
    ORDER BY {$order_by}
    LIMIT ? OFFSET ?";

    // Add limit and offset params
    $types .= 'ii';
    $params[] = $items_per_page;
    $params[] = $offset;

    $stmt = Database::$connection->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    if($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    // Get all brands
    $brands_query = "SELECT DISTINCT brand_name FROM brands WHERE brand_name IS NOT NULL ORDER BY brand_name";
    $brands_result = Database::search($brands_query);
    $all_brands = [];
    if($brands_result) { while ($b = $brands_result->fetch_assoc()) { $all_brands[] = $b['brand_name']; } }

    $categories = ["Men's Watches", "Women's Watches", "Smart Watches", "Luxury Collection", "Wall Clocks", "Photo Frames", "Wall Art", "Mirrors"];
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
    
    <link rel="stylesheet" href="assets/css/global.css">

    <style>
        .page-header { background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(212, 175, 55, 0.05)); border-bottom: 1px solid rgba(212, 175, 55, 0.2); padding: 2.5rem 0; margin-bottom: 2rem; }
        .page-title { font-size: 2.5rem; font-weight: 700; color: white; letter-spacing: 2px; }

        /* --- Sidebar Filters --- */
        .filter-sidebar { width: 280px; flex-shrink: 0; }
        .filter-card { background: var(--sec-blue); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .filter-title { font-size: 1rem; font-weight: 700; color: white; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; }
        .filter-title i { color: var(--chp-gold); margin-right: 8px; }
        .filter-option { display: flex; align-items: center; padding: 0.6rem 0; cursor: pointer; transition: 0.2s; }
        .filter-option:hover { color: var(--chp-gold); }
        .filter-option input { width: 18px; height: 18px; margin-right: 10px; accent-color: var(--chp-gold); cursor: pointer; }
        .price-input { background: var(--input-bg); border: 1px solid var(--border-color); color: white; padding: 0.6rem 0.75rem; border-radius: 6px; width: 100%; }
        .price-input:focus { outline: none; border-color: var(--chp-gold); }

        /* --- Search Dropdown --- */
        .search-container { position: relative; }
        .search-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: var(--sec-blue); border: 1px solid var(--border-color); border-radius: 8px; z-index: 1050; max-height: 300px; overflow-y: auto; box-shadow: 0 10px 25px rgba(0,0,0,0.5); display: none; margin-top: 5px; }
        .search-item { display: flex; align-items: center; gap: 10px; padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); cursor: pointer; transition: 0.2s; }
        .search-item:hover { background: rgba(212, 175, 55, 0.1); }
        .search-item img { width: 40px; height: 40px; object-fit: contain; background: white; border-radius: 4px; padding: 2px;}
        .search-item-text { font-size: 0.85rem; color: white; font-weight: 500; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        .toolbar { background: var(--sec-blue); border: 1px solid var(--border-color); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .sort-select { background: var(--input-bg); border: 1px solid var(--border-color); color: white; padding: 0.5rem 1rem; border-radius: 8px; }

        /* --- The "Business Card" Grid --- */
        .product-card { background: var(--dark-grey); border: none; border-radius: 12px; overflow: hidden; transition: 0.3s; height: 100%; display: flex; flex-direction: column; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: relative; cursor: pointer; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.3); border-color: var(--chp-gold); }
        .card-img-wrapper { aspect-ratio: 4 / 3; background: white; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;}
        .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        
        .badge-overlay { position: absolute; top: 10px; left: 10px; z-index: 10; }
        .discount-badge { background: var(--danger-red); color: white; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        
        .card-body { padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1; }
        .card-title { font-size: 18px; font-weight: 700; margin-bottom: 1rem; line-height: 1.3; color: white; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        
        .price-row { display: flex; align-items: baseline; gap: 12px; margin-bottom: 0.5rem; }
        .current-price { font-size: 18px; font-weight: 700; color: var(--chp-gold); }
        .old-price { font-size: 14px; font-weight: 400; color: var(--text-muted); text-decoration: line-through;}
        
        .koko-text { font-size: 11px; color: #e2e8f0; margin-bottom: 1.5rem; }
        .koko-logo { font-weight: 800; color: #7191D9; letter-spacing: 1px; font-style: italic; }

        .card-actions { display: flex; gap: 10px; margin-top: auto; flex-direction: row-reverse;}
        .btn-add-cart-outline { flex-grow: 1; background: transparent; border: 2px solid var(--chp-gold); color: white; border-radius: 25px; font-weight: 600; font-size: 14px; transition: 0.3s; padding: 10px; text-transform: uppercase; letter-spacing: 0.5px;}
        .btn-add-cart-outline:hover:not(:disabled) { background: var(--chp-gold); color: #000; }
        .btn-add-cart-outline:disabled { border-color: #555; color: #555; cursor: not-allowed; }
        
        .btn-view-solid { background: var(--chp-gold); color: #000; border: none; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; transition: 0.3s; font-size: 16px; flex-shrink: 0; }
        .btn-view-solid:hover { transform: scale(1.05); }

        /* --- Pagination UI --- */
        .pagination-wrapper { display: flex; justify-content: center; margin-top: 3rem; }
        .pagination .page-link { background: var(--sec-blue); border: 1px solid var(--border-color); color: var(--text-muted); padding: 0.75rem 1.25rem; border-radius: 6px; transition: 0.2s; font-weight: 600; }
        .pagination .page-link:hover { background: rgba(212, 175, 55, 0.1); border-color: var(--chp-gold); color: var(--chp-gold); }
        .pagination .page-item.active .page-link { background: var(--chp-gold); border-color: var(--chp-gold); color: #000; }

        /* --- Responsive Offcanvas --- */
        @media (max-width: 991px) {
            .collection-wrapper { flex-direction: column; }
            .offcanvas-lg { background-color: var(--prm-blue) !important; border-right: 1px solid var(--border-color); width: 320px; max-width: 85vw; }
            .offcanvas-body { padding: 1.5rem; overflow-y: auto; }
            .toolbar { flex-direction: column; align-items: stretch; gap: 15px; }
            .toolbar .sort-select { width: 100%; }
        }
        @media (max-width: 768px) {
            .card-body { padding: 1.25rem; }
            .card-title { font-size: 16px; }
            .card-actions { flex-direction: column-reverse; }
            .btn-view-solid { width: 100%; border-radius: 25px; height: 44px; margin-top: 5px; }
            .koko-text { font-size: 10px; }
        }
    </style>
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <section class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title font-oswald">Watch Collection</h1>
                    <p class="m-0 text-muted">Discover timeless elegance and precision</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            
            <button class="btn btn-gold mb-4 d-lg-none w-100 py-3 shadow" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar" aria-controls="filterSidebar">
                <i class="fas fa-filter me-2"></i> Sort, Filters & Search
            </button>

            <div class="collection-wrapper d-flex gap-4">
                
                <aside class="offcanvas-lg offcanvas-start filter-sidebar" tabindex="-1" id="filterSidebar" aria-labelledby="filterSidebarLabel">
                    
                    <div class="offcanvas-header border-bottom border-secondary d-lg-none mb-3 pb-3">
                        <h5 class="offcanvas-title text-white font-oswald" id="filterSidebarLabel">
                            <i class="fas fa-sliders-h text-gold me-2"></i> Filter Products
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar" aria-label="Close"></button>
                    </div>
                    
                    <div class="offcanvas-body d-block p-0">
                    
                    <div class="filter-card search-container">
                        <div class="filter-title"><i class="fas fa-search"></i> Search</div>
                        <input type="text" id="searchInput" class="price-input" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>" oninput="handleLiveSearch(this.value)" autocomplete="off">
                        <div id="searchDropdown" class="search-dropdown custom-scrollbar"></div>
                    </div>

                    <div class="filter-card">
                        <div class="filter-title"><i class="fas fa-th-large"></i> Categories</div>
                        <?php foreach ($categories as $cat): ?>
                        <div class="filter-option">
                            <input type="radio" name="category" value="<?php echo htmlspecialchars($cat); ?>" id="cat-<?php echo md5($cat); ?>" <?php echo $category === $cat ? 'checked' : ''; ?>>
                            <label for="cat-<?php echo md5($cat); ?>"><?php echo $cat; ?></label>
                        </div>
                        <?php endforeach; ?>
                        <div class="filter-option">
                            <input type="radio" name="category" value="all" id="cat-all" <?php echo $category === 'all' ? 'checked' : ''; ?>>
                            <label for="cat-all">All Categories</label>
                        </div>
                    </div>

                    <div class="filter-card">
                        <div class="filter-title"><i class="fas fa-award"></i> Brands</div>
                        <?php foreach ($all_brands as $b): ?>
                        <div class="filter-option">
                            <input type="radio" name="brand" value="<?php echo htmlspecialchars($b); ?>" id="brand-<?php echo md5($b); ?>" <?php echo $brand === $b ? 'checked' : ''; ?>>
                            <label for="brand-<?php echo md5($b); ?>"><?php echo $b; ?></label>
                        </div>
                        <?php endforeach; ?>
                        <div class="filter-option">
                            <input type="radio" name="brand" value="" id="brand-all" <?php echo $brand === '' ? 'checked' : ''; ?>>
                            <label for="brand-all">All Brands</label>
                        </div>
                    </div>

                    <div class="filter-card">
                        <div class="filter-title"><i class="fas fa-tags"></i> Price Range (LKR)</div>
                        <div class="d-flex gap-2">
                            <input type="number" id="priceMin" class="price-input" placeholder="Min" value="<?php echo $price_min > 0 ? $price_min : ''; ?>">
                            <input type="number" id="priceMax" class="price-input" placeholder="Max" value="<?php echo $price_max < 999999 ? $price_max : ''; ?>">
                        </div>
                    </div>

                    <button class="btn btn-gold mb-2" onclick="applyFilters()"><i class="fas fa-check me-2"></i> Apply Filters</button>
                    <button class="btn btn-outline-light w-100 py-2 border-secondary" onclick="window.location.href='collection.php'"><i class="fas fa-times me-2"></i> Clear All</button>
                    
                    </div> </aside>

                <div class="flex-grow-1" style="min-width: 0;">
                    
                    <div class="toolbar">
                        <span class="text-muted">Showing <?php echo count($products); ?> products (Page <?php echo $page; ?> of <?php echo $total_pages; ?>)</span>
                        <select class="sort-select" id="sortSelect" onchange="applyFilters()">
                            <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest Arrivals</option>
                            <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="name_az" <?php echo $sort === 'name_az' ? 'selected' : ''; ?>>Name: A to Z</option>
                            <option value="discount" <?php echo $sort === 'discount' ? 'selected' : ''; ?>>Highest Discount</option>
                        </select>
                    </div>

                    <?php if (count($products) > 0): ?>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                        <?php foreach ($products as $p): 
                            $safeName = htmlspecialchars(addslashes($p['name']));
                            $img = htmlspecialchars($p['image'] ? $p['image'] : 'assets/images/products/default.png');
                            $koko = number_format($p['price'] / 3, 2);
                            $isOut = $p['stock_status'] === 'Out of Stock';
                        ?>
                        <div class="col">
                            <div class="product-card" onclick="window.location.href='product-page.php?id=<?php echo $p['id']; ?>'">
                                
                                <div class="card-img-wrapper">
                                    <div class="badge-overlay">
                                        <?php if ($p['discount'] > 0): ?>
                                            <span class="discount-badge">-<?php echo $p['discount']; ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                </div>
                                
                                <div class="card-body">
                                    <span class="text-gold small fw-bold text-uppercase mb-1" style="letter-spacing:1px;"><?php echo htmlspecialchars($p['brand'] ?? 'POWER WATCH'); ?></span>
                                    <h6 class="card-title"><?php echo htmlspecialchars($p['name']); ?></h6>
                                    
                                    <div class="mt-auto">
                                        <div class="price-row">
                                            <div class="current-price font-oswald">LKR <?php echo number_format($p['price'], 2); ?></div>
                                            <?php if ($p['original_price'] > $p['price']): ?>
                                                <div class="old-price">LKR <?php echo number_format($p['original_price'], 2); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="koko-text">
                                            or pay in 3 x Rs <?php echo $koko; ?> with <span class="koko-logo">KOKO</span>
                                        </div>

                                        <div class="card-actions">
                                            <button class="btn-add-cart-outline" 
                                                onclick="event.stopPropagation(); quickAddToCart(<?php echo $p['id']; ?>, '<?php echo $safeName; ?>', <?php echo $p['price']; ?>, '<?php echo $img; ?>')" 
                                                <?php echo $isOut ? 'disabled' : ''; ?>>
                                                <?php echo $isOut ? 'Out of Stock' : 'Add to Cart'; ?>
                                            </button>
                                            <button class="btn-view-solid" onclick="event.stopPropagation(); window.location.href='product-page.php?id=<?php echo $p['id']; ?>'">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if($total_pages > 1): ?>
                    <div class="pagination-wrapper">
                        <ul class="pagination m-0">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="#" onclick="changePage(<?php echo $page - 1; ?>); return false;">&laquo; Prev</a>
                            </li>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="#" onclick="changePage(<?php echo $i; ?>); return false;"><?php echo $i; ?></a>
                                    </li>
                                <?php elseif($i == 2 || $i == $total_pages - 1): ?>
                                    <li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="#" onclick="changePage(<?php echo $page + 1; ?>); return false;">Next &raquo;</a>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-search-minus fa-4x text-muted opacity-50 mb-4"></i>
                        <h3 class="text-white font-oswald">No Products Found</h3>
                        <p class="text-muted">Try adjusting your filters or search terms.</p>
                        <button class="btn btn-outline-gold mt-3 px-4" onclick="window.location.href='collection.php'">Reset Filters</button>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <?php include 'components/cart-offcanvas.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cart.js"></script>

    <script>
        // --- 1. CORE FILTER SUBMISSION ---
        function applyFilters(targetPage = 1) {
            const params = new URLSearchParams();
            
            // Category
            const category = document.querySelector('input[name="category"]:checked');
            if (category && category.value !== 'all') params.append('category', category.value);
            
            // Brand
            const brand = document.querySelector('input[name="brand"]:checked');
            if (brand && brand.value !== '') params.append('brand', brand.value);
            
            // Price
            const pMin = document.getElementById('priceMin').value;
            const pMax = document.getElementById('priceMax').value;
            if (pMin) params.append('price_min', pMin);
            if (pMax) params.append('price_max', pMax);
            
            // Search
            const search = document.getElementById('searchInput').value;
            if (search) params.append('search', search);
            
            // Sort
            const sort = document.getElementById('sortSelect').value;
            if (sort !== 'newest') params.append('sort', sort);

            // Pagination
            if (targetPage > 1) params.append('page', targetPage);

            window.location.href = 'collection.php?' + params.toString();
        }

        // --- 2. PAGINATION ---
        function changePage(page) {
            applyFilters(page);
        }

        // --- 3. LIVE SEARCH (Like Dashboard) ---
        let allLiveProducts = null;
        let searchTimeout;

        async function fetchProductsForSearch() {
            if (allLiveProducts) return; // Already loaded
            try {
                const response = await fetch('admin/actions/products-data.php');
                allLiveProducts = await response.json();
            } catch (e) { console.error("Could not load live search data"); }
        }

        function handleLiveSearch(query) {
            clearTimeout(searchTimeout);
            const dropdown = document.getElementById('searchDropdown');

            if (!query.trim()) {
                dropdown.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(async () => {
                await fetchProductsForSearch();
                if (!allLiveProducts) return;

                const results = allLiveProducts.filter(p => 
                    p.name.toLowerCase().includes(query.toLowerCase()) || 
                    (p.brand && p.brand.name && p.brand.name.toLowerCase().includes(query.toLowerCase()))
                );

                if (results.length === 0) {
                    dropdown.innerHTML = '<div class="p-3 text-center text-muted small">No matches found</div>';
                } else {
                    // Show top 5 matches
                    dropdown.innerHTML = results.slice(0, 5).map(p => {
                        const img = p.primary_thumbnail ? p.primary_thumbnail : 'assets/images/products/default.png';
                        return `
                            <div class="search-item" onclick="window.location.href='product-page.php?id=${p.id}'">
                                <img src="${img}" onerror="this.src='assets/images/products/default.png'">
                                <div class="search-item-text">${p.name}</div>
                            </div>
                        `;
                    }).join('');
                }
                dropdown.style.display = 'block';
            }, 300);
        }

        // Hide dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-container')) {
                document.getElementById('searchDropdown').style.display = 'none';
            }
        });

        // Trigger filter on Enter key
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Stop default form submit if any
                applyFilters(); 
            }
        });

        // --- 4. CART ADDITION ---
        function quickAddToCart(id, name, price, img) {
            const pData = { 
                id: id, 
                name: name, 
                price: parseFloat(price), 
                image: img, 
                quantity: 1,
                options: { Type: 'Standard' } 
            };
            addToCart(pData); // Relies on your global cart.js
        }
    </script>
</body>
</html>