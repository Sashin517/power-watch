<?php
session_start();

$order_number = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : '';
$order_total = "0.00";
$customer_name = "Valued Customer";
$customer_email = "";
$order_date = date("Y-m-d H:i:s");
$email_sent = false;

try {
    require_once "includes/connection.php";
    if (empty(Database::$connection)) {
        Database::setUpConnection();
    }
    if(!empty($order_number)) {
        $stmt = Database::$connection->prepare("SELECT * FROM orders WHERE order_number = ?");
        $stmt->bind_param("s", $order_number);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows > 0) {
            $order = $res->fetch_assoc();
            $order_total = number_format($order['total_amount'], 2);
            $customer_name = htmlspecialchars($order['customer_fname'] . ' ' . $order['customer_lname']);
            $customer_email = $order['customer_email'];
            $order_date = date("F j, Y, g:i a", strtotime($order['created_at']));
            
            // Auto-send email if not sent yet
            if(!isset($order['email_sent']) || $order['email_sent'] != 1) {
                // Trigger email sending
                $email_response = @file_get_contents(dirname(__FILE__) . '/actions/send-order-email.php?order=' . urlencode($order_number));
                
                if($email_response === 'success') {
                    $email_sent = true;
                    // Update email_sent flag
                    $update_stmt = Database::$connection->prepare("UPDATE orders SET email_sent = 1 WHERE order_id = ?");
                    $update_stmt->bind_param("i", $order['order_id']);
                    $update_stmt->execute();
                }
            }
        }
    }
} catch (Exception $e) {
    error_log("Order Success Error: " . $e->getMessage());
}

