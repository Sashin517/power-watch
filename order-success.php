<?php
session_start();

// Get order number from URL
$order_number = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : '';

// WhatsApp details
$whatsapp_number = "94768863075"; // Replace with your actual WhatsApp number
$whatsapp_message = urlencode("Hello Power Watch! Here is the payment slip for my order: " . $order_number);
$whatsapp_link = "https://wa.me/{$whatsapp_number}?text={$whatsapp_message}";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Received - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/global.css">    

    <style>
        body {
            background: linear-gradient(135deg, var(--prm-blue) 0%, #0d1626 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;
        }
        .success-container { 
            max-width: 550px; 
            width: 100%; 
            background: rgba(255,255,255,0.03); 
            border: 1px solid var(--border-color); 
            border-radius: 20px; 
            padding: 3rem 2.5rem; 
            text-align: center; 
            backdrop-filter: blur(10px); 
            animation: fadeInUp 0.6s ease-out; 
        }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        .success-icon { 
            width: 90px; height: 90px; 
            background: linear-gradient(135deg, var(--chp-gold), #b5952f); 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            margin: 0 auto 1.5rem; 
            animation: scaleIn 0.5s ease-out 0.2s both; 
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
        }
        @keyframes scaleIn { from { transform: scale(0); } to { transform: scale(1); } }
        .success-icon i { font-size: 2.5rem; color: #000; }
        
        .order-number { 
            font-size: 2.2rem; font-weight: 700; color: var(--text-light); margin: 0.5rem 0 0.5rem; letter-spacing: 1px; font-family: 'Oswald', sans-serif;
        }
        
        .divider { height: 1px; background: linear-gradient(90deg, transparent, var(--border-color), transparent); margin: 2rem 0; }
        
        /* Bank Details Card */
        .bank-card { 
            background: rgba(212, 175, 55, 0.05); 
            border: 1px solid rgba(212, 175, 55, 0.3); 
            border-radius: 12px; 
            padding: 1.5rem; 
            margin: 1.5rem 0; 
            text-align: left; 
        }
        .bank-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; border-bottom: 1px dashed rgba(255,255,255,0.1);
        }
        .bank-row:last-child { border-bottom: none; padding-bottom: 0; }
        .bank-label { color: var(--text-muted); font-size: 0.85rem; }
        .bank-value { color: white; font-weight: 600; font-size: 0.95rem; text-align: right; }
        
        .copy-btn {
            background: transparent; border: 1px solid var(--chp-gold); color: var(--chp-gold);
            padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; cursor: pointer; transition: 0.3s;
            margin-left: 10px; font-weight: 600; text-transform: uppercase;
        }
        .copy-btn:hover { background: var(--chp-gold); color: #000; }

        /* WhatsApp Button */
        .btn-whatsapp {
            background-color: #25D366; color: white; font-weight: 600; padding: 14px 20px; 
            border-radius: 8px; text-decoration: none; display: flex; align-items: center; 
            justify-content: center; width: 100%; transition: 0.3s; border: none; font-size: 1.05rem;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.2);
        }
        .btn-whatsapp:hover { background-color: #128C7E; color: white; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3); }

        .confetti { position: fixed; width: 10px; height: 10px; background: var(--chp-gold); position: absolute; animation: confetti-fall 3s linear forwards; z-index: 0; }
        @keyframes confetti-fall { to { transform: translateY(100vh) rotate(360deg); opacity: 0; } }

        @media (max-width: 576px) {
            .success-container { padding: 2rem 1.5rem; }
            .order-number { font-size: 1.8rem; }
            .success-icon { width: 70px; height: 70px; }
            .success-icon i { font-size: 2rem; }
            .bank-row { flex-direction: column; align-items: flex-start; gap: 5px; }
            .bank-value { text-align: left; }
            .copy-btn { margin-left: 0; margin-top: 5px; }
        }
    </style>
</head>
<body>

    <div class="success-container">
        
        <div class="success-icon">
            <i class="fas fa-receipt"></i>
        </div>

        <h1 class="mb-2 text-white font-oswald text-uppercase">Order Received!</h1>
        <p class="order-number"><?php echo $order_number; ?></p>
        
        <p class="text-gold small mb-0" style="background: rgba(212,175,55,0.1); padding: 8px; border-radius: 6px; display: inline-block;">
            <i class="fas fa-camera me-1"></i> Please take a screenshot of this page for your records.
        </p>

        <div class="divider"></div>

        <h4 class="font-oswald text-white mb-2 text-uppercase" style="font-size: 1.3rem;">Next Step: Payment</h4>
        <p class="text-muted" style="font-size: 0.85rem; line-height: 1.5;">
            To finalize your order, please transfer the total amount to the bank account below and send us a screenshot of the receipt via WhatsApp.
        </p>

        <div class="bank-card">
            <div class="bank-row">
                <span class="bank-label">Bank Name</span>
                <span class="bank-value">Commercial Bank</span>
            </div>
            <div class="bank-row">
                <span class="bank-label">Account Name</span>
                <span class="bank-value">Power Watch PVT LTD</span>
            </div>
            <div class="bank-row">
                <span class="bank-label">Branch</span>
                <span class="bank-value">Panadura Branch</span>
            </div>
            <div class="bank-row">
                <span class="bank-label">Account Number</span>
                <div class="d-flex align-items-center flex-wrap">
                    <span class="bank-value text-gold fs-5" id="accNumber">1234 5678 9012</span>
                    <button class="copy-btn" id="copyBtn" onclick="copyAccountNumber()">
                        <i class="fas fa-copy me-1"></i> Copy
                    </button>
                </div>
            </div>
        </div>

        <a href="<?php echo $whatsapp_link; ?>" target="_blank" class="btn-whatsapp mb-4">
            <i class="fab fa-whatsapp fa-xl me-2"></i> Send Slip via WhatsApp
        </a>

        <div class="mt-4 pt-4 border-top border-secondary text-start">
            <p class="text-muted small mb-2">
                <i class="fas fa-box text-gold me-2"></i> Orders are dispatched within 24 hours of payment confirmation.
            </p>
            <p class="text-muted small mb-0">
                <i class="fas fa-envelope text-gold me-2"></i> An order summary has also been sent to your email.
            </p>
        </div>
        
        <div class="mt-5 d-flex flex-column gap-3">
            <a href="collection.php" class="btn btn-outline-gold w-100 py-3" style="font-weight: 600; letter-spacing: 1px;">
                CONTINUE SHOPPING
            </a>
            <a href="index.php" class="text-muted text-decoration-none hover-gold mt-2" style="font-size: 0.9rem;">
                Return to Homepage
            </a>
        </div>
    </div>

    <script>
        // Copy to clipboard functionality
        function copyAccountNumber() {
            const accNumber = document.getElementById('accNumber').innerText.replace(/\s+/g, '');
            const copyBtn = document.getElementById('copyBtn');

            navigator.clipboard.writeText(accNumber).then(() => {
                const originalHtml = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check me-1"></i> Copied';
                copyBtn.style.backgroundColor = 'var(--chp-gold)';
                copyBtn.style.color = '#000';
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalHtml;
                    copyBtn.style.backgroundColor = 'transparent';
                    copyBtn.style.color = 'var(--chp-gold)';
                }, 3000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }

        // Create confetti effect
        function createConfetti() {
            const colors = ['#D4AF37', '#FFD700', '#FFA500', '#ffffff'];
            
            for (let i = 0; i < 40; i++) {
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

        // Security check: ONLY redirect if they accessed this page directly without placing an order. 
        // If an order exists, the page stays open FOREVER.
        <?php if (empty($order_number)): ?>
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>