<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Power Watch</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Palette Extraction (Consistent with Home/Checkout) */
            --prm-blue: #0A111F; /* Deep Navy */
            --chp-gold: #D4AF37; /* Gold */
            --chp-gold-hover: #b5952f;
            --btn-blue: #6F95E8; 
            --dark-card-bg: #2c3440; 
            --input-bg: #1a2332;
            --border-color: #2d3748;
            --text-light: #f8f9fa;
            --text-muted: #adb5bd;
        }

        .brand-logo-img {
            height: 44px;
            width: auto;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
            background-color: var(--prm-blue);
            color: var(--text-light);
            height: 100vh;
            margin: 0;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        /* --- Utilities --- */
        .text-gold { color: var(--chp-gold); }
        .bg-prm-blue { background-color: var(--prm-blue); }
        
        a { text-decoration: none; transition: 0.3s; color: var(--text-muted); }
        a:hover { color: var(--chp-gold); }

        .btn-gold {
            background-color: var(--chp-gold);
            color: #000;
            border: none;
            font-weight: 700;
            border-radius: 4px;
            padding: 12px 20px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            width: 100%;
            letter-spacing: 1px;
        }
        .btn-gold:hover {
            background-color: var(--chp-gold-hover);
            color: #000;
            transform: translateY(-1px);
        }

        /* --- Login Section --- */
        .login-section {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(10, 17, 31, 0.85), rgba(10, 17, 31, 0.95)), url('https://images.unsplash.com/photo-1612177344073-78e21244d543?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
        }

        .login-card {
            background-color: rgba(44, 52, 64, 0.95); /* Semi-transparent dark card */
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
        }

        .form-floating > .form-control {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
        }
        
        .form-floating > .form-control:focus {
            background-color: var(--input-bg);
            border-color: var(--chp-gold);
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.15);
        }
        
        .form-floating > label {
            color: #888;
        }
        
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--chp-gold);
            opacity: 1;
        }

        .social-login-btn {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: white;
            padding: 10px;
            border-radius: 4px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            margin-bottom: 10px;
        }
        .social-login-btn:hover {
            background-color: rgba(255,255,255,0.05);
            border-color: white;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }
        .divider::before { margin-right: .5em; }
        .divider::after { margin-left: .5em; }

        /* Brand Logo */
        .login-brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            text-decoration: none;
        }

        /* Password Toggle Icon */
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            z-index: 5;
        }
        .password-toggle:hover {
            color: var(--chp-gold);
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-card { padding: 2rem; width: 90%; }
        }
        /* --- Responsive Tweaks --- */
        @media (max-width: 768px) {
            .brand-logo-img { height: 32px; }
        }
    </style>
</head>
<body>

    <!-- Login Area -->
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    
                    <div class="login-card">
                        
                        <!-- Brand Header -->
                        <a href="PowerWatch_eCommerce.html" class="login-brand-logo">
                           <img src="../assets/images/brand-logos/logo5.png" alt="Logo" class="brand-logo-img">
                        </a>

                        <div class="text-center mb-4">
                            <h2 class="text-white mb-1 h4">Welcome Back</h2>
                            <p class="text-muted small">Please sign in to your account</p>
                        </div>

                        <form>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                                <label for="floatingInput">Email address</label>
                            </div>
                            <!-- Password Field with Toggle -->
                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" style="padding-right: 45px;">
                                <label for="floatingPassword">Password</label>
                                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" style="background-color: var(--input-bg); border-color: #555;">
                                    <label class="form-check-label text small" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>
                                <a href="#" class="small text-gold">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn btn-gold mb-3">Sign In</button>
                        </form>

                        <div class="divider">OR LOGIN WITH</div>

                        <div class="row">
                            <div class="col-12">
                                <button class="social-login-btn">
                                    <i class="fab fa-google"></i> Google
                                </button>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text small mb-0">
                                Don't have an account? 
                                <a href="#" class="text-gold fw-bold ms-2">Sign Up</a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Password Toggle Script -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#floatingPassword');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    </script>
</body>
</html>