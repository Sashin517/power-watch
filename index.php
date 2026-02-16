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

    <style>
        :root {
            /* Palette Extraction */
            --prm-blue: #0A111F; /* Deep Navy from image */
            --chp-gold: #D4AF37; /* Gold from palette */
            --chp-gold-hover: #b5952f;
            --fd-blue: #D9D9D9; /* Light grey/silver */
            --btn-blue: #6F95E8; /* Periwinkle blue button */
            --btn-brown: #8B887F; /* Saddle Brown for "People's Choice" buttons */
            --cream-bg: #F9EDC9; /* Luxury collection bg approximation */
            --dark-grey: #394150;
            --text-light: #f8f9fa;
            --border-color: #2d3748;
            --input-bg: #1a2332;
            --discount-green: #08CB00;
            --card-bg: #394150;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
            background-color: #f4f4f4;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        /* --- Utilities --- */
        .text-gold { color: var(--chp-gold); }
        .bg-prm-blue { background-color: var(--prm-blue); }
        .bg-gold { background-color: var(--chp-gold); }
        .bg-cream { background-color: var(--cream-bg); }

        .btn-gold {
            background-color: var(--chp-gold);
            color: #000;
            border: none;
            font-weight: 600;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background-color: var(--chp-gold-hover);
            color: #000;
        }

        .btn-blue {
            background-color: var(--btn-blue);
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .btn-blue:hover {
            background-color: #5b7dc4;
            color: white;
        }

        .btn-brown {
            background-color: var(--btn-brown);
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .btn-brown:hover {
            background-color: #787469;
            color: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 2.5rem;
            font-weight: 500;
            letter-spacing: 1px;
        }

        /* --- Top Bar --- */
        .top-bar {
            background-color: #C8A030;
            color: #000;
            padding: 8px 0;
            overflow: hidden;
        }
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            display: flex;
        }

        .marquee-content {
            display: flex;
            animation: marquee 20s linear infinite;
            flex-shrink: 0;
        }

        .marquee-content span {
            padding: 0 50px;
            flex-shrink: 0;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        /* Pause animation on hover (optional) */
        .marquee-container:hover .marquee-content {
            animation-play-state: paused;
        }
        /* --- Navbar --- */
        .navbar {
            background-color: var(--prm-blue);
            padding: 1rem 0;
        }
        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-logo-img {
            height: 40px;
            width: auto;
        }
        .footer-brand-logo-img{
            height: 68px;
            width: auto;
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            margin: 0 10px;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        .nav-link:hover {
            color: var(--chp-gold) !important;
        }
        
        /* Dropdown specific styles */
        .nav-link.dropdown-toggle::after {
            display: none; /* Hide default Bootstrap arrow */
        }
        
        .nav-link i.fa-chevron-down {
            font-size: 0.7rem;
            transition: transform 0.3s ease;
        }
        
        .nav-link[aria-expanded="true"] i.fa-chevron-down {
            transform: rotate(180deg);
        }
        
        .dropdown-menu {
            background-color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
            margin-top: 0.5rem;
            padding: 0.5rem 0;
            min-width: 200px;
        }
        
        .dropdown-item {
            padding: 0.6rem 1.5rem;
            color: #333;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--prm-blue);
            color: white;
            padding-left: 2rem;
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(0,0,0,0.1);
        }
        
        /* Mobile dropdown styles */
        @media (max-width: 991px) {
            .dropdown-menu {
                background-color: rgba(255,255,255,0.1);
                border-left: 3px solid var(--chp-gold);
                margin-left: 1rem;
                box-shadow: none;
            }
            
            .dropdown-item {
                color: rgba(255,255,255,0.8);
                font-size: 0.85rem;
            }
            
            .dropdown-item:hover {
                background-color: rgba(255,255,255,0.1);
                color: var(--chp-gold);
                padding-left: 1.5rem;
            }
            
            .nav-link {
                padding: 0.5rem 0;
            }
        }
        
        .nav-icons .btn {
            color: white;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .nav-icons .btn:hover {
            color: var(--chp-gold);
            transform: scale(1.1);
        }
        
        /* Cart badge */
        .cart-badge {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
        }
        
        /* Navbar toggler animation */
        .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
            transition: all 0.3s ease;
        }
        
        .navbar-toggler:hover {
            border-color: var(--chp-gold);
        }
        
        .navbar-toggler:hover i {
            color: var(--chp-gold) !important;
        }

        /* --- Hero Section --- */
        .hero-section {
            position: relative;
            height: 600px;
            background-color: #000;
            color: white;
            overflow: hidden;
        }

        .hero-img {
            object-fit: cover;
            width: 100%;
            height: 100%;
            opacity: 0.8;
            object-position: center;
        }

        .hero-overlay {
            position: absolute;
            top: 50%;
            left: 10%;
            transform: translateY(-50%);
            z-index: 2;
            max-width: 600px;
            padding: 0 15px;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            font-weight: 300;
            opacity: 0.9;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
        }

        .hero-btn {
            padding: 12px 30px;
            font-size: 1rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .hero-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        /* Carousel controls enhancement */
        .carousel-control-prev,
        .carousel-control-next {
            width: 60px;
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-size: 30px;
            width: 50px;
            height: 50px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
        }

        .carousel-indicators {
            margin-bottom: 2rem;
        }

        .carousel-indicators [data-bs-target] {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 6px;
            border: 2px solid white;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .carousel-indicators .active {
            opacity: 1;
            background-color: var(--chp-gold);
            border-color: var(--chp-gold);
        }

        /* ===== TABLET RESPONSIVE (768px - 991px) ===== */
        @media (max-width: 991px) {
            .hero-section {
                height: 500px;
            }
            
            .hero-overlay {
                left: 5%;
                max-width: 500px;
            }
            
            .hero-title {
                font-size: 3rem;
                margin-bottom: 0.8rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
                margin-bottom: 1.2rem;
            }
            
            .hero-btn {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
            
            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                width: 40px;
                height: 40px;
                background-size: 25px;
            }
        }

        /* ===== MOBILE LANDSCAPE (576px - 767px) ===== */
        @media (max-width: 767px) {
            .hero-section {
                height: 450px;
            }
            
            .hero-overlay {
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
                max-width: 90%;
                width: 100%;
            }
            
            .hero-title {
                font-size: 2.5rem;
                margin-bottom: 0.8rem;
            }
            
            .hero-btn {
                padding: 10px 20px;
                font-size: 0.85rem;
                white-space: nowrap;
            }
            
            .carousel-indicators {
                margin-bottom: 1.5rem;
            }
        }

        /* ===== MOBILE PORTRAIT (up to 575px) ===== */
        @media (max-width: 575px) {
            .hero-section {
                height: 400px;
            }
            
            .hero-img {
                object-position: 70% center; /* Adjust to show important parts of image */
            }
            
            .hero-overlay {
                padding: 0 20px;
            }
            
            .hero-title {
                font-size: 2rem;
                line-height: 1.2;
                margin-bottom: 1rem;
            }
            
            .hero-btn {
                padding: 10px 18px;
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                display: inline-block;
            }
            
            /* Make controls smaller on mobile */
            .carousel-control-prev,
            .carousel-control-next {
                width: 40px;
            }
            
            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                width: 30px;
                height: 30px;
                background-size: 18px;
            }
            
            .carousel-indicators [data-bs-target] {
                width: 8px;
                height: 8px;
                margin: 0 4px;
            }
            
            .carousel-indicators {
                margin-bottom: 1rem;
            }
        }

        /* ===== EXTRA SMALL DEVICES (up to 375px) ===== */
        @media (max-width: 375px) {
            .hero-section {
                height: 350px;
            }
            
            .hero-title {
                font-size: 1.75rem;
            }
            
            .hero-btn {
                padding: 8px 15px;
                font-size: 0.7rem;
            }
        }

        /* ===== LARGE DESKTOP (1400px+) ===== */
        @media (min-width: 1400px) {
            .hero-section {
                height: 700px;
            }
            
            .hero-title {
                font-size: 4.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.4rem;
            }
            
            .hero-btn {
                padding: 14px 35px;
                font-size: 1.1rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .hero-btn:active {
                transform: scale(0.95);
            }
            
            .carousel-control-prev:active,
            .carousel-control-next:active {
                opacity: 0.5;
            }
        }

        /* Accessibility: Reduce motion for users who prefer it */
        @media (prefers-reduced-motion: reduce) {
            .carousel-item {
                transition: none;
            }
            
            .hero-btn,
            .carousel-control-prev,
            .carousel-control-next {
                transition: none;
            }
        }

        /* --- Brand Logos --- */
        .brand-grid {
            background-color: var(--prm-blue);
            padding: 3rem 0;
        }
        
        .brand-item {
            background-color: var(--fd-blue);
            border-radius: 6px;
            /* 1. INCREASED HEIGHT */
            height: 64px; 
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            transition: transform 0.2s;
            /* 2. ADDED PADDING so logos don't touch the edges */
            padding: 12px;
        }

        .brand-item:hover {
            transform: translateY(-3px);
        }

        /* 3. NEW RULE: Force images to fit the container height */
        .brand-item img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Keeps aspect ratio while filling height */
            display: block;
        }
        
        /* --- Product Cards --- */

        /* ===== EQUAL HEIGHT CAROUSEL ITEMS ===== */

        /* Make carousel and all nested containers full height */
        #luxuryCarousel,
        #luxuryCarousel .carousel-inner,
        #luxuryCarousel .carousel-item {
            height: auto;
        }

        /* Force equal height for all rows in carousel */
        #luxuryCarousel .row {
            display: flex;
            flex-wrap: wrap;
        }

        /* Make all columns equal height */
        #luxuryCarousel .row > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }

        /* People's choice */
        /* Make carousel and all nested containers full height */
        #peoplesChoiceCarousel,
        #peoplesChoiceCarousel .carousel-inner,
        #peoplesChoiceCarousel .carousel-item {
            height: auto;
        }

        /* Force equal height for all rows in carousel */
        #peoplesChoiceCarousel .row {
            display: flex;
            flex-wrap: wrap;
        }

        /* Make all columns equal height */
        #peoplesChoiceCarousel .row > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }

        /* --- Product Cards (COMPACT VERSION) --- */
        .product-card {
            background: white;
            border: none;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.12);
        }

        .card-img-wrapper {
            position: relative;
            padding: 15px;
            text-align: center;
            background: #fff;
            height: 180px;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border-light);
            flex-shrink: 0;
        }

        .discount-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: linear-gradient(135deg, var(--discount-green) 0%, #2bc04e 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.7rem;
            box-shadow: 0 2px 6px rgba(60, 231, 74, 0.3);
            z-index: 10;
            letter-spacing: 0.3px;
        }

        .product-img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .card-body {
            padding: 1.25rem;
            background-color: var(--card-bg);
            color: white;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-title {
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            line-height: 1.4;
            font-family: 'Montserrat', sans-serif;
            min-height: 34px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price-section {
            margin-bottom: 0.5rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-container {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .original-price {
            font-size: 0.8rem;
            color: var(--fd-blue);
            text-decoration: line-through;
            font-weight: 500;
        }

        .price {
            font-weight: 700;
            font-size: 1rem;
            color: var(--chp-gold);
        }

        .eye-icon {
            background: rgba(255,255,255,0.15);
            padding: 5px 6px;
            border-radius: 4px;
            color: var(--chp-gold);
            transition: all 0.3s;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .eye-icon:hover {
            background: rgba(255,255,255,0.25);
            transform: scale(1.1);
        }

        .installment-text {
            font-size: 0.75rem;
            color: #bbb;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .koko-brand {
            font-weight: 700;
            color: #7191D9;
        }

        .btn-card {
            width: 100%;
            margin-top: auto;
            text-transform: uppercase;
            font-size: 0.7rem;
            padding: 8px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
        }

        .btn-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        /* ===== TABLET RESPONSIVE (768px - 991px) ===== */
        @media (max-width: 991px) {
            .card-img-wrapper {
                height: 170px;
                min-height: 170px;
                padding: 12px;
            }
            
            .card-body {
                padding: 1.1rem;
            }
            
            .card-title {
                font-size: 0.75rem;
                min-height: 32px;
            }
            
            .price {
                font-size: 0.95rem;
            }
            
            .btn-card {
                padding: 7px;
                font-size: 0.68rem;
            }
        }

        /* ===== MOBILE LANDSCAPE (576px - 767px) ===== */
        @media (max-width: 767px) {
            .card-img-wrapper {
                height: 160px;
                min-height: 160px;
                padding: 10px;
            }
            
            .discount-badge {
                top: 6px;
                left: 6px;
                padding: 3px 7px;
                font-size: 0.65rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .card-title {
                font-size: 0.72rem;
                min-height: 30px;
                margin-bottom: 0.4rem;
            }
            
            .price-section {
                margin-bottom: 0.4rem;
            }
            
            .original-price {
                font-size: 0.65rem;
            }
            
            .price {
                font-size: 0.9rem;
            }
            
            .eye-icon {
                padding: 4px 5px;
                font-size: 0.8rem;
            }
            
            .installment-text {
                font-size: 0.69231rem;
                margin-bottom: 0.6rem;
            }
            
            .btn-card {
                padding: 7px;
                font-size: 0.65rem;
            }
        }

        /* ===== MOBILE PORTRAIT (up to 575px) ===== */
        @media (max-width: 575px) {
            .product-card {
                border-radius: 6px;
            }
            
            .card-img-wrapper {
                height: 140px;
                min-height: 140px;
                padding: 8px;
            }
            
            .discount-badge {
                top: 5px;
                left: 5px;
                padding: 3px 6px;
                font-size: 0.6rem;
            }
            
            .card-body {
                padding: 0.85rem;
            }
            
            .card-title {
                font-size: 0.68rem;
                min-height: 28px;
                line-height: 1.3;
                margin-bottom: 0.4rem;
            }
            
            .price-section {
                margin-bottom: 0.4rem;
            }
            
            .original-price {
                font-size: 0.6rem;
            }
            
            .price {
                font-size: 0.85rem;
            }
            
            .eye-icon {
                padding: 3px 4px;
                font-size: 0.75rem;
            }
            
            .installment-text {
                font-size: 0.6346175rem;
                margin-bottom: 0.6rem;
                line-height: 1.2;
            }
            
            .btn-card {
                padding: 6px;
                font-size: 0.6rem;
                letter-spacing: 0.2px;
            }
            
            /* Disable hover effects on mobile for better performance */
            .product-card:hover {
                transform: none;
            }
        }

        /* ===== EXTRA SMALL DEVICES (up to 375px) ===== */
        @media (max-width: 375px) {
            .card-img-wrapper {
                height: 120px;
                min-height: 120px;
                padding: 6px;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .card-title {
                font-size: 0.65rem;
                min-height: 26px;
            }
            
            .price {
                font-size: 0.8rem;
            }
            
            .installment-text {
                font-size: 0.52rem;
            }
            
            .btn-card {
                padding: 5px;
                font-size: 0.58rem;
            }
        }

        /* ===== LARGE DESKTOP (1400px+) ===== */
        @media (min-width: 1400px) {
            .card-img-wrapper {
                height: 200px;
                min-height: 200px;
                padding: 18px;
            }
            
            .card-body {
                padding: 1.4rem;
            }
            
            .card-title {
                font-size: 0.85rem;
                min-height: 36px;
            }
            
            .price {
                font-size: 1.1rem;
            }
            
            .btn-card {
                padding: 9px;
                font-size: 0.75rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .product-card:active {
                transform: scale(0.98);
            }
            
            .btn-card:active {
                transform: scale(0.95);
            }
            
            .eye-icon:active {
                transform: scale(0.9);
            }
        }

        /* Carousel controls for product sections */
        .carousel-control-prev,
        .carousel-control-next {
            width: 45px;
            opacity: 0.6;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
        }

        @media (max-width: 767px) {
            .carousel-control-prev,
            .carousel-control-next {
                width: 35px;
            }
            
            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                width: 22px;
                height: 22px;
            }
        }
        /* --- Banner Sections --- */
        .banner-section {
            position: relative;
            height: 400px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
        }
        .banner-overlay {
            background: rgba(0,0,0,0.5);
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
        }
        .banner-content {
            position: relative;
            z-index: 2;
            color: white;
            padding: 2rem;
        }
        .banner-street {
            background-image: url('assets/images/home/body-banners/bdy-bnr-img-3.png');
        }
        .banner-gold-watch {
            background-image: url('assets/images/home/body-banners/bdy-bnr-img-1.png');
        }
        .banner-wall-clock {
            background-image: url('assets/images/home/body-banners/bdy-bnr-img-2.png');
        }

        /* --- Carousel Controls --- */
        .carousel-control-prev, .carousel-control-next {
            width: 5%;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .carousel:hover .carousel-control-prev, 
        .carousel:hover .carousel-control-next {
            opacity: 1;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: rgba(0,0,0,0.5);
            border-radius: 50%;
            padding: 20px;
            background-size: 50% 50%;
        }

        /* --- Footer --- */
        footer {
            background-color: #000;
            color: #aaa;
            padding: 4rem 0 2rem;
            font-size: 0.9rem;
        }
        footer h5 {
            color: white;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }
        footer ul {
            list-style: none;
            padding: 0;
        }
        footer ul li {
            margin-bottom: 10px;
        }
        footer a {
            color: #aaa;
            text-decoration: none;
            transition: color 0.3s;
        }
        footer a:hover {
            color: var(--chp-gold);
        }
        .footer-bottom {
            border-top: 1px solid #333;
            margin-top: 3rem;
            padding-top: 1.5rem;
        }
        .social-icons a {
            font-size: 1.2rem;
            margin-right: 15px;
        }

        /* --- Responsive Tweaks --- */
        @media (max-width: 768px) {
            .brand-logo-img { height: 32px; }
            .footer-brand-logo-img { height: 54px; }
            .hero-title { font-size: 2.5rem; }
            .section-title { font-size: 1.5rem; }
            .brand-item { margin-bottom: 10px; }
            .banner-street {
                background-image: url('assets/images/home/body-banners/bdy-bnr-img-3-mobile.png');
            }
            .banner-gold-watch {
                background-image: url('assets/images/home/body-banners/bdy-bnr-img-1-mobile.png');
            }
            .banner-wall-clock {
                background-image: url('assets/images/home/body-banners/bdy-bnr-img-2-mobile.png');
            }
        }

        /* Multi-item carousel hack for pure bootstrap */
        @media (min-width: 992px) {
            .carousel-inner.multi-item .carousel-item {
                display: block; /* Force block to allow flex children logic if needed, but we will use groups */
            }
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
                <div class="nav-icons d-flex gap-2">
                    <button class="btn btn-link" aria-label="Search">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-link position-relative" aria-label="Shopping Cart">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                            <span class="visually-hidden">items in cart</span>
                        </span>
                    </button>
                    <button class="btn btn-link" aria-label="User Account">
                        <i class="fas fa-user"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

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
                        <img src="assets/images/home/hero-section-banners/hr-sec-img-2.png" alt="Luxury Watch" class="hero-img d-block w-100">
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

    <!-- The Luxury Collection -->
    <section class="py-5" style="background-color: var(--cream-bg);">
        <div class="container">
            <h2 class="section-title text-uppercase">The Premium Collection</h2>
            
            <div id="luxuryCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    
                    <!-- Slide 1 (Group of 4) -->
                    <div class="carousel-item active">
                        <div class="row g-3">
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial Metal Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Silver Dial Metal Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 4,833.33 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Black Dial Metal Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 6,000.00 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Gold Dial Metal Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 7,333.33 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Gold Dial Metal Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 7,333.33 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Gold Dial Metal Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 7,333.33 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="row g-3">
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-20% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1622434641406-a15810545064?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Sport Edition Chronograph</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 20,000</span>
                                                    <span class="price">LKR 16,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 5,333.33 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-10% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1587836374615-91d3b6c88a9c?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Casio Edifice Premium Steel Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 22,500</span>
                                                    <span class="price">LKR 20,250</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 6,750.00 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-25% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Seiko Automatic Diver's Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 30,000</span>
                                                    <span class="price">LKR 22,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 7,500.00 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Fossil Minimalist Rose Gold Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 17,000</span>
                                                    <span class="price">LKR 14,450</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 4,816.67 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-gold btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#luxuryCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#luxuryCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
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
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row g-4">
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Silver Mesh Band Analog Watch</h5>
                                        <div class="price-row">
                                            <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                            </div>
                                            <i class="fa-solid fa-eye eye-icon"></i>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Gold Plated Classic Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1434056838489-293029c62689?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Classic Leather Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row g-4">
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Silver Mesh Band Analog Watch</h5>
                                        <div class="price-row">
                                            <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                            </div>
                                            <i class="fa-solid fa-eye eye-icon"></i>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Gold Plated Classic Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1434056838489-293029c62689?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Classic Leather Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#peoplesChoiceCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#peoplesChoiceCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
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
            <div class="d-flex justify-content-center gap-2 mb-4">
                <button class="btn btn-light rounded-pill px-4">Casio</button>
                <button class="btn btn-outline-light rounded-pill px-4">Titan</button>
                <button class="btn btn-outline-light rounded-pill px-4">Casio</button>
            </div>

            <!-- Carousel Grid (similar to Luxury Collection) -->
            <div id="favoriteBrandsCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row g-3">
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row g-3">
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-blue btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#favoriteBrandsCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#favoriteBrandsCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>

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
                <p class="fs-5">Not just a clock, it can decorate you whole time</p>
            </div>
        </div>
    </section>

    <!-- Elevate Your Style (Wall Clocks) -->
    <section class="py-5" style="background-color: var(--prm-blue); color: white;">
        <div class="container">
            <h2 class="section-title text-uppercase text-white">Elevate Your Style</h2>
            
            <div id="wallClockCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row g-4">
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Silver Mesh Band Analog Watch</h5>
                                        <div class="price-row">
                                            <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                            </div>
                                            <i class="fa-solid fa-eye eye-icon"></i>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Gold Plated Classic Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1434056838489-293029c62689?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Classic Leather Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row g-4">
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Silver Mesh Band Analog Watch</h5>
                                        <div class="price-row">
                                            <div class="price-container">
                                                    <span class="original-price">LKR 14,120</span>
                                                    <span class="price">LKR 12,000</span>
                                            </div>
                                            <i class="fa-solid fa-eye eye-icon"></i>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Gold Plated Classic Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1434056838489-293029c62689?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Classic Leather Strap Watch</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="card-img-wrapper">
                                        <div class="discount-badge">-15% OFF</div>
                                        <img src="https://images.unsplash.com/photo-1614164185128-e4899ea2b789?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Watch">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Titan Quartz Analog Blue Dial</h5>
                                        <div class="price-section">
                                            <div class="price-row">
                                                <div class="price-container">
                                                    <span class="original-price">LKR 18,125</span>
                                                    <span class="price">LKR 14,500</span>
                                                </div>
                                                <i class="fa-solid fa-eye eye-icon"></i>
                                            </div>
                                        </div>
                                        <p class="installment-text">or pay in 3 x Rs 3,316.66 with <span class="koko-brand">KOKO</span></p>
                                        <button class="btn btn-brown btn-card">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#wallClockCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#wallClockCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
             <!-- Dots indicator -->
             <div class="text-center mt-3">
                 <span style="display:inline-block; width:8px; height:8px; background:white; border-radius:50%; margin:0 3px;"></span>
                 <span style="display:inline-block; width:8px; height:8px; background:#666; border-radius:50%; margin:0 3px;"></span>
                 <span style="display:inline-block; width:8px; height:8px; background:#666; border-radius:50%; margin:0 3px;"></span>
                 <span style="display:inline-block; width:8px; height:8px; background:#666; border-radius:50%; margin:0 3px;"></span>
             </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4">
                     <a class="navbar-brand mb-3" href="#">
                        <!-- Logo mock -->
                        <img src="assets/images/brand-logos/logo4.png" alt="Logo" class="footer-brand-logo-img">
                    </a>
                </div>
                <div class="col-md-2 col-6">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#">Men's</a></li>
                        <li><a href="#">Women's</a></li>
                        <li><a href="#">About Us</a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-6">
                    <h5>Customer Care</h5>
                    <ul>
                        <li><a href="#">Warranty Info</a></li>
                        <li><a href="#">Returns Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Panadura Address</h5>
                    <p>No. 123, Main Street, Panadura, Sri Lanka.</p>
                </div>
            </div>
            
            <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center">
                <p class="mb-0">&copy; 2026 Power Watch Panadura. All rights reserved.</p>
                <div class="social-icons my-3 my-md-0">
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
                <div class="payment-methods">
                     <span class="text-white me-2">We Accept:</span>
                     <i class="fab fa-cc-mastercard fa-lg text-light me-2"></i>
                     <i class="fab fa-cc-visa fa-lg text-light me-2"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>