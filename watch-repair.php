<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Repair - Power Watch</title>
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
                    <li class="breadcrumb-item active text-white" aria-current="page">Watch Repair</li>
                </ol>
            </nav>
            <h1 class="font-oswald text-white text-uppercase mb-2" style="font-size: 2.5rem;">Expert Watch Repair</h1>
            <p class="text-muted" style="font-size: 1.1rem; max-width: 600px;">Restore your timepiece to factory precision with our master technicians.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <h3 class="text-white font-oswald text-uppercase mb-4">Precision Care for Premium Watches</h3>
                    <p class="text-muted mb-4" style="line-height: 1.7;">A luxury watch is a highly engineered machine. When it requires servicing, you need technicians who understand the intricate mechanics of Japanese and Swiss movements. Our in-house repair center in Panadura handles everything from minor adjustments to complete movement overhauls.</p>

                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="p-4 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                                <i class="fas fa-cogs text-gold mb-3" style="font-size: 1.5rem;"></i>
                                <h5 class="text-white font-oswald text-uppercase mb-2">Movement Servicing</h5>
                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Complete dismantling, ultrasonic cleaning, lubrication, and reassembly of quartz and automatic movements.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                                <i class="fas fa-water text-gold mb-3" style="font-size: 1.5rem;"></i>
                                <h5 class="text-white font-oswald text-uppercase mb-2">Water Resistance</h5>
                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Gasket replacement, crystal resealing, and atmospheric pressure testing to factory specifications.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                                <i class="fas fa-link text-gold mb-3" style="font-size: 1.5rem;"></i>
                                <h5 class="text-white font-oswald text-uppercase mb-2">Bracelet Adjustments</h5>
                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Professional link removal, clasp repairs, and strap replacements without scratching the metal.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                                <i class="fas fa-search-plus text-gold mb-3" style="font-size: 1.5rem;"></i>
                                <h5 class="text-white font-oswald text-uppercase mb-2">Glass Replacement</h5>
                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Upgrades and replacements for scratched mineral and sapphire crystals.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-card">
                        <h6 class="text-white font-oswald text-uppercase mb-3"><i class="fas fa-store text-gold me-2"></i> Drop Off Location</h6>
                        <p class="text-muted" style="font-size: 0.9rem;">No. 123, Main Street<br>Panadura, Sri Lanka</p>
                        <hr style="border-color: rgba(255,255,255,0.1);">
                        <p class="text-muted mb-1" style="font-size: 0.85rem;"><strong>Hours:</strong> Mon - Sat, 9am - 6pm</p>
                        <a href="https://wa.me/94771234567" target="_blank" class="btn btn-gold w-100 mt-4" style="font-size:0.9rem;">
                            <i class="fab fa-whatsapp me-2"></i> Message for a Quote
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