<?php
    session_start();
    if(isset($_SESSION["u"])){
        header("Location: dashboard.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <link rel="stylesheet" href="../assets/css/global.css">    

    <style>
        .login-section, .signup-section { min-height: 100vh; display: flex; align-items: center; justify-content: center; background-image: linear-gradient(rgba(10, 17, 31, 0.85), rgba(10, 17, 31, 0.95)), url('https://images.unsplash.com/photo-1612177344073-78e21244d543?q=80'); background-size: cover; background-position: center; padding: 2rem 0; }
        .login-card { background-color: rgba(44, 52, 64, 0.95); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 3rem; width: 100%; max-width: 450px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); backdrop-filter: blur(10px); }
        .signup-section .login-card { max-width: 500px; }

        .form-floating > .form-control { background-color: var(--input-bg); border: 1px solid var(--border-color); color: white; }
        .form-floating > .form-control:focus { background-color: var(--input-bg); border-color: var(--chp-gold); color: white; box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.15); }
        .form-floating > label { color: #888; }
        .form-floating > .form-control:focus ~ label, .form-floating > .form-control:not(:placeholder-shown) ~ label { color: var(--chp-gold); opacity: 1; }
        .form-control.is-invalid { border-color: var(--danger-red); }
        .form-control.is-valid { border-color: var(--success-green); }

        .social-login-btn { background-color: transparent; border: 1px solid var(--border-color); color: white; padding: 10px; border-radius: 4px; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; margin-bottom: 10px; cursor: pointer; position: relative; }
        .social-login-btn:hover { background-color: rgba(255,255,255,0.05); border-color: white; }

        #g_id_onload { display: none; }
        .google-btn-wrapper { position: relative; width: 100%; overflow: hidden; }

        .divider { display: flex; align-items: center; text-align: center; margin: 1.5rem 0; color: var(--text-muted); font-size: 0.85rem; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid var(--border-color); }
        .divider::before { margin-right: .5em; }
        .divider::after { margin-left: .5em; }

        .login-brand-logo { display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; }
        .password-toggle { position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; color: var(--text-muted); z-index: 5; }
        .password-toggle:hover { color: var(--chp-gold); }

        .alert-custom { padding: 12px 16px; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; display: none; }
        .alert-custom.show { display: block; }
        .alert-error { background-color: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.5); color: #ff6b6b; }
        .alert-success { background-color: rgba(40, 167, 69, 0.2); border: 1px solid rgba(40, 167, 69, 0.5); color: #51cf66; }

        .spinner-border-sm { width: 1rem; height: 1rem; border-width: 0.15em; }

        @media (max-width: 576px) { .login-card { padding: 2rem; width: 90%; } }
    </style>
</head>
<body>

    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    
                    <div class="login-card">
                        
                        <a href="../index.php" class="login-brand-logo">
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
                                    <label class="form-check-label text small" for="rememberMe">Remember me</label>
                                </div>
                                <a href="#" class="small text-gold">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn btn-gold mb-3" id="loginBtn">
                                <span id="btnText">Sign In</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                            </button>
                        </form>

                        <div class="divider">OR LOGIN WITH</div>

                        <div class="row">
                            <div class="col-12">
                                <button class="social-login-btn" id="customGoogleBtn">
                                    <i class="fab fa-google"></i> Google
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
        // --- GOOGLE LOGIN INTEGRATION ---
        
        window.onload = function () {
            google.accounts.id.initialize({
                client_id: "732695935170-8s2r15mbnobiek7rd2e7j8d7i99hnjc5.apps.googleusercontent.com", // PASTE YOUR CLIENT ID
                callback: handleGoogleResponse
            });

            const googleBtn = document.getElementById("customGoogleBtn");
            googleBtn.onclick = function() {
                google.accounts.id.prompt();
            }
        };

        // Check for URL errors
        document.addEventListener("DOMContentLoaded", () => {
            const urlParams = new URLSearchParams(window.location.search);
            
            if(urlParams.get('err') === 'concurrent') {
                showAlert('You were logged out because your account was accessed from another device.', 'error');
                window.history.replaceState(null, null, window.location.pathname);
            } 
            else if (urlParams.get('err') === 'timeout') {
                showAlert('Your session expired due to inactivity. Please sign in again.', 'error');
                window.history.replaceState(null, null, window.location.pathname);
            }
            else if (urlParams.get('msg') === 'logged_out') {
                showAlert('You have been logged out successfully.', 'success');
                window.history.replaceState(null, null, window.location.pathname);
            }
        });

        function handleGoogleResponse(response) {
            const responsePayload = decodeJwtResponse(response.credential);
            const email = responsePayload.email;

            console.log("Google Email:", email); // Debugging

            const formData = new FormData();
            formData.append('e', email);
            formData.append('login_method', 'google'); 

            // Show UI Loading
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            
            if(loginBtn) {
                loginBtn.disabled = true;
                btnText.textContent = 'Verifying Google Account...';
                btnSpinner.style.display = 'inline-block';
            }

            fetch('actions/verify-account.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Remove loading spinners
                if(loginBtn) { loginBtn.disabled = false; }
                if(btnText) { btnText.textContent = 'Sign In'; }
                if(btnSpinner) { btnSpinner.style.display = 'none'; }

                let response = data.trim();
                console.log("Server Response: ", response); // For debugging

                if (response === 'success') {
                    showAlert('Login successful! Redirecting...', 'success');
                    setTimeout(() => { window.location.href = 'dashboard.php'; }, 1000);
                } 
                else if (response === '') {
                    showAlert('Server returned an empty response. Check PHP logs.', 'error');
                } 
                else if (response.includes('<html') || response.includes('<' + '?php')) {
                    showAlert('Server 500 Error: Backend crashed.', 'error');
                }
                else {
                    // Displays the actual caught error message!
                    showAlert(response, 'error'); 
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                if(loginBtn) {
                    loginBtn.disabled = false;
                    btnText.textContent = 'Sign In';
                    btnSpinner.style.display = 'none';
                }
                showAlert('Connection error. Check console.', 'error');
            });
        }

        // Helper to decode JWT
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
            setTimeout(() => { alertBox.classList.remove('show'); }, 5000);
        }

        // Standard Email/Pass Login
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('floatingEmail').value.trim();
            const password = document.getElementById('floatingPassword').value;
            const rememberMe = document.getElementById('rememberMe').checked;

            if (!email) { showAlert('Please enter your Email', 'error'); return; }
            if (!password) { showAlert('Please enter your Password', 'error'); return; }

            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            
            loginBtn.disabled = true;
            btnText.textContent = 'Verifying...';
            btnSpinner.style.display = 'inline-block';

            const formData = new FormData();
            formData.append('e', email);
            formData.append('p', password);
            formData.append('rm', rememberMe ? '1' : '0');
            formData.append('login_method', 'standard'); // Flag for standard login

            fetch('actions/verify-account.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Remove loading spinners
                if(loginBtn) { loginBtn.disabled = false; }
                if(btnText) { btnText.textContent = 'Sign In'; }
                if(btnSpinner) { btnSpinner.style.display = 'none'; }

                let response = data.trim();
                console.log("Server Response: ", response); // For debugging

                if (response === 'success') {
                    showAlert('Login successful! Redirecting...', 'success');
                    setTimeout(() => { window.location.href = 'dashboard.php'; }, 1000);
                } 
                else if (response === '') {
                    showAlert('Server returned an empty response. Check PHP logs.', 'error');
                } 
                else if (response.includes('<html') || response.includes('<' + '?php')) {
                    showAlert('Server 500 Error: Backend crashed.', 'error');
                } 
                else {
                    // Displays the actual caught error message!
                    showAlert(response, 'error'); 
                }
            })
            .catch(error => {
                loginBtn.disabled = false;
                btnText.textContent = 'Sign In';
                btnSpinner.style.display = 'none';
                showAlert('An error occurred connecting to the server.', 'error');
            });
        });
    </script>
</body>
</html>