<!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <!-- Logo image long version-->
                <img src="assets/images/brand-logos/logo5.png" alt="Logo" class="brand-logo-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="collection.php?category=Wristwatch" id="wristwatchDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Wristwatch <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="wristwatchDropdown">
                            <li><a class="dropdown-item" href="collection.php?category=Men's Watches">Men's Watches</a></li>
                            <li><a class="dropdown-item" href="collection.php?category=Women's Watches">Women's Watches</a></li>
                            <li><a class="dropdown-item" href="collection.php?category=Smart Watches">Smart Watches</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="collection.php?category=Premium Collection">Premium Collection</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="collection.php?category=Wall Decor" id="wallDecorDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Wall Decor <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="wallDecorDropdown">
                            <li><a class="dropdown-item" href="collection.php?category=Wall Clocks">Wall Clocks</a></li>
                            <li><a class="dropdown-item" href="collection.php?category=Photo Frames">Photo Frames</a></li>
                            <li><a class="dropdown-item" href="collection.php?category=Wall Art">Wall Art</a></li>
                            <li><a class="dropdown-item" href="collection.php?category=Mirrors">Mirrors</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="collection.php" id="brandsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Brands <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="brandsDropdown">
                            <li><a class="dropdown-item" href="collection.php?brand=Rolex">Rolex</a></li>
                            <li><a class="dropdown-item" href="collection.php?brand=Omega">Omega</a></li>
                            <li><a class="dropdown-item" href="collection.php?brand=Casio">Casio</a></li>
                            <li><a class="dropdown-item" href="collection.php?brand=Seiko">Seiko</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="collection.php">View All Brands</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Services <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            <li><a class="dropdown-item" href="watch-repair.php">Watch Repair</a></li>
                            <li><a class="dropdown-item" href="battery-replacement.php">Battery Replacement</a></li>
                            <li><a class="dropdown-item" href="custom-engraving.php">Custom Engraving</a></li>
                            <li><a class="dropdown-item" href="warranty-service.php">Warranty Service</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="nav-icons d-flex gap-2 align-items-center">
                    
                    <div class="nav-search-container" id="navSearchContainer">
                        <form action="collection.php" method="GET" class="d-flex align-items-center m-0">
                            <input type="text" name="search" id="navSearchInput" class="nav-search-input" placeholder="Search watches..." autocomplete="off">
                            <button type="submit" class="btn btn-link search-trigger-btn" aria-label="Search" id="navSearchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <div id="navSearchDropdown" class="nav-search-dropdown shadow d-none"></div>
                    </div>
                    
                    <button class="btn btn-link position-relative" aria-label="Shopping Cart" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="navHeaderCount" class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                            <span class="visually-hidden">items in cart</span>
                        </span>
                    </button>
                    <!-- <button class="display-none btn btn-link" aria-label="User Account">
                        <i class="fas fa-user"></i>
                    </button> -->
                </div>
            </div>
        </div>
    </nav>
<script>
    // ========== NAVBAR SEARCH ENGINE ==========
        const navSearchContainer = document.getElementById('navSearchContainer');
        const navSearchInput = document.getElementById('navSearchInput');
        const navSearchBtn = document.getElementById('navSearchBtn');
        const navSearchDropdown = document.getElementById('navSearchDropdown');
        let navSearchTimeout;

        // --- NEW: Dynamic Mobile Positioning Engine ---
        function updateSearchDropdownPosition() {
            // Only apply the dynamic math if we are on mobile AND the dropdown is open
            if (window.innerWidth <= 991 && !navSearchDropdown.classList.contains('d-none')) {
                // getBoundingClientRect calculates the exact real-time position on the screen
                const searchBoxPos = navSearchContainer.getBoundingClientRect();
                navSearchDropdown.style.top = `${searchBoxPos.bottom + 5}px`; // 5px gap
            } else {
                navSearchDropdown.style.top = ''; // Let CSS handle desktop layout naturally
            }
        }

        // Automatically track the search bar when the user scrolls
        window.addEventListener('scroll', updateSearchDropdownPosition, { passive: true });

        // Toggle animation & form submission protection
        navSearchBtn.addEventListener('click', (e) => {
            if (!navSearchContainer.classList.contains('active')) {
                e.preventDefault(); // Stop form from submitting immediately
                navSearchContainer.classList.add('active');
                navSearchInput.focus();
            } else if (navSearchInput.value.trim() === '') {
                e.preventDefault(); // If empty, just close the bar
                navSearchContainer.classList.remove('active');
                navSearchDropdown.classList.add('d-none');
            }
            // If it IS active and HAS text, let the form submit naturally to collection.php
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#navSearchContainer')) {
                navSearchContainer.classList.remove('active');
                navSearchDropdown.classList.add('d-none');
            }
        });

        // Live Filtering
        navSearchInput.addEventListener('input', (e) => {
            clearTimeout(navSearchTimeout);
            const query = e.target.value.toLowerCase().trim();

            if (query.length < 2) {
                navSearchDropdown.classList.add('d-none');
                return;
            }

            navSearchTimeout = setTimeout(() => {
                // Search by Name, Brand, or Category
                const results = globalProducts.filter(p => 
                    p.name.toLowerCase().includes(query) || 
                    (p.brand && p.brand.name.toLowerCase().includes(query)) ||
                    (p.category && p.category.toLowerCase().includes(query))
                ).slice(0, 5); // Show top 5 results for clean UI

                if (results.length === 0) {
                    navSearchDropdown.innerHTML = `
                        <div class="p-4 text-center text-muted small">
                            <i class="fas fa-search d-block mb-2 fs-4 opacity-50"></i>
                            No products found for "${query}"
                        </div>`;
                } else {
                    let html = results.map(p => {
                        const img = p.primary_thumbnail ? p.primary_thumbnail : 'assets/images/products/default.png';
                        const price = new Intl.NumberFormat('en-LK', { style: 'currency', currency: 'LKR', minimumFractionDigits: 0 }).format(p.pricing.current_price);
                        
                        // UI/UX Standard: Specific items go to the product page
                        return `
                            <a href="product-page.php?id=${p.id}" class="nav-search-item">
                                <img src="${img}" alt="Product">
                                <div style="min-width: 0;">
                                    <span class="nav-search-title">${p.name}</span>
                                    <span class="nav-search-price">${price}</span>
                                </div>
                            </a>
                        `;
                    }).join('');
                    
                    // UI/UX Standard: "View All" goes to collection.php to show the full grid
                    html += `
                        <a href="collection.php?search=${encodeURIComponent(query)}" class="d-block p-3 text-center text-gold small fw-bold text-decoration-none" style="background: rgba(0,0,0,0.2);">
                            View all results for "${query}" <i class="fas fa-arrow-right ms-1"></i>
                        </a>`;
                    
                    navSearchDropdown.innerHTML = html;
                }
                navSearchDropdown.classList.remove('d-none');

                // Trigger the calculation the moment results appear!
                updateSearchDropdownPosition();
            }, 300); // 300ms debounce for performance
        });
</script>