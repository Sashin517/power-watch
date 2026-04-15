<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Battery Replacement - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css">
    <style>
        .service-hero { background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, rgba(212,175,55,0.03) 100%); border-bottom: 1px solid rgba(212,175,55,0.2); padding: 3.5rem 0 2.5rem; }
        .sidebar-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1.5rem; }
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
                    <li class="breadcrumb-item active text-white" aria-current="page">Battery Replacement</li>
                </ol>
            </nav>
            <h1 class="font-oswald text-white text-uppercase mb-2" style="font-size: 2.5rem;">Battery Replacement</h1>
            <p class="text-muted" style="font-size: 1.1rem; max-width: 600px;">Swift, secure power restoration using premium Swiss and Japanese cells.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <h3 class="text-white font-oswald text-uppercase mb-4">Never risk your water resistance</h3>
                    <p class="text-muted mb-4" style="line-height: 1.7;">Opening a watch case back breaks the factory seal. Having a battery replaced at a local uncertified shop often results in compromised water resistance and internal dust damage. At Power Watch, every battery replacement includes professional case resealing to protect your investment.</p>

                    <h5 class="text-white font-oswald mb-3 mt-5"><i class="fas fa-check-circle text-gold me-2"></i> Our Replacement Process</h5>
                    <ul class="text-muted" style="line-height: 1.8; list-style-type: none; padding-left: 0;">
                        <li class="mb-2"><i class="fas fa-battery-full text-secondary me-2"></i> <strong>Premium Cells:</strong> We only use high-grade Renata (Swiss) or Sony (Japanese) silver oxide batteries to prevent leakage.</li>
                        <li class="mb-2"><i class="fas fa-shield-alt text-secondary me-2"></i> <strong>Seal Inspection:</strong> O-ring gaskets are inspected and lubricated with silicone grease to maintain water resistance.</li>
                        <li class="mb-2"><i class="fas fa-bolt text-secondary me-2"></i> <strong>Contact Cleaning:</strong> Battery terminals are safely cleaned to ensure maximum power conductivity to the quartz movement.</li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-card">
                        <h6 class="text-white font-oswald text-uppercase mb-3"><i class="fas fa-stopwatch text-gold me-2"></i> Walk-In Service</h6>
                        <p class="text-muted mb-4" style="font-size: 0.9rem;">Most battery replacements can be completed in under 15 minutes at our Panadura showroom while you wait.</p>
                        
                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2" style="border-color: rgba(255,255,255,0.1)!important;">
                            <span class="text-muted" style="font-size: 0.9rem;">Starting From</span>
                            <span class="text-white fw-bold">LKR 1,500</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>