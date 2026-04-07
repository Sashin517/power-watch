<?php
session_start();

// Get order number from URL
$order_number = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : '';

// In production, fetch order details from database
// For now, showing success message with order number
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --prm-blue: #0A111F;
            --chp-gold: #D4AF37;
            --chp-gold-hover: #b5952f;
            --text-light: #f8f9fa;
            --text-muted: #adb5bd;
            --success-green: #2ecc71;
            --border-color: #2d3748;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, var(--prm-blue) 0%, #0d1626 100%);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        .success-container {
            max-width: 600px;
            width: 100%;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            backdrop-filter: blur(10px);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--success-green), #27ae60);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: scaleIn 0.5s ease-out 0.2s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .success-icon i {
            font-size: 3rem;
            color: white;
        }

        .order-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--chp-gold);
            margin: 1.5rem 0;
            letter-spacing: 2px;
            text-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-color), transparent);
            margin: 2rem 0;
        }

        .info-box {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item i {
            width: 30px;
            color: var(--chp-gold);
            font-size: 1.2rem;
        }

        .btn-gold {
            background-color: var(--chp-gold);
            color: #000;
            border: none;
            font-weight: 700;
            border-radius: 50px;
            padding: 14px 40px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem;
        }

        .btn-gold:hover {
            background-color: var(--chp-gold-hover);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
        }

        .btn-outline-gold {
            background: transparent;
            color: var(--chp-gold);
            border: 2px solid var(--chp-gold);
            font-weight: 600;
            border-radius: 50px;
            padding: 12px 38px;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem;
        }

        .btn-outline-gold:hover {
            background-color: var(--chp-gold);
            color: #000;
            transform: translateY(-2px);
        }

        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: var(--chp-gold);
            position: absolute;
            animation: confetti-fall 3s linear forwards;
        }

        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        @media (max-width: 576px) {
            .success-container {
                padding: 2rem 1.5rem;
            }

            .order-number {
                font-size: 2rem;
            }

            .success-icon {
                width: 80px;
                height: 80px;
            }

            .success-icon i {
                font-size: 2.5rem;
            }

            .btn-gold, .btn-outline-gold {
                display: block;
                width: 100%;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>

    <div class="success-container">
        
        <!-- Success Icon -->
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Main Message -->
        <h1 class="mb-3">Order Confirmed!</h1>
        <p class="text-muted mb-2">Thank you for your purchase</p>
        
        <!-- Order Number -->
        <div class="order-number"><?php echo $order_number; ?></div>

        <div class="divider"></div>

        <!-- Order Info -->
        <div class="info-box">
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div class="ms-3">
                    <small class="text-muted d-block">Confirmation sent to</small>
                    <span class="text-white">Your registered email</span>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-truck"></i>
                <div class="ms-3">
                    <small class="text-muted d-block">Estimated delivery</small>
                    <span class="text-white">3-5 Business Days</span>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-headset"></i>
                <div class="ms-3">
                    <small class="text-muted d-block">Need help?</small>
                    <span class="text-white">+94 77 123 4567</span>
                </div>
            </div>
        </div>

        <p class="text-muted small mt-4 mb-4">
            We'll send you shipping updates and tracking information via email. 
            You can also track your order status in your account dashboard.
        </p>

        <!-- Action Buttons -->
        <div class="mt-4">
            <a href="index.php" class="btn btn-gold">
                <i class="fas fa-home me-2"></i> Back to Home
            </a>
            <a href="#" class="btn btn-outline-gold">
                <i class="fas fa-receipt me-2"></i> View Order
            </a>
        </div>

        <!-- Additional Info -->
        <div class="mt-5 pt-4 border-top border-secondary">
            <p class="text-muted small mb-2">
                <i class="fas fa-shield-alt text-gold me-2"></i>
                Your order is protected by our secure checkout
            </p>
            <p class="text-muted small mb-0">
                Questions? Contact us at <a href="mailto:support@powerwatch.lk" class="text-gold">support@powerwatch.lk</a>
            </p>
        </div>
    </div>

    <script>
        // Create confetti effect
        function createConfetti() {
            const colors = ['#D4AF37', '#FFD700', '#FFA500', '#2ecc71'];
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = -10 + 'px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 2 + 's';
                confetti.style.animationDuration = (Math.random() * 2 + 3) + 's';
                document.body.appendChild(confetti);
                
                // Remove after animation
                setTimeout(() => confetti.remove(), 5000);
            }
        }

        // Trigger confetti on load
        window.addEventListener('load', () => {
            setTimeout(createConfetti, 300);
        });

        // Redirect if no order number
        <?php if (empty($order_number)): ?>
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>