$whatsapp_number = "94771234567";
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
        
        .order-number { font-size: 2.2rem; font-weight: 700; color: var(--text-light); margin: 0.5rem 0 0.5rem; letter-spacing: 1px; font-family: 'Oswald', sans-serif; }
        
        .divider { height: 1px; background: linear-gradient(90deg, transparent, var(--border-color), transparent); margin: 2rem 0; }
        
        .bank-card { 
            background: #111b2e; 
            border: 1px solid rgba(212, 175, 55, 0.3); 
            border-radius: 12px; 
            padding: 1.5rem; 
            margin: 1.5rem 0; 
            text-align: left; 
        }
        .bank-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 0; border-bottom: 1px dashed rgba(255,255,255,0.05);
        }
        .bank-row:last-of-type { border-bottom: none; padding-bottom: 0; }
        .bank-label { color: var(--text-muted); font-size: 0.85rem; }
        .bank-value { color: white; font-weight: 600; font-size: 0.95rem; text-align: right; }
        
        .btn-whatsapp {
            background-color: #25D366; color: white; font-weight: 600; padding: 14px 20px; 
            border-radius: 8px; text-decoration: none; display: flex; align-items: center; 
            justify-content: center; width: 100%; transition: 0.3s; border: none; font-size: 1.05rem;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.2); margin-bottom: 15px;
        }
        .btn-whatsapp:hover { background-color: #128C7E; color: white; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3); }

        .btn-pdf {
            background-color: #dc3545; border: 1px solid #dc3545; color: white; 
            font-weight: 600; padding: 14px 20px; border-radius: 8px; display: flex; 
            align-items: center; justify-content: center; width: 100%; transition: 0.3s; font-size: 0.95rem;
            text-decoration: none;
        }
        .btn-pdf:hover { background-color: #c82333; color: white; border-color: #c82333; transform: translateY(-1px); }

        .hover-gold:hover { letter-spacing: 1px; border-color: var(--border-color);}

        .confetti { position: fixed; width: 10px; height: 10px; background: var(--chp-gold); position: absolute; animation: confetti-fall 3s linear forwards; z-index: 0; }
        @keyframes confetti-fall { to { transform: translateY(100vh) rotate(360deg); opacity: 0; } }

        .email-notification {
            background: rgba(46, 204, 113, 0.1);
            border: 1px solid rgba(46, 204, 113, 0.3);
            border-radius: 8px;
            padding: 12px 16px;
            margin: 1rem 0;
            color: #2ecc71;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .success-container { padding: 2rem 1.5rem; }
            .order-number { font-size: 1.8rem; }
            .success-icon { width: 70px; height: 70px; }
            .success-icon i { font-size: 2rem; }
            .bank-row { flex-direction: column; align-items: flex-start; gap: 5px; }
            .bank-value { text-align: left; }
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
        
        <?php if($email_sent): ?>
        <div class="email-notification">
            <i class="fas fa-envelope-circle-check me-2"></i>
            Order confirmation sent to <?php echo htmlspecialchars($customer_email); ?>
        </div>
        <?php endif; ?>
        
        <div class="divider"></div>

        <h4 class="font-oswald text-white mb-2 text-uppercase" style="font-size: 1.3rem;">Next Step | Payment</h4>
        <p class="text-muted" style="font-size: 0.85rem; line-height: 1.5;">
            To finalize your order, please transfer the total amount to the bank account below and send us a screenshot of the receipt via WhatsApp.
        </p>

        <div class="bank-card">
            <div class="bank-row">
                <span class="bank-label">Bank Name</span>
                <span class="bank-value">Bank of Ceylon</span>
            </div>
            <div class="bank-row">
                <span class="bank-label">Branch</span>
                <span class="bank-value">Minuwangoda Branch (545)</span>
            </div>
            <div class="bank-row">
                <span class="bank-label">Account Holder</span>
                <span class="bank-value">MR R M S D RATNAYAKE</span>
            </div>
            <div class="bank-row pb-3">
                <span class="bank-label">Account Number</span>
                <span class="bank-value text-gold fs-5">0003102670</span>
            </div>
            
            <button class="btn btn-outline-gold w-100" id="copyAllBtn" onclick="copyAllDetails()" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                <i class="fas fa-copy me-2"></i> Copy Bank Details
            </button>
        </div>

        <a href="<?php echo $whatsapp_link; ?>" target="_blank" class="btn-whatsapp">
            <i class="fab fa-whatsapp fa-xl me-2"></i> Send Slip via WhatsApp
        </a>

        <a href="actions/generate-invoice-pdf.php?order=<?php echo urlencode($order_number); ?>" class="btn-pdf" target="_blank">
            <i class="fas fa-file-pdf fa-lg me-2"></i> Download Official Invoice
        </a>

        <div class="mt-4 pt-4 border-top border-secondary text-start">
            <p class="text-muted small mb-2"><i class="fas fa-box text-gold me-2"></i> Orders are dispatched within 24 hours of payment.</p>
            <p class="text-muted small mb-0"><i class="fas fa-envelope text-gold me-2"></i> An order summary has been sent to your email.</p>
        </div>
        
        <div class="mt-5 d-flex flex-column gap-3">
            <a href="collection.php" class="btn btn-outline-gold w-100 py-3" style="font-weight: 600; letter-spacing: 1px; border-color: var(--border-color);">
                CONTINUE SHOPPING
            </a>
            <a href="index.php" class="text-muted text-decoration-none hover-gold mt-2" style="font-size: 0.9rem;">
                Return to Homepage
            </a>
        </div>
    </div>

    <script>
        function copyAllDetails() {
            const details = `Account Number: 0003102670\nBank of Ceylon\nMinuwangoda Branch (545)\nAccount Holder: MR R M S D RATNAYAKE`;
            const copyBtn = document.getElementById('copyAllBtn');

            navigator.clipboard.writeText(details).then(() => {
                const originalHtml = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Details Copied Successfully!';
                copyBtn.classList.remove('btn-outline-gold');
                copyBtn.classList.add('btn-gold');
                copyBtn.style.color = '#000';
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalHtml;
                    copyBtn.classList.remove('btn-gold');
                    copyBtn.classList.add('btn-outline-gold');
                    copyBtn.style.color = '';
                }, 3000);
            }).catch(err => {
                alert("Failed to copy. Please manually select the text.");
            });
        }

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
                setTimeout(() => confetti.remove(), 5000);
            }
        }

        window.addEventListener('load', () => { setTimeout(createConfetti, 300); });

        <?php if (empty($order_number)): ?>
        setTimeout(() => { window.location.href = 'index.php'; }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>
