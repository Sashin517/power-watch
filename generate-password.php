<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Protection Generator - Power Watch</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0A111F 0%, #1a2332 100%);
            color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            backdrop-filter: blur(10px);
        }
        h1 {
            color: #D4AF37;
            margin-bottom: 10px;
            font-size: 2rem;
        }
        .subtitle {
            color: #adb5bd;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #D4AF37;
            font-weight: 600;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 6px;
            color: white;
            font-size: 1rem;
        }
        input:focus {
            outline: none;
            border-color: #D4AF37;
            background: rgba(255,255,255,0.15);
        }
        button {
            background: #D4AF37;
            color: #000;
            border: none;
            padding: 14px 30px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
            text-transform: uppercase;
        }
        button:hover {
            background: #b5952f;
            transform: translateY(-2px);
        }
        .result {
            margin-top: 30px;
            padding: 20px;
            background: rgba(0,0,0,0.3);
            border-left: 4px solid #D4AF37;
            border-radius: 6px;
            display: none;
        }
        .result.show {
            display: block;
        }
        .result-title {
            color: #D4AF37;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .code-block {
            background: #0A111F;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            word-break: break-all;
            margin: 10px 0;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }
        .copy-btn {
            background: transparent;
            border: 1px solid #D4AF37;
            color: #D4AF37;
            padding: 8px 16px;
            font-size: 0.85rem;
            margin-top: 10px;
            width: auto;
        }
        .copy-btn:hover {
            background: rgba(212, 175, 55, 0.1);
        }
        .success {
            background: rgba(46, 204, 113, 0.2);
            border-left-color: #2ecc71;
        }
        .info-box {
            background: rgba(52, 152, 219, 0.1);
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 0.9rem;
        }
        .step {
            background: rgba(255,255,255,0.03);
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            border-left: 3px solid #D4AF37;
        }
        .step strong {
            color: #D4AF37;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Password Generator</h1>
        <p class="subtitle">Create htpasswd file for Power Watch site protection</p>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="admin" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter strong password" required>
            </div>

            <div class="form-group">
                <label for="password2">Confirm Password</label>
                <input type="password" id="password2" name="password2" placeholder="Re-enter password" required>
            </div>

            <button type="submit" name="generate">Generate htpasswd</button>
        </form>

        <?php
        if (isset($_POST['generate'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            
            // Validation
            if (empty($username) || empty($password)) {
                echo '<div class="result show" style="border-left-color: #e74c3c;">
                    <div class="result-title">❌ Error</div>
                    <p>Username and password are required.</p>
                </div>';
            } elseif ($password !== $password2) {
                echo '<div class="result show" style="border-left-color: #e74c3c;">
                    <div class="result-title">❌ Error</div>
                    <p>Passwords do not match.</p>
                </div>';
            } elseif (strlen($password) < 8) {
                echo '<div class="result show" style="border-left-color: #f39c12;">
                    <div class="result-title">⚠️ Warning</div>
                    <p>Password is too short. Recommended: 12+ characters.</p>
                </div>';
            } else {
                // Generate encrypted password
                $encrypted = crypt($password, '$1$' . substr(md5(rand()), 0, 8) . '$');
                $htpasswd_line = $username . ':' . $encrypted;
                
                // Get current directory path
                $current_dir = dirname(__FILE__);
                $htpasswd_path = $current_dir . '/.htpasswd';
                
                echo '<div class="result show success">
                    <div class="result-title">✅ Success! htpasswd Generated</div>
                    
                    <p><strong>Step 1:</strong> Create a file named <code>.htpasswd</code></p>
                    
                    <p><strong>Step 2:</strong> Add this line to .htpasswd:</p>
                    <div class="code-block" id="htpasswdContent">' . htmlspecialchars($htpasswd_line) . '</div>
                    <button class="copy-btn" onclick="copyToClipboard(\'htpasswdContent\')">📋 Copy</button>
                    
                    <p style="margin-top: 20px;"><strong>Step 3:</strong> Upload .htpasswd to:</p>
                    <div class="code-block">/home/sldevs/public_html/power-watch/.htpasswd</div>
                    
                    <p style="margin-top: 20px;"><strong>Step 4:</strong> Update .htaccess line 26:</p>
                    <div class="code-block" id="authPath">AuthUserFile ' . htmlspecialchars($current_dir) . '/.htpasswd</div>
                    <button class="copy-btn" onclick="copyToClipboard(\'authPath\')">📋 Copy Path</button>
                </div>';
                
                // Try to create the file automatically
                if (is_writable($current_dir)) {
                    if (file_put_contents($htpasswd_path, $htpasswd_line . PHP_EOL, FILE_APPEND)) {
                        echo '<div class="result show" style="border-left-color: #2ecc71; margin-top: 15px;">
                            <div class="result-title">🎉 File Created Automatically!</div>
                            <p>.htpasswd file has been created at: <code>' . htmlspecialchars($htpasswd_path) . '</code></p>
                            <p style="margin-top: 10px;">You can now upload the .htaccess file and your site will be protected!</p>
                        </div>';
                    }
                } else {
                    echo '<div class="info-box">
                        <strong>ℹ️ Note:</strong> Could not auto-create .htpasswd file. Please create it manually using the content above.
                    </div>';
                }
            }
        }
        ?>

        <div class="info-box" style="margin-top: 30px;">
            <strong>📝 Next Steps:</strong>
            <div class="step"><strong>1.</strong> Create .htpasswd file with the generated content</div>
            <div class="step"><strong>2.</strong> Upload .htpasswd to your server</div>
            <div class="step"><strong>3.</strong> Upload .htaccess file</div>
            <div class="step"><strong>4.</strong> Update AuthUserFile path in .htaccess</div>
            <div class="step"><strong>5.</strong> Test by visiting your site</div>
        </div>

        <div class="info-box">
            <strong>🔒 Security Tips:</strong><br>
            • Use a strong password (12+ characters)<br>
            • Mix uppercase, lowercase, numbers & symbols<br>
            • Never share your password<br>
            • Change password every 90 days<br>
            • Don't use the same password elsewhere
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            
            navigator.clipboard.writeText(text).then(() => {
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = '✅ Copied!';
                btn.style.background = 'rgba(46, 204, 113, 0.2)';
                btn.style.borderColor = '#2ecc71';
                btn.style.color = '#2ecc71';
                
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = '';
                    btn.style.borderColor = '';
                    btn.style.color = '';
                }, 2000);
            }).catch(err => {
                alert('Failed to copy. Please select and copy manually.');
            });
        }

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            const colors = ['#e74c3c', '#f39c12', '#f1c40f', '#2ecc71', '#27ae60'];
            const labels = ['Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
            
            if (password.length > 0) {
                e.target.style.borderColor = colors[strength - 1] || '#e74c3c';
            } else {
                e.target.style.borderColor = 'rgba(255,255,255,0.2)';
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const password2 = document.getElementById('password2').value;
            
            if (password !== password2) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 8) {
                if (!confirm('Password is weak. Continue anyway?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
</body>
</html>
