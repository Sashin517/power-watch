<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Engraving - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css">
    <style>
        .service-hero { background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, rgba(212,175,55,0.03) 100%); border-bottom: 1px solid rgba(212,175,55,0.2); padding: 3.5rem 0 2.5rem; }
        .sidebar-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1.5rem; position: sticky; top: 100px; }
        .legal-trap-alert { background: rgba(231, 76, 60, 0.1); border-left: 4px solid var(--danger-red); padding: 1.25rem; border-radius: 0 8px 8px 0; margin-bottom: 2rem; }
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
                    <li class="breadcrumb-item active text-white" aria-current="page">Custom Engraving</li>
                </ol>
            </nav>
            <h1 class="font-oswald text-white text-uppercase mb-2" style="font-size: 2.5rem;">Custom Engraving</h1>
            <p class="text-muted" style="font-size: 1.1rem; max-width: 600px;">Make your timepiece truly yours. Leave a legacy that lasts generations.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="legal-trap-alert">
                        <h5 class="text-white font-oswald text-uppercase mb-2"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Important: Final Sale Policy</h5>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;"><strong>Any timepiece that has been customized or personalized with custom engraving is strictly FINAL SALE and is not eligible for return, refund, or exchange under any circumstances.</strong> By proceeding with an engraving request, you acknowledge and agree to these terms.</p>
                    </div>

                    <h3 class="text-white font-oswald text-uppercase mb-4">Precision Laser Engraving</h3>
                    <p class="text-muted mb-4" style="line-height: 1.7;">Whether it's a wedding anniversary, a corporate milestone, or a personal achievement, our precision laser engraving service allows you to etch your most meaningful moments directly into the 316L Stainless Steel case back of your watch.</p>

                    <h5 class="text-white font-oswald mb-3">What can be engraved?</h5>
                    <ul class="text-muted mb-5" style="line-height: 1.8;">
                        <li><strong>Initials & Monograms:</strong> Classic and understated (e.g., A.J.R).</li>
                        <li><strong>Dates:</strong> Anniversaries or birth dates (e.g., 24.10.2025).</li>
                        <li><strong>Short Messages:</strong> Up to 20 characters, depending on the watch model's case back size.</li>
                    </ul>

                    <p class="text-muted"><em>Note: Engraving adds an additional 2-3 business days to your order's delivery timeline.</em></p>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-card">
                        <h6 class="text-white font-oswald text-uppercase mb-3"><i class="fas fa-gem text-gold me-2"></i> Engraving Pricing</h6>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3" style="border-color: rgba(255,255,255,0.1)!important;">
                            <span class="text-muted" style="font-size: 0.9rem;">Standard Text (Up to 20 chars)</span>
                            <span class="text-white fw-bold">LKR 2,500</span>
                        </div>
                        <a href="contact.php" class="btn btn-gold w-100 mt-2" style="font-size:0.9rem;">
                            <i class="fas fa-envelope me-2"></i> Request Engraving
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>