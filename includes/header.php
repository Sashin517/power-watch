<!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <!-- Logo image long version-->
                <img src="assets/images/brand-logos/logo5.png" alt="Logo" class="brand-logo-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="wristwatchDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Wristwatch <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="wristwatchDropdown">
                            <li><a class="dropdown-item" href="#">Men's Watches</a></li>
                            <li><a class="dropdown-item" href="#">Women's Watches</a></li>
                            <li><a class="dropdown-item" href="#">Smart Watches</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Luxury Collection</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="wallDecorDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Wall Decor <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="wallDecorDropdown">
                            <li><a class="dropdown-item" href="#">Wall Clocks</a></li>
                            <li><a class="dropdown-item" href="#">Photo Frames</a></li>
                            <li><a class="dropdown-item" href="#">Wall Art</a></li>
                            <li><a class="dropdown-item" href="#">Mirrors</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="brandsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Brands <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="brandsDropdown">
                            <li><a class="dropdown-item" href="#">Rolex</a></li>
                            <li><a class="dropdown-item" href="#">Omega</a></li>
                            <li><a class="dropdown-item" href="#">Casio</a></li>
                            <li><a class="dropdown-item" href="#">Seiko</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">View All Brands</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Services <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            <li><a class="dropdown-item" href="#">Watch Repair</a></li>
                            <li><a class="dropdown-item" href="#">Battery Replacement</a></li>
                            <li><a class="dropdown-item" href="#">Custom Engraving</a></li>
                            <li><a class="dropdown-item" href="#">Warranty Service</a></li>
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
                        <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
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