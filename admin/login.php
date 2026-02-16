<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            min-height: 100vh;
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
        .btn-gold:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* --- Login Section --- */
        .login-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(10, 17, 31, 0.85), rgba(10, 17, 31, 0.95)), url('https://images.unsplash.com/photo-1612177344073-78e21244d543?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            padding: 2rem 0;
        }

        .login-card {
            background-color: rgba(44, 52, 64, 0.95);
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

        /* Alert Messages */
        .alert-custom {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            display: none;
        }
        .alert-custom.show {
            display: block;
        }
        .alert-error {
            background-color: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #ff6b6b;
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.5);
            color: #51cf66;
        }

        /* Loading Spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-card { padding: 2rem; width: 90%; }
        }
    </style>
</head>
<body>

    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    
                    <div class="login-card">
                        
                        <a href="PowerWatch_eCommerce.html" class="login-brand-logo">
                           <img src="../assets/images/brand-logos/logo5.png" alt="Logo" class="brand-logo-img">
                        </a>

                        <div class="text-center mb-4">
                            <h2 class="text-white mb-1 h4">Welcome Back</h2>
                            <p class="text-muted small">Please sign in to your account</p>
                        </div>

                        <div id="alertMessage" class="alert-custom"></div>

                        <form id="loginForm">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingEmail" placeholder="name@example.com" required>
                                <label for="floatingEmail">Email address</label>
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" style="padding-right: 45px;" required>
                                <label for="floatingPassword">Password</label>
                                <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility('floatingPassword', this)"></i>
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

                            <button type="submit" class="btn btn-gold mb-3" id="loginBtn">
                                <span id="btnText">Sign In</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                            </button>
                        </form>

                        <div class="divider">OR LOGIN WITH</div>

                        <div class="row g-2">
                            <div class="col-6">
                                <button class="social-login-btn">
                                    <i class="fab fa-google"></i> Google
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="social-login-btn">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </button>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text small mb-0">
                                Don't have an account? 
                                <a href="signup.php" class="text-gold fw-bold ms-2">Sign Up</a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Password Toggle Function (Reused)
        function togglePasswordVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            icon.classList.toggle('fa-eye-slash');
            icon.classList.toggle('fa-eye');
        }

        // Show Alert Message (Reused)
        function showAlert(message, type) {
            const alertBox = document.getElementById('alertMessage');
            alertBox.textContent = message;
            alertBox.className = 'alert-custom show alert-' + type;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertBox.classList.remove('show');
            }, 5000);
        }

        // Login Form Submission Handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form values
            const email = document.getElementById('floatingEmail').value.trim();
            const password = document.getElementById('floatingPassword').value;
            const rememberMe = document.getElementById('rememberMe').checked;

            // Client-side validation
            if (!email) {
                showAlert('Please enter your Email', 'error');
                return;
            }

            if (!password) {
                showAlert('Please enter your Password', 'error');
                return;
            }

            // Show loading state
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            
            loginBtn.disabled = true;
            btnText.textContent = 'Verifying...';
            btnSpinner.style.display = 'inline-block';

            // Create FormData object
            const formData = new FormData();
            formData.append('e', email);
            formData.append('p', password);
            formData.append('rm', rememberMe ? '1' : '0'); // Sending Remember Me status

            // Send AJAX request to verify-account.php
            fetch('actions/verify-account.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            // ... inside the fetch().then() block ...
            .then(data => {
                // Reset button state
                loginBtn.disabled = false;
                btnText.textContent = 'Sign In';
                btnSpinner.style.display = 'none';

                if (data.trim() === 'success') {
                    showAlert('Login successful! Redirecting...', 'success');
                    
                    // UPDATE: Redirect to dashboard.php
                    setTimeout(() => {
                        window.location.href = 'dashboard.php'; 
                    }, 1000);
                } else {
                    // Show error message from PHP
                    showAlert(data, 'error');
                }
            })
            .catch(error => {
                // Reset button state on network error
                loginBtn.disabled = false;
                btnText.textContent = 'Sign In';
                btnSpinner.style.display = 'none';

                showAlert('An error occurred connecting to the server.', 'error');
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>