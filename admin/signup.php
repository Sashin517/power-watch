<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://accounts.google.com/gsi/client" async defer></script>

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

        /* --- Sign Up Section --- */
        .signup-section {
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
            max-width: 500px;
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

        /* Form validation feedback */
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .form-control.is-valid {
            border-color: #28a745;
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
            cursor: pointer;
            position: relative; /* For Google overlay */
        }
        .social-login-btn:hover {
            background-color: rgba(255,255,255,0.05);
            border-color: white;
        }

        /* Div to hold the actual Google button invisibly on top of our custom button */
        #g_id_onload {
            display: none;
        }
        
        /* Custom wrapper for Google button to ensure clickability */
        .google-btn-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
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

    <section class="signup-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    
                    <div class="login-card">
                        
                        <a href="PowerWatch_eCommerce.html" class="login-brand-logo">
                           <img src="../assets/images/brand-logos/logo5.png" alt="Logo" class="brand-logo-img">
                        </a>

                        <div class="text-center mb-4">
                            <h2 class="text-white mb-1 h4">Create Account</h2>
                            <p class="text-muted small">Join Power Watch for exclusive offers</p>
                        </div>

                        <div id="alertMessage" class="alert-custom"></div>

                        <form id="signupForm">
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="firstName" placeholder="First Name" required>
                                        <label for="firstName">First Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="lastName" placeholder="Last Name" required>
                                        <label for="lastName">Last Name</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingEmail" placeholder="name@example.com" required>
                                <label for="floatingEmail">Email address</label>
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" style="padding-right: 45px;" required>
                                <label for="floatingPassword">Password</label>
                                <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility('floatingPassword', this)"></i>
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" style="padding-right: 45px;" required>
                                <label for="confirmPassword">Confirm Password</label>
                                <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility('confirmPassword', this)"></i>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="termsCheck" style="background-color: var(--input-bg); border-color: #555;" required>
                                    <label class="form-check-label text small" for="termsCheck">
                                        I agree to the <a href="#" class="text-gold">Terms of Service</a> and <a href="#" class="text-gold">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gold mb-3" id="signupBtn">
                                <span id="btnText">Sign Up</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                            </button>
                        </form>

                        <div class="divider">OR SIGN UP WITH</div>

                        <div class="row g-2">
                            <div class="col-6">
                                <div id="googleButtonWrapper" class="google-btn-wrapper">
                                    <button class="social-login-btn" id="customGoogleBtn">
                                        <i class="fab fa-google"></i> Google
                                    </button>
                                </div>
                            </div>
                            <div class="col-6">
                                <button class="social-login-btn">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </button>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text small mb-0">
                                Already have an account? 
                                <a href="login.php" class="text-gold fw-bold ms-2">Sign In</a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // --- GOOGLE SIGN UP INTEGRATION ---
        
        // 1. Initialize Google Identity Services
        window.onload = function () {
            google.accounts.id.initialize({
                // REPLACE THIS WITH YOUR ACTUAL GOOGLE CLIENT ID
                client_id: "732695935170-8s2r15mbnobiek7rd2e7j8d7i99hnjc5.apps.googleusercontent.com", 
                callback: handleGoogleResponse
            });

            // 2. Attach click event to custom button to prompt Google login
            const googleBtn = document.getElementById("customGoogleBtn");
            googleBtn.onclick = function() {
                google.accounts.id.prompt(); // Show One Tap or Prompt
            }
        };

        // 3. Handle the response from Google
        function handleGoogleResponse(response) {
            // Decode the JWT (JSON Web Token) credential
            const responsePayload = decodeJwtResponse(response.credential);

            console.log("ID: " + responsePayload.sub);
            console.log('Full Name: ' + responsePayload.name);
            console.log('Given Name: ' + responsePayload.given_name);
            console.log('Family Name: ' + responsePayload.family_name);
            console.log("Image URL: " + responsePayload.picture);
            console.log("Email: " + responsePayload.email);

            // 4. Prepare Data for PHP Backend
            // Since your PHP requires a password, we generate a random secure one.
            // Note: The user won't know this password. They should use Google Login 
            // or use "Forgot Password" later if you implement it.
            const randomPassword = "GGL_" + Math.random().toString(36).slice(-10) + "Pw!";

            const formData = new FormData();
            formData.append('f', responsePayload.given_name);
            formData.append('l', responsePayload.family_name);
            formData.append('e', responsePayload.email);
            formData.append('p', randomPassword);

            // Show UI feedback
            const signupBtn = document.getElementById('signupBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            
            signupBtn.disabled = true;
            btnText.textContent = 'Verifying Google Account...';
            btnSpinner.style.display = 'inline-block';

            // 5. Send to your existing PHP script
            fetch('actions/create-account.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                signupBtn.disabled = false;
                btnText.textContent = 'Sign Up';
                btnSpinner.style.display = 'none';

                if (data.trim() === 'success') {
                    showAlert('Google Sign Up Successful! Redirecting...', 'success');
                    
                    // Redirect to login or dashboard
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    // This might happen if the email is already registered
                    showAlert(data, 'error');
                }
            })
            .catch(error => {
                signupBtn.disabled = false;
                btnText.textContent = 'Sign Up';
                btnSpinner.style.display = 'none';
                showAlert('Error connecting to server.', 'error');
            });
        }

        // Helper function to decode JWT
        function decodeJwtResponse(token) {
            var base64Url = token.split('.')[1];
            var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));

            return JSON.parse(jsonPayload);
        }

        // --- END GOOGLE INTEGRATION ---


        // Password Toggle Function
        function togglePasswordVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            icon.classList.toggle('fa-eye-slash');
            icon.classList.toggle('fa-eye');
        }

        // Show Alert Message
        function showAlert(message, type) {
            const alertBox = document.getElementById('alertMessage');
            alertBox.textContent = message;
            alertBox.className = 'alert-custom show alert-' + type;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertBox.classList.remove('show');
            }, 5000);
        }

        // Normal Form Submission Handler
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form values
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('floatingEmail').value.trim();
            const password = document.getElementById('floatingPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const termsChecked = document.getElementById('termsCheck').checked;

            // Client-side validation
            if (!firstName) { showAlert('Please enter your First Name', 'error'); return; }
            if (firstName.length > 50) { showAlert('First Name must have less than 50 characters', 'error'); return; }
            if (!lastName) { showAlert('Please enter your Last Name', 'error'); return; }
            if (lastName.length > 50) { showAlert('Last Name must have less than 50 characters', 'error'); return; }
            if (!email) { showAlert('Please enter your Email', 'error'); return; }
            if (email.length >= 100) { showAlert('Email must have less than 100 characters', 'error'); return; }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) { showAlert('Invalid Email format', 'error'); return; }
            if (!password) { showAlert('Please enter your Password', 'error'); return; }
            if (password.length < 5 || password.length > 20) { showAlert('Password must be between 5 - 20 characters', 'error'); return; }
            if (password !== confirmPassword) { showAlert('Passwords do not match', 'error'); return; }
            if (!termsChecked) { showAlert('Please accept the Terms of Service and Privacy Policy', 'error'); return; }

            // Show loading state
            const signupBtn = document.getElementById('signupBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            
            signupBtn.disabled = true;
            btnText.textContent = 'Creating Account...';
            btnSpinner.style.display = 'inline-block';

            // Create FormData object
            const formData = new FormData();
            formData.append('f', firstName);
            formData.append('l', lastName);
            formData.append('e', email);
            formData.append('p', password);

            // Send AJAX request to PHP
            fetch('actions/create-account.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                signupBtn.disabled = false;
                btnText.textContent = 'Sign Up';
                btnSpinner.style.display = 'none';

                if (data.trim() === 'success') {
                    showAlert('Account created successfully! Redirecting...', 'success');
                    document.getElementById('signupForm').reset();
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    showAlert(data, 'error');
                }
            })
            .catch(error => {
                signupBtn.disabled = false;
                btnText.textContent = 'Sign Up';
                btnSpinner.style.display = 'none';
                showAlert('An error occurred. Please try again.', 'error');
                console.error('Error:', error);
            });
        });

        // Real-time password match validation
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('floatingPassword').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (confirmPassword && password === confirmPassword) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
            }
        });
    </script>
</body>
</html>