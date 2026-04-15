<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warranty Service - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css">
    <style>
        .service-hero { background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, rgba(212,175,55,0.03) 100%); border-bottom: 1px solid rgba(212,175,55,0.2); padding: 3.5rem 0 2.5rem; }
        .sidebar-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1.5rem; }
        .step-circle { width: 40px; height: 40px; background: var(--chp-gold); color: #000; font-weight: 700; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.2rem; }
    </style>
</head>
<body style="background-color: var(--prm-blue); color: var(--text-light);">
    <?php include 'includes/header.php'; ?>

    <section class="service-hero">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="index.php" style="color: var(--chp-gold); text-decoration: none;">Home</a></li>
                    <li class="breadcrumb-item text-muted">Services</li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Warranty Claim</li>
                </ol>
            </nav>
            <h1 class="font-oswald text-white text-uppercase mb-2" style="font-size: 2.5rem;">Warranty Service Portal</h1>
            <p class="text-muted" style="font-size: 1.1rem; max-width: 600px;">Fast, uncompromising support for your premium timepiece.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <h3 class="text-white font-oswald text-uppercase mb-4">The 3-Step Warranty Action Plan</h3>
                    <p class="text-muted mb-5">We stand by our craftsmanship. If your watch experiences a manufacturing defect or internal movement failure within the warranty period, follow this streamlined process to get it resolved immediately.</p>

                    <div class="d-flex gap-4 mb-4 pb-4 border-bottom" style="border-color: rgba(255,255,255,0.1)!important;">
                        <div class="step-circle">1</div>
                        <div>
                            <h5 class="text-white font-oswald text-uppercase mb-2">Email Support</h5>
                            <p class="text-muted mb-0" style="font-size: 0.95rem;">Send an email to <strong>support@powerwatch.lk</strong> or message us on WhatsApp. You must include your original <strong>Order Number</strong> (e.g., #PWORD12) so we can verify your coverage.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-4 mb-4 pb-4 border-bottom" style="border-color: rgba(255,255,255,0.1)!important;">
                        <div class="step-circle">2</div>
                        <div>
                            <h5 class="text-white font-oswald text-uppercase mb-2">Provide Visual Proof</h5>
                            <p class="text-muted mb-0" style="font-size: 0.95rem;">Attach a clear photo or short video demonstrating the defect. This allows our master technicians to diagnose the issue rapidly before you even ship the watch.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-4 mb-4">
                        <div class="step-circle">3</div>
                        <div>
                            <h5 class="text-white font-oswald text-uppercase mb-2">Receive Repair Ticket</h5>
                            <p class="text-muted mb-0" style="font-size: 0.95rem;">Once reviewed, you will receive a Repair Authorization Ticket and instructions on how to securely ship the timepiece to our Panadura facility for repair or replacement.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-card mb-4">
                        <h6 class="text-white font-oswald text-uppercase mb-3"><i class="fas fa-headset text-gold me-2"></i> Start Your Claim</h6>
                        <a href="https://wa.me/94771234567" target="_blank" class="btn btn-gold w-100 mb-3" style="font-size:0.95rem; font-weight: 600;">
                            <i class="fab fa-whatsapp me-2"></i> WhatsApp Support
                        </a>
                        <a href="mailto:support@powerwatch.lk" class="btn btn-outline-light w-100" style="font-size:0.95rem; border-color: rgba(255,255,255,0.2);">
                            <i class="fas fa-envelope me-2"></i> Email Us
                        </a>
                    </div>
                    
                    <div class="sidebar-card">
                        <h6><i class=\"fas fa-book-open text-gold me-2\"></i> Before Claiming</h6>
                        <p class="text-muted" style="font-size: 0.85rem;">Please review our Warranty Policy to ensure your issue is covered (excludes glass scratches, strap wear, and external water damage).</p>
                        <a href="warranty.php" class="text-gold" style="font-size: 0.85rem; text-decoration: none;">Read Full Policy <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>