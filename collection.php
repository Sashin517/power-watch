<?php
    session_start();
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
        /* ===== PAGE HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, rgba(212,175,55,0.1), rgba(212,175,55,0.05));
            border-bottom: 1px solid rgba(212,175,55,0.2);
            padding: 2.5rem 0;
            margin-bottom: 2rem;
        }
        .page-title { font-size: 2.5rem; font-weight: 700; color: white; letter-spacing: 2px; }

        /* ===== ACTIVE FILTERS PILLS (UX: show applied filters clearly) ===== */
        .active-filters-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 1rem;
            min-height: 0;
        }
        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(212,175,55,0.15);
            border: 1px solid rgba(212,175,55,0.4);
            color: var(--chp-gold);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .filter-pill:hover { background: rgba(212,175,55,0.3); }
        .filter-pill i { font-size: 0.7rem; }

        /* ===== SIDEBAR ===== */
        .filter-sidebar { width: 280px; flex-shrink: 0; }
        .filter-card {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
        }
        .filter-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }
        .filter-title i.icon-label { color: var(--chp-gold); margin-right: 8px; }
        .filter-title i.chevron { transition: transform 0.3s; font-size: 0.75rem; color: #777; }
        .filter-title.collapsed i.chevron { transform: rotate(-90deg); }

        .filter-option {
            display: flex;
            align-items: center;
            padding: 0.45rem 0;
            cursor: pointer;
            transition: 0.2s;
            font-size: 0.9rem;
            color: #ccc;
        }
        .filter-option:hover { color: var(--chp-gold); }
        .filter-option input[type="radio"],
        .filter-option input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-right: 10px;
            accent-color: var(--chp-gold);
            cursor: pointer;
            flex-shrink: 0;
        }
        .filter-option label { cursor: pointer; margin: 0; line-height: 1.3; }
        .filter-count { margin-left: auto; font-size: 0.72rem; color: #666; background: rgba(255,255,255,0.05); padding: 1px 7px; border-radius: 10px; }

        /* Price range */
        .price-input {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
            padding: 0.55rem 0.75rem;
            border-radius: 6px;
            width: 100%;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }
        .price-input:focus { outline: none; border-color: var(--chp-gold); }
        .price-input::placeholder { color: #666; }

        /* Search in sidebar */
        .search-container { position: relative; }
        .search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-input-wrapper i {
            position: absolute;
            left: 12px;
            color: #666;
            font-size: 0.85rem;
            pointer-events: none;
        }
        .search-input-wrapper input { padding-left: 34px; }
        .search-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0; right: 0;
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            z-index: 1050;
            max-height: 280px;
            overflow-y: auto;
            box-shadow: 0 12px 30px rgba(0,0,0,0.6);
            display: none;
        }
        .search-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-item:last-child { border-bottom: none; }
        .search-item:hover { background: rgba(212,175,55,0.1); }
        .search-item img {
            width: 42px;
            height: 42px;
            object-fit: contain;
            background: white;
            border-radius: 6px;
            padding: 3px;
            flex-shrink: 0;
        }
        .search-item-info { flex: 1; min-width: 0; }
        .search-item-name {
            font-size: 0.82rem;
            color: white;
            font-weight: 500;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
        }
        .search-item-price { font-size: 0.75rem; color: var(--chp-gold); font-weight: 600; margin-top: 2px; }

        /* ===== TOOLBAR ===== */
        .toolbar {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .product-count-text { color: #aaa; font-size: 0.875rem; white-space: nowrap; }
        .product-count-text span { color: white; font-weight: 700; }
        .sort-select {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .sort-select:focus { outline: none; border-color: var(--chp-gold); }

        /* ===== PRODUCT CARDS ===== */
        .product-card {
            background: var(--dark-grey);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            cursor: pointer;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(0,0,0,0.35);
            border-color: rgba(212,175,55,0.4);
        }
        .card-img-wrapper {
            aspect-ratio: 1 / 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }
        .product-card:hover .card-img-wrapper img { transform: scale(1.04); }

        .badge-overlay { position: absolute; top: 10px; left: 10px; z-index: 10; display: flex; flex-direction: column; gap: 5px; }
        .discount-badge {
            background: #e63946;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.72rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }
        .out-of-stock-badge {
            background: rgba(0,0,0,0.7);
            color: #ccc;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.72rem;
        }

        /* Quick view button on hover */
        .quick-view-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: rgba(0,0,0,0.65);
            color: white;
            text-align: center;
            padding: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transform: translateY(100%);
            transition: transform 0.25s ease;
        }
        .card-img-wrapper:hover .quick-view-overlay { transform: translateY(0); }

        .card-body {
            padding: 1.1rem 1.25rem 1.25rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .brand-label {
            font-size: 0.7rem;
            color: var(--chp-gold);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }
        .card-title {
            font-size: 0.92rem;
            font-weight: 600;
            margin-bottom: 0.6rem;
            line-height: 1.35;
            color: white;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.7em;
        }
        .price-row { display: flex; align-items: baseline; gap: 8px; margin-bottom: 4px; flex-wrap: wrap; }
        .current-price { font-size: 1rem; font-weight: 700; color: var(--chp-gold); font-family: 'Oswald', sans-serif; }
        .old-price { font-size: 0.8rem; color: #666; text-decoration: line-through; }
        .koko-text { font-size: 0.72rem; color: #888; margin-bottom: 1rem; line-height: 1.3; }
        .koko-logo { font-weight: 800; color: #7191D9; font-style: italic; }

        .card-actions { display: flex; gap: 8px; margin-top: auto; }
        .btn-add-cart-outline {
            flex-grow: 1;
            background: transparent;
            border: 1.5px solid var(--chp-gold);
            color: white;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.78rem;
            transition: 0.25s;
            padding: 9px 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .btn-add-cart-outline:hover:not(:disabled) { background: var(--chp-gold); color: #000; }
        .btn-add-cart-outline:disabled { border-color: #444; color: #555; cursor: not-allowed; }
        .btn-view-solid {
            background: var(--chp-gold);
            color: #000;
            border: none;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.25s;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .btn-view-solid:hover { background: var(--chp-gold-hover); transform: scale(1.08); }

        /* ===== SKELETON LOADER ===== */
        .skeleton-card { border-radius: 12px; overflow: hidden; background: var(--sec-blue); border: 1px solid var(--border-color); }
        .skeleton-img { aspect-ratio: 1/1; background: linear-gradient(90deg, #1e2a3a 25%, #243040 50%, #1e2a3a 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; }
        .skeleton-body { padding: 1.1rem 1.25rem 1.25rem; }
        .skeleton-line { height: 12px; border-radius: 6px; margin-bottom: 10px; background: linear-gradient(90deg, #1e2a3a 25%, #243040 50%, #1e2a3a 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; }
        .skeleton-line.short { width: 50%; }
        .skeleton-line.medium { width: 75%; }
        @keyframes shimmer { to { background-position: -200% 0; } }

        /* ===== PAGINATION ===== */
        .pagination-wrapper { display: flex; justify-content: center; margin-top: 2.5rem; }
        .pagination .page-link {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            color: #aaa;
            padding: 0.65rem 1.1rem;
            border-radius: 8px !important;
            margin: 0 3px;
            transition: 0.2s;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .pagination .page-link:hover { background: rgba(212,175,55,0.1); border-color: var(--chp-gold); color: var(--chp-gold); }
        .pagination .page-item.active .page-link { background: var(--chp-gold); border-color: var(--chp-gold); color: #000; }
        .pagination .page-item.disabled .page-link { opacity: 0.4; cursor: not-allowed; }

        /* ===== MOBILE FILTER & SORT (UX Upgrades) ===== */
        .btn-filter-mobile {
            flex: 1; /* Takes exactly half the space */
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            color: white;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 45px; /* Forced equal height */
        }
        .btn-filter-mobile:hover { border-color: var(--chp-gold); color: var(--chp-gold); }
        .btn-filter-mobile .filter-badge {
            background: var(--chp-gold);
            color: #000;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .btn-filter-mobile .filter-badge.visible { display: flex; }

        .sort-mobile-wrapper {
            flex: 1; /* Takes exactly half the space */
            position: relative;
            height: 45px; /* Forced equal height */
        }
        .sort-mobile-wrapper .sort-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-faded);
            pointer-events: none; /* Lets clicks pass through to the select */
        }
        .sort-select-mobile {
            width: 100%;
            height: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
            padding: 10px 10px 10px 35px; /* Extra left padding for the icon */
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            appearance: none; /* Removes the ugly default browser arrow */
            -webkit-appearance: none;
        }
        .sort-select-mobile:focus { outline: none; border-color: var(--chp-gold); }


        /* ===== OFFCANVAS ===== */
        @media (max-width: 991px) {
            .collection-wrapper { flex-direction: column; }
            .offcanvas-lg {
                background-color: var(--prm-blue) !important;
                border-right: 1px solid var(--border-color);
                width: 310px;
                max-width: 88vw;
            }
            .offcanvas-body { padding: 1.25rem; overflow-y: auto; }
        }

        /* ===== TOOLBAR RESPONSIVE ===== */
        @media (max-width: 767px) {
            .page-title { font-size: 1.8rem; }
            .toolbar { padding: 0.75rem 1rem; }
            .product-count-text { font-size: 0.78rem; }

            /* 2-column grid on mobile */
            .product-col { padding: 6px !important; }
            .card-body { padding: 0.85rem 0.9rem 1rem; }
            .card-title { font-size: 0.82rem; min-height: 2.4em; }
            .current-price { font-size: 0.9rem; }
            .koko-text { font-size: 0.66rem; margin-bottom: 0.75rem; }
            .btn-add-cart-outline { font-size: 0.7rem; padding: 7px 8px; }
            .btn-view-solid { width: 36px; height: 36px; font-size: 0.8rem; }
        }

        @media (max-width: 400px) {
            .card-title { font-size: 0.76rem; }
            .current-price { font-size: 0.85rem; }
            .btn-add-cart-outline { font-size: 0.65rem; letter-spacing: 0; }
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: #aaa;
        }
        .empty-state i { font-size: 3.5rem; opacity: 0.3; margin-bottom: 1.5rem; display: block; }
        .empty-state h4 { color: white; margin-bottom: 0.5rem; }

        /* ===== SCROLL TO TOP ===== */
        #scrollTopBtn {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 44px;
            height: 44px;
            background: var(--chp-gold);
            color: #000;
            border: none;
            border-radius: 50%;
            font-size: 1rem;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(212,175,55,0.4);
            transition: transform 0.2s;
        }
        #scrollTopBtn:hover { transform: translateY(-3px); }
        #scrollTopBtn.visible { display: flex; }

        /* Custom scrollbar for sidebar */
        .offcanvas-body::-webkit-scrollbar,
        .search-dropdown::-webkit-scrollbar { width: 4px; }
        .offcanvas-body::-webkit-scrollbar-track,
        .search-dropdown::-webkit-scrollbar-track { background: transparent; }
        .offcanvas-body::-webkit-scrollbar-thumb,
        .search-dropdown::-webkit-scrollbar-thumb { background: rgba(212,175,55,0.3); border-radius: 4px; }
    </style>
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title font-oswald">Watch Collection</h1>
                    <p class="m-0 text-muted" style="font-size:0.9rem;">Discover timeless elegance and precision</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-3 pb-5">
        <div class="container">

            <!-- Mobile: Filter + Sort bar (sticky feel) -->
            <div class="d-flex d-lg-none gap-2 mb-3 align-items-center">
                <button class="btn-filter-mobile m-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar">
                    <i class="fas fa-sliders-h"></i> Filters
                    <span class="filter-badge ms-auto" id="mobileFilterBadge">0</span>
                </button>
                
                <div class="sort-mobile-wrapper m-0">
                    <i class="fas fa-sort-amount-down sort-icon"></i>
                    <select class="sort-select-mobile w-100" id="sortSelectMobile" onchange="syncAndApply('mobile')">
                        <option value="newest">Newest</option>
                        <option value="price_low">Price ↑</option>
                        <option value="price_high">Price ↓</option>
                        <option value="name_az">A → Z</option>
                        <option value="discount">Best Deal</option>
                    </select>
                </div>
            </div>

            <!-- Active Filter Pills -->
            <div class="active-filters-bar" id="activeFiltersBar" style="display:none!important;"></div>

            <div class="collection-wrapper d-flex gap-4">

                <!-- ===== SIDEBAR (offcanvas on mobile, static on desktop) ===== -->
                <aside class="offcanvas-lg offcanvas-start filter-sidebar" tabindex="-1" id="filterSidebar" aria-labelledby="filterSidebarLabel">
                    
                    <div class="offcanvas-header border-bottom border-secondary d-lg-none mb-2 pb-3">
                        <h5 class="offcanvas-title text-white font-oswald" id="filterSidebarLabel">
                            <i class="fas fa-sliders-h text-gold me-2"></i> Filters
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar" aria-label="Close"></button>
                    </div>
                    
                    <div class="offcanvas-body d-block p-0">

                        <!-- Search -->
                        <div class="filter-card search-container">
                            <div class="filter-title" style="cursor:default;">
                                <span><i class="fas fa-search icon-label"></i> Search</span>
                            </div>
                            <div class="search-input-wrapper">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchInput" class="price-input" placeholder="Product name or brand..." autocomplete="off"
                                    oninput="handleLiveSearch(this.value)">
                            </div>
                            <div id="searchDropdown" class="search-dropdown"></div>
                        </div>

                        <!-- Categories -->
                        <div class="filter-card">
                            <div class="filter-title" onclick="toggleSection('catSection', this)">
                                <span><i class="fas fa-th-large icon-label"></i> Category</span>
                                <i class="fas fa-chevron-down chevron"></i>
                            </div>
                            <div id="catSection">
                                <div class="filter-option">
                                    <input type="radio" name="category" value="all" id="cat-all" checked>
                                    <label for="cat-all">All Categories</label>
                                </div>
                                <!-- Populated by JS -->
                                <div id="categoryList"></div>
                            </div>
                        </div>

                        <!-- Brands -->
                        <div class="filter-card">
                            <div class="filter-title" onclick="toggleSection('brandSection', this)">
                                <span><i class="fas fa-award icon-label"></i> Brand</span>
                                <i class="fas fa-chevron-down chevron"></i>
                            </div>
                            <div id="brandSection">
                                <div class="filter-option">
                                    <input type="radio" name="brand" value="" id="brand-all" checked>
                                    <label for="brand-all">All Brands</label>
                                </div>
                                <!-- Populated by JS -->
                                <div id="brandList"></div>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="filter-card">
                            <div class="filter-title" onclick="toggleSection('priceSection', this)">
                                <span><i class="fas fa-tags icon-label"></i> Price (LKR)</span>
                                <i class="fas fa-chevron-down chevron"></i>
                            </div>
                            <div id="priceSection">
                                <div class="d-flex gap-2">
                                    <input type="number" id="priceMin" class="price-input" placeholder="Min" min="0">
                                    <input type="number" id="priceMax" class="price-input" placeholder="Max" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="px-0 pb-3">
                            <button class="btn btn-gold w-100 mb-2 py-2" onclick="applyFilters()">
                                <i class="fas fa-check me-2"></i> Apply Filters
                            </button>
                            <button class="btn w-100 py-2" style="background:transparent; border:1px solid #444; color:#aaa; border-radius:8px;" onclick="clearAllFilters()">
                                <i class="fas fa-times me-2"></i> Clear All
                            </button>
                        </div>

                    </div>
                </aside>

                <!-- ===== MAIN CONTENT ===== -->
                <div class="flex-grow-1" style="min-width:0;">
                    
                    <!-- Toolbar (desktop) -->
                    <div class="toolbar d-none d-lg-flex">
                        <span class="product-count-text" id="productCountText">Loading products...</span>
                        <select class="sort-select" id="sortSelect" onchange="syncAndApply('desktop')">
                            <option value="newest">Newest Arrivals</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="name_az">Name: A to Z</option>
                            <option value="discount">Highest Discount</option>
                        </select>
                    </div>

                    <!-- Active filter pills (desktop) -->
                    <div id="filterPillsDesktop" class="active-filters-bar mb-3"></div>

                    <!-- Product Grid -->
                    <div class="row g-3" id="productGrid">
                        <!-- Skeleton loaders shown while fetching -->
                        <?php for($s=0;$s<8;$s++): ?>
                        <div class="col-6 col-md-4 col-lg-3 product-col">
                            <div class="skeleton-card">
                                <div class="skeleton-img"></div>
                                <div class="skeleton-body">
                                    <div class="skeleton-line short"></div>
                                    <div class="skeleton-line medium"></div>
                                    <div class="skeleton-line"></div>
                                    <div class="skeleton-line short"></div>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper" id="paginationWrapper"></div>

                </div>
            </div>
        </div>
    </section>

    <!-- Scroll to top -->
    <button id="scrollTopBtn" aria-label="Scroll to top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fas fa-chevron-up"></i>
    </button>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/cart-offcanvas.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cart.js"></script>

    <script>
        // ============================================================
        // STATE
        // ============================================================
        let globalProducts  = [];   // All products from API
        let filteredProducts = [];  // After filters applied
        let currentPage     = 1;
        const ITEMS_PER_PAGE = 12;

        // ============================================================
        // BOOT: fetch all products once, then build UI from that
        // ============================================================
        document.addEventListener('DOMContentLoaded', async () => {
            await loadProducts();
            readURLParams();
            applyFilters();
            initScrollTop();
        });

        async function loadProducts() {
            try {
                const res = await fetch('admin/actions/products-data.php');
                if (!res.ok) throw new Error('Network error');
                const data = await res.json();
                globalProducts = Array.isArray(data) ? data : Object.values(data);
                buildFilterOptions();
            } catch (err) {
                console.error('Failed to fetch products:', err);
                document.getElementById('productGrid').innerHTML = `
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h4>Could not load products</h4>
                            <p>Please check your connection and try again.</p>
                            <button class="btn btn-gold mt-2" onclick="location.reload()">Retry</button>
                        </div>
                    </div>`;
            }
        }

        // ============================================================
        // BUILD FILTER OPTIONS FROM API DATA (no PHP loops needed)
        // ============================================================
        function buildFilterOptions() {
            // Categories (Combines Top-Level and Sub-Categories for deep linking)
            const cats = [...new Set(globalProducts.flatMap(p => [p.category, p.sub_category]).filter(Boolean))].sort();
            
            const catList = document.getElementById('categoryList');
            catList.innerHTML = cats.map(c => `
                <div class="filter-option">
                    <input type="radio" name="category" value="${c}" id="cat-${btoa(c).replace(/=/g,'')}">
                    <label for="cat-${btoa(c).replace(/=/g,'')}">${c}</label>
                    <span class="filter-count">${globalProducts.filter(p => p.category === c || p.sub_category === c).length}</span>
                </div>`).join('');

            // Brands
            const brands = [...new Set(globalProducts.map(p => p.brand?.name).filter(Boolean))].sort();
            const brandList = document.getElementById('brandList');
            brandList.innerHTML = brands.map(b => `
                <div class="filter-option">
                    <input type="radio" name="brand" value="${b}" id="brand-${btoa(b).replace(/=/g,'')}">
                    <label for="brand-${btoa(b).replace(/=/g,'')}">${b}</label>
                    <span class="filter-count">${globalProducts.filter(p=>p.brand?.name===b).length}</span>
                </div>`).join('');
        }

        // ============================================================
        // READ URL PARAMS (e.g. coming from navbar search ?search=casio)
        // ============================================================
        function readURLParams() {
            const p = new URLSearchParams(window.location.search);
            if (p.get('search'))    { document.getElementById('searchInput').value = p.get('search'); }
            if (p.get('category'))  { const el = document.querySelector(`input[name="category"][value="${p.get('category')}"]`); if(el) el.checked = true; }
            if (p.get('brand'))     { const el = document.querySelector(`input[name="brand"][value="${p.get('brand')}"]`); if(el) el.checked = true; }
            if (p.get('price_min')) { document.getElementById('priceMin').value = p.get('price_min'); }
            if (p.get('price_max')) { document.getElementById('priceMax').value = p.get('price_max'); }
            if (p.get('sort'))      { document.getElementById('sortSelect').value = p.get('sort'); document.getElementById('sortSelectMobile').value = p.get('sort'); }
            if (p.get('page'))      { currentPage = parseInt(p.get('page')) || 1; }
        }

        // ============================================================
        // SYNC BOTH SORT SELECTS (desktop & mobile)
        // ============================================================
        function syncAndApply(source) {
            const val = source === 'mobile'
                ? document.getElementById('sortSelectMobile').value
                : document.getElementById('sortSelect').value;
            document.getElementById('sortSelect').value = val;
            document.getElementById('sortSelectMobile').value = val;
            currentPage = 1;
            applyFilters();
        }

        // ============================================================
        // APPLY ALL FILTERS + SORT  →  render
        // ============================================================
        function applyFilters() {
            const query    = document.getElementById('searchInput').value.trim().toLowerCase();
            const category = document.querySelector('input[name="category"]:checked')?.value || 'all';
            const brand    = document.querySelector('input[name="brand"]:checked')?.value || '';
            const priceMin = parseFloat(document.getElementById('priceMin').value) || 0;
            const priceMax = parseFloat(document.getElementById('priceMax').value) || Infinity;
            const sortMode = document.getElementById('sortSelect').value;

            filteredProducts = globalProducts.filter(p => {
                const matchSearch = !query ||
                    p.name.toLowerCase().includes(query) ||
                    (p.brand?.name?.toLowerCase().includes(query)) ||
                    (p.category?.toLowerCase().includes(query));

                const matchCategory = (category === 'all') ||
                    (p.category === category) ||
                    (p.sub_category === category);

                const matchBrand = !brand || (p.brand?.name === brand);

                const price = p.pricing?.current_price || 0;
                const matchPrice = price >= priceMin && price <= priceMax;

                return matchSearch && matchCategory && matchBrand && matchPrice;
            });

            // Sort
            switch(sortMode) {
                case 'price_low':  filteredProducts.sort((a,b) => a.pricing.current_price - b.pricing.current_price); break;
                case 'price_high': filteredProducts.sort((a,b) => b.pricing.current_price - a.pricing.current_price); break;
                case 'name_az':    filteredProducts.sort((a,b) => a.name.localeCompare(b.name)); break;
                case 'discount':   filteredProducts.sort((a,b) => (b.pricing.discount_percent||0) - (a.pricing.discount_percent||0)); break;
                default:           filteredProducts.sort((a,b) => b.id - a.id); // newest
            }

            renderActiveFilterPills({ query, category, brand, priceMin, priceMax });
            updateMobileFilterBadge({ query, category, brand, priceMin, priceMax });
            renderPage(currentPage);
            renderPagination();
        }

        function clearAllFilters() {
            document.getElementById('searchInput').value = '';
            document.querySelector('input[name="category"][value="all"]').checked = true;
            document.querySelector('input[name="brand"][value=""]').checked = true;
            document.getElementById('priceMin').value = '';
            document.getElementById('priceMax').value = '';
            document.getElementById('sortSelect').value = 'newest';
            document.getElementById('sortSelectMobile').value = 'newest';
            currentPage = 1;
            applyFilters();
        }

        // ============================================================
        // RENDER PRODUCT GRID (paginated slice)
        // ============================================================
        function renderPage(page) {
            currentPage = page;
            const grid  = document.getElementById('productGrid');
            const start = (page - 1) * ITEMS_PER_PAGE;
            const slice = filteredProducts.slice(start, start + ITEMS_PER_PAGE);

            // Update count
            document.getElementById('productCountText').innerHTML =
                `Showing <span>${filteredProducts.length}</span> product${filteredProducts.length !== 1 ? 's' : ''}`;

            if (filteredProducts.length === 0) {
                grid.innerHTML = `
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <h4>No products found</h4>
                            <p>Try different keywords, categories, or clear your filters.</p>
                            <button class="btn btn-outline-gold mt-2 px-4" onclick="clearAllFilters()">
                                <i class="fas fa-times me-2"></i>Clear All Filters
                            </button>
                        </div>
                    </div>`;
                return;
            }

            grid.innerHTML = slice.map(p => {
                const img  = p.primary_thumbnail || 'assets/images/products/default.png';
                const price = new Intl.NumberFormat('en-LK').format(p.pricing.current_price);
                const koko  = new Intl.NumberFormat('en-LK').format(p.pricing.current_price / 3);
                const isOut = p.inventory?.stock_status === 'Out of Stock';
                const disc  = p.pricing.discount_percent || 0;
                const safeName = p.name.replace(/'/g, "\\'").replace(/"/g, '&quot;');

                const badges = `
                    <div class="badge-overlay">
                        ${isOut ? '<span class="out-of-stock-badge">Out of Stock</span>' : ''}
                        ${!isOut && disc > 0 ? `<span class="discount-badge">-${disc}% OFF</span>` : ''}
                    </div>`;

                const oldPriceHtml = p.pricing.original_price > p.pricing.current_price
                    ? `<span class="old-price">LKR ${new Intl.NumberFormat('en-LK').format(p.pricing.original_price)}</span>` : '';

                return `
                <div class="col-6 col-md-4 col-lg-3 product-col">
                    <div class="product-card" onclick="window.location.href='product-page.php?id=${p.id}'">
                        <div class="card-img-wrapper">
                            ${badges}
                            <img src="${img}" alt="${safeName}"
                                onerror="this.src='assets/images/products/default.png'">
                            <div class="quick-view-overlay">
                                <i class="fas fa-eye me-1"></i> Quick View
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="brand-label">${p.brand?.name || 'POWER WATCH'}</div>
                            <h6 class="card-title" title="${safeName}">${p.name}</h6>
                            <div class="price-row">
                                <span class="current-price">LKR ${price}</span>
                                ${oldPriceHtml}
                            </div>
                            <p class="koko-text">or 3 × Rs ${koko} with <span class="koko-logo">KOKO</span></p>
                            <div class="card-actions">
                                <button class="btn-add-cart-outline"
                                    onclick="event.stopPropagation(); quickAddToCart(${p.id}, '${safeName}', ${p.pricing.current_price}, '${img}')"
                                    ${isOut ? 'disabled' : ''}>
                                    ${isOut ? 'Out of Stock' : '<i class="fas fa-cart-plus me-1"></i>Add to Cart'}
                                </button>
                                <button class="btn-view-solid"
                                    onclick="event.stopPropagation(); window.location.href='product-page.php?id=${p.id}'"
                                    title="View details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            }).join('');
        }

        // ============================================================
        // PAGINATION
        // ============================================================
        function renderPagination() {
            const totalPages = Math.ceil(filteredProducts.length / ITEMS_PER_PAGE);
            const wrapper = document.getElementById('paginationWrapper');

            if (totalPages <= 1) { wrapper.innerHTML = ''; return; }

            let items = '';

            // Prev
            items += `<li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goPage(${currentPage - 1}); return false;">
                    <i class="fas fa-chevron-left"></i>
                </a></li>`;

            // Pages with ellipsis
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    items += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="goPage(${i}); return false;">${i}</a></li>`;
                } else if (i === 2 || i === totalPages - 1) {
                    items += `<li class="page-item disabled"><span class="page-link border-0 bg-transparent">…</span></li>`;
                }
            }

            // Next
            items += `<li class="page-item ${currentPage >= totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goPage(${currentPage + 1}); return false;">
                    <i class="fas fa-chevron-right"></i>
                </a></li>`;

            wrapper.innerHTML = `<ul class="pagination m-0">${items}</ul>`;
        }

        function goPage(page) {
            const totalPages = Math.ceil(filteredProducts.length / ITEMS_PER_PAGE);
            if (page < 1 || page > totalPages) return;
            renderPage(page);
            renderPagination();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ============================================================
        // ACTIVE FILTER PILLS (UX: show what's applied so user knows)
        // ============================================================
        function renderActiveFilterPills({ query, category, brand, priceMin, priceMax }) {
            const bar = document.getElementById('filterPillsDesktop');
            let pills = '';

            if (query)              pills += pill(`Search: "${query}"`,       () => { document.getElementById('searchInput').value=''; applyFilters(); });
            if (category !== 'all') pills += pill(`Category: ${category}`,    () => { document.querySelector('input[name="category"][value="all"]').checked=true; applyFilters(); });
            if (brand)              pills += pill(`Brand: ${brand}`,           () => { document.querySelector('input[name="brand"][value=""]').checked=true; applyFilters(); });
            if (priceMin > 0)       pills += pill(`Min: LKR ${priceMin}`,      () => { document.getElementById('priceMin').value=''; applyFilters(); });
            if (priceMax < Infinity)pills += pill(`Max: LKR ${priceMax}`,      () => { document.getElementById('priceMax').value=''; applyFilters(); });

            bar.innerHTML = pills;
            bar.style.display = pills ? 'flex' : 'none';
        }

        function pill(label, onRemove) {
            const id = 'pill_' + Math.random().toString(36).slice(2);
            // Register cleanup on next tick
            setTimeout(() => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('click', onRemove);
            }, 0);
            return `<span class="filter-pill" id="${id}">${label} <i class="fas fa-times"></i></span>`;
        }

        function updateMobileFilterBadge({ query, category, brand, priceMin, priceMax }) {
            let count = 0;
            if (query) count++;
            if (category !== 'all') count++;
            if (brand) count++;
            if (priceMin > 0) count++;
            if (priceMax < Infinity) count++;
            const badge = document.getElementById('mobileFilterBadge');
            badge.textContent = count;
            badge.classList.toggle('visible', count > 0);
        }

        // ============================================================
        // LIVE SEARCH DROPDOWN
        // ============================================================
        let searchTimer;
        function handleLiveSearch(query) {
            clearTimeout(searchTimer);
            const dropdown = document.getElementById('searchDropdown');
            if (!query.trim()) { dropdown.style.display = 'none'; return; }

            searchTimer = setTimeout(() => {
                const results = globalProducts.filter(p =>
                    p.name.toLowerCase().includes(query.toLowerCase()) ||
                    (p.brand?.name?.toLowerCase().includes(query.toLowerCase()))
                ).slice(0, 6);

                if (results.length === 0) {
                    dropdown.innerHTML = '<div class="p-3 text-center text-muted" style="font-size:0.82rem;">No matches found</div>';
                } else {
                    dropdown.innerHTML = results.map(p => {
                        const img   = p.primary_thumbnail || 'assets/images/products/default.png';
                        const price = new Intl.NumberFormat('en-LK').format(p.pricing.current_price);
                        return `<div class="search-item" onclick="window.location.href='product-page.php?id=${p.id}'">
                            <img src="${img}" onerror="this.src='assets/images/products/default.png'" alt="${p.name}">
                            <div class="search-item-info">
                                <div class="search-item-name">${p.name}</div>
                                <div class="search-item-price">LKR ${price}</div>
                            </div>
                        </div>`;
                    }).join('');
                }
                dropdown.style.display = 'block';
            }, 280);
        }

        // Close dropdown on outside click
        document.addEventListener('click', e => {
            if (!e.target.closest('.search-container'))
                document.getElementById('searchDropdown').style.display = 'none';
        });

        // Enter key triggers filter
        document.getElementById('searchInput').addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('searchDropdown').style.display='none'; currentPage=1; applyFilters(); }
        });

        // ============================================================
        // COLLAPSIBLE FILTER SECTIONS
        // ============================================================
        function toggleSection(sectionId, titleEl) {
            const section = document.getElementById(sectionId);
            const isOpen = section.style.display !== 'none';
            section.style.display = isOpen ? 'none' : 'block';
            titleEl.classList.toggle('collapsed', isOpen);
        }

        // ============================================================
        // CART
        // ============================================================
        function quickAddToCart(id, name, price, img) {
            addToCart({ id, name, price: parseFloat(price), image: img, quantity: 1, options: { Type: 'Standard' } });
        }

        // ============================================================
        // SCROLL TO TOP BUTTON
        // ============================================================
        function initScrollTop() {
            const btn = document.getElementById('scrollTopBtn');
            window.addEventListener('scroll', () => {
                btn.classList.toggle('visible', window.scrollY > 400);
            });
        }
    </script>
</body>
</html>