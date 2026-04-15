<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css">
    <style>
        /* ===== HERO ===== */
        .about-hero {
            position: relative;
            background: linear-gradient(135deg, #0A111F 0%, #0f1a2e 60%, #0A111F 100%);
            border-bottom: 1px solid rgba(212,175,55,0.15);
            padding: 5rem 0 4rem;
            overflow: hidden;
        }
        .about-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(212,175,55,0.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .about-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(212,175,55,0.04) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-tagline {
            font-family: 'Oswald', sans-serif;
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            color: white;
            text-transform: uppercase;
            letter-spacing: 3px;
            line-height: 1.15;
        }
        .hero-tagline span { color: var(--chp-gold); }
        .hero-sub { color: #aaa; font-size: 1rem; line-height: 1.7; max-width: 520px; }

        /* ===== STAT COUNTER BAR ===== */
        .stats-bar {
            background: var(--sec-blue);
            border-top: 1px solid rgba(212,175,55,0.15);
            border-bottom: 1px solid rgba(212,175,55,0.15);
            padding: 2rem 0;
        }
        .stat-item { text-align: center; }
        .stat-value { font-family: 'Oswald', sans-serif; font-size: 2.2rem; color: var(--chp-gold); line-height: 1; display: block; }
        .stat-label { font-size: 0.78rem; color: #888; margin-top: 5px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ===== SECTION SHARED ===== */
        .section-tag {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--chp-gold);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 0.75rem;
        }
        .section-title {
            font-family: 'Oswald', sans-serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            color: white;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            line-height: 1.2;
        }
        .section-body { color: #bbb; font-size: 0.93rem; line-height: 1.75; }

        /* ===== NARRATIVE SECTION ===== */
        .narrative-section { padding: 5rem 0; }
        .narrative-visual {
            position: relative;
            aspect-ratio: 4/5;
            max-height: 480px;
            border-radius: 16px;
            overflow: hidden;
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .narrative-visual .logo-display {
            font-family: 'Oswald', sans-serif;
            text-align: center;
        }
        .narrative-visual .logo-display i { font-size: 5rem; color: var(--chp-gold); display: block; margin-bottom: 1rem; }
        .narrative-visual .logo-display span { font-size: 1.3rem; letter-spacing: 4px; color: white; text-transform: uppercase; }
        .narrative-visual::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(212,175,55,0.08), transparent);
            pointer-events: none;
        }
        .gold-accent-line {
            width: 48px;
            height: 3px;
            background: var(--chp-gold);
            border-radius: 2px;
            margin-bottom: 1.5rem;
        }

        /* ===== VALUE PILLARS ===== */
        .pillars-section { padding: 4rem 0; background: var(--sec-blue); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
        .pillar-card {
            text-align: center;
            padding: 2rem 1.5rem;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            background: rgba(255,255,255,0.02);
            height: 100%;
            transition: border-color 0.25s, transform 0.25s;
        }
        .pillar-card:hover { border-color: rgba(212,175,55,0.35); transform: translateY(-4px); }
        .pillar-icon {
            width: 60px;
            height: 60px;
            background: rgba(212,175,55,0.1);
            border: 1px solid rgba(212,175,55,0.25);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .pillar-icon i { color: var(--chp-gold); font-size: 1.3rem; }
        .pillar-card h6 { font-family: 'Oswald', sans-serif; color: white; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.6rem; }
        .pillar-card p { color: #999; font-size: 0.84rem; margin: 0; line-height: 1.6; }

        /* ===== WHY US ===== */
        .why-section { padding: 5rem 0; }
        .why-item { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 1.75rem; }
        .why-item:last-child { margin-bottom: 0; }
        .why-icon { width: 44px; height: 44px; background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .why-icon i { color: var(--chp-gold); font-size: 1rem; }
        .why-item strong { color: white; font-size: 0.9rem; display: block; margin-bottom: 4px; }
        .why-item p { color: #aaa; font-size: 0.84rem; margin: 0; line-height: 1.55; }

        /* Visual side for why section */
        .specs-card {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 2rem;
            height: 100%;
        }
        .specs-card h6 { font-family: 'Oswald', sans-serif; color: white; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.25rem; }
        .spec-row { display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid rgba(255,255,255,0.04); }
        .spec-row:last-child { border-bottom: none; }
        .spec-label { color: #888; font-size: 0.82rem; }
        .spec-value { color: white; font-size: 0.85rem; font-weight: 600; }
        .spec-value.gold { color: var(--chp-gold); }

        /* ===== MISSION BANNER ===== */
        .mission-banner {
            background: linear-gradient(135deg, rgba(212,175,55,0.12) 0%, rgba(212,175,55,0.05) 100%);
            border: 1px solid rgba(212,175,55,0.2);
            border-radius: 16px;
            padding: 3rem 2.5rem;
            text-align: center;
            margin: 4rem 0;
        }
        .mission-banner blockquote {
            font-family: 'Oswald', sans-serif;
            font-size: clamp(1.3rem, 3vw, 1.9rem);
            color: white;
            text-transform: uppercase;
            letter-spacing: 2px;
            line-height: 1.3;
            margin: 0;
        }
        .mission-banner blockquote span { color: var(--chp-gold); }
        .mission-banner p { color: #888; font-size: 0.85rem; margin-top: 1rem; margin-bottom: 0; }

        /* ===== CONTACT CTA ===== */
        .cta-section { padding: 4rem 0; }
        .cta-card {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2.5rem;
        }
        .contact-method {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 0.9rem 1.25rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: rgba(255,255,255,0.02);
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
            margin-bottom: 0.75rem;
        }
        .contact-method:last-child { margin-bottom: 0; }
        .contact-method:hover { border-color: rgba(212,175,55,0.3); background: rgba(212,175,55,0.05); }
        .contact-method i { font-size: 1.1rem; color: var(--chp-gold); width: 20px; text-align: center; }
        .contact-method .cm-label { color: #888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; }
        .contact-method .cm-value { color: white; font-size: 0.9rem; font-weight: 600; display: block; margin-top: 1px; }

        @media (max-width: 767px) {
            .about-hero { padding: 3.5rem 0 3rem; }
            .narrative-section, .why-section, .cta-section { padding: 3rem 0; }
            .pillars-section { padding: 3rem 0; }
            .mission-banner { padding: 2rem 1.5rem; margin: 2.5rem 0; }
            .stats-bar .stat-value { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- ===== HERO ===== -->
    <section class="about-hero">
        <div class="container" style="position:relative; z-index:1;">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0" style="font-size:0.82rem;">
                    <li class="breadcrumb-item"><a href="index.php" class="text-gold">Home</a></li>
                    <li class="breadcrumb-item active" style="color:#aaa;">About Us</li>
                </ol>
            </nav>
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="section-tag">Est. Power Watch Sri Lanka</div>
                    <h1 class="hero-tagline mb-3">
                        Forged for Those<br>Who <span>Command</span><br>the Room.
                    </h1>
                    <p class="hero-sub mb-4">
                        Power Watch was built on a single conviction: that prestige on your wrist should not require a legacy retail markup. We deliver precision timepieces that project authority — without the compromise.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="collection.php" class="btn btn-gold px-4 py-3">
                            <i class="fas fa-watch me-2"></i> Shop Collection
                        </a>
                        <a href="#our-story" class="btn btn-outline-gold px-4 py-3">
                            Our Story
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                    <div style="text-align:center; opacity:0.9;">
                        <i class="fas fa-crown" style="font-size:6rem; color:var(--chp-gold); filter:drop-shadow(0 0 30px rgba(212,175,55,0.3));"></i>
                        <div style="font-family:'Oswald',sans-serif; color:white; font-size:1rem; letter-spacing:5px; margin-top:1rem; text-transform:uppercase;">Power Watch</div>
                        <div style="font-size:0.72rem; color:#666; letter-spacing:2px; margin-top:4px;">Sri Lanka</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== STATS ===== -->
    <div class="stats-bar">
        <div class="container">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-value">500+</span>
                        <span class="stat-label">Timepieces Sold</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-value">15+</span>
                        <span class="stat-label">Premium Brands</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-value">24/7</span>
                        <span class="stat-label">WhatsApp Support</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-value">100%</span>
                        <span class="stat-label">Island-Wide Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== OUR STORY ===== -->
    <section class="narrative-section" id="our-story">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5 order-lg-2">
                    <div class="narrative-visual">
                        <div class="logo-display">
                            <i class="fas fa-shield-halved"></i>
                            <span>Power Watch</span>
                            <div style="font-size:0.7rem; letter-spacing:3px; color:#666; margin-top:8px; text-transform:uppercase;">Precision · Presence · Power</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 order-lg-1">
                    <div class="section-tag">Our Story</div>
                    <div class="gold-accent-line"></div>
                    <h2 class="section-title mb-3">Traditional Luxury Was Gatekept. We Changed That.</h2>
                    <div class="section-body">
                        <p>We noticed a gap that the luxury watch market had deliberately maintained for decades: breathtaking timepieces, locked behind an inflated retail premium that had little to do with the movement inside and everything to do with the label outside.</p>
                        <p>Power Watch was forged with a different mandate. We source precision-engineered timepieces from globally recognized manufacturers — featuring 316L stainless steel cases, precision Japanese and Swiss movements, and scratch-resistant mineral crystals — and deliver them to the Sri Lankan market without the legacy retail tax.</p>
                        <p>What you're purchasing isn't just a watch. It's the expression of a standard. A statement worn on the wrist that says you understand quality, you demand precision, and you refuse to overpay for a logo.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== VALUE PILLARS ===== -->
    <section class="pillars-section">
        <div class="container">
            <div class="text-center mb-4">
                <div class="section-tag">What We Stand For</div>
                <h2 class="section-title">Our Pillars</h2>
            </div>
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <div class="pillar-card">
                        <div class="pillar-icon"><i class="fas fa-gem"></i></div>
                        <h6>Precision</h6>
                        <p>Every movement is tested for accuracy. We only carry timepieces that meet a strict ±15 second/day tolerance standard.</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="pillar-card">
                        <div class="pillar-icon"><i class="fas fa-shield-halved"></i></div>
                        <h6>Integrity</h6>
                        <p>What you see is what you get. No hidden markups, no inflated "original" prices. Transparent pricing, always.</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="pillar-card">
                        <div class="pillar-icon"><i class="fas fa-star"></i></div>
                        <h6>Craftsmanship</h6>
                        <p>316L stainless steel. Scratch-resistant crystal. Japanese and Swiss movements. Materials that justify every rupee.</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="pillar-card">
                        <div class="pillar-icon"><i class="fas fa-handshake"></i></div>
                        <h6>Trust</h6>
                        <p>24-month warranty. Transparent return policy. Island-wide cash on delivery. We make it easy to buy with confidence.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== WHY US + SPECS ===== -->
    <section class="why-section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="section-tag">Why Power Watch</div>
                    <div class="gold-accent-line"></div>
                    <h2 class="section-title mb-4">Engineered to a Standard You Can Feel</h2>

                    <div class="why-item">
                        <div class="why-icon"><i class="fas fa-microscope"></i></div>
                        <div>
                            <strong>Rigorous Pre-Shipment QC</strong>
                            <p>Every timepiece undergoes a multi-point quality inspection before dispatch — movement, crown, clasp, crystal integrity, and timekeeping accuracy are verified.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-icon"><i class="fas fa-truck-fast"></i></div>
                        <div>
                            <strong>Island-Wide Cash on Delivery</strong>
                            <p>We offer COD across Sri Lanka. You don't pay a rupee until the watch is in your hands and you're satisfied with what you see.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-icon"><i class="fas fa-percent"></i></div>
                        <div>
                            <strong>KOKO Instalment Plans</strong>
                            <p>Split any purchase into 3 equal, interest-free instalments via KOKO — making premium timepieces accessible without financial strain.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-icon"><i class="fas fa-envelope-open-text"></i></div>
                        <div>
                            <strong>Post-Purchase Support</strong>
                            <p>From your order confirmation to warranty claims, our team is reachable via WhatsApp within hours — not days.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="specs-card">
                        <h6><i class="fas fa-sliders text-gold me-2"></i> Build Standards</h6>
                        <div class="spec-row"><span class="spec-label">Case Material</span><span class="spec-value">316L Stainless Steel</span></div>
                        <div class="spec-row"><span class="spec-label">Crystal</span><span class="spec-value">Scratch-Resistant Mineral</span></div>
                        <div class="spec-row"><span class="spec-label">Movement Origin</span><span class="spec-value">Japanese &amp; Swiss</span></div>
                        <div class="spec-row"><span class="spec-label">Accuracy Standard</span><span class="spec-value gold">±15 sec/day</span></div>
                        <div class="spec-row"><span class="spec-label">Water Resistance</span><span class="spec-value">Varies by model (3–10 ATM)</span></div>
                        <div class="spec-row"><span class="spec-label">Warranty</span><span class="spec-value gold">24 Months International</span></div>
                        <div class="spec-row"><span class="spec-label">QC Inspection</span><span class="spec-value">Multi-point, pre-shipment</span></div>
                        <div class="spec-row"><span class="spec-label">Delivery</span><span class="spec-value">Island-wide, COD available</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MISSION QUOTE ===== -->
    <div class="container">
        <div class="mission-banner">
            <blockquote>"Unapologetic <span>Presence</span>. Relentless <span>Precision</span>. Zero Compromise."</blockquote>
            <p>— The Power Watch Mandate</p>
        </div>
    </div>

    <!-- ===== CONTACT CTA ===== -->
    <section class="cta-section">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5">
                    <div class="section-tag">Get in Touch</div>
                    <div class="gold-accent-line"></div>
                    <h2 class="section-title mb-3">We're a Message Away</h2>
                    <p class="section-body">Whether you're choosing your first Power Watch, need help with sizing, or have a question about an order — our team responds fast.</p>
                    <a href="collection.php" class="btn btn-gold px-4 py-3 mt-2">
                        <i class="fas fa-watch me-2"></i> Browse the Collection
                    </a>
                </div>
                <div class="col-lg-7">
                    <div class="cta-card">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="https://wa.me/94771234567" target="_blank" class="contact-method">
                                    <i class="fab fa-whatsapp" style="color:#25D366;"></i>
                                    <div><span class="cm-label">WhatsApp</span><span class="cm-value">+94 77 123 4567</span></div>
                                </a>
                                <a href="tel:01122264589" class="contact-method">
                                    <i class="fas fa-phone"></i>
                                    <div><span class="cm-label">Phone</span><span class="cm-value">011-22264589</span></div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="mailto:info@powerwatch.lk" class="contact-method">
                                    <i class="fas fa-envelope"></i>
                                    <div><span class="cm-label">Email</span><span class="cm-value">info@powerwatch.lk</span></div>
                                </a>
                                <div class="contact-method" style="cursor:default;">
                                    <i class="fas fa-location-dot"></i>
                                    <div><span class="cm-label">Location</span><span class="cm-value">No. 123, Main Street, Negombo</span></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="contact-method" style="cursor:default;">
                                    <i class="fas fa-clock"></i>
                                    <div><span class="cm-label">Business Hours</span><span class="cm-value">Monday – Saturday, 9:00am – 6:00pm</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== RELATED PAGES ===== -->
    <div class="container pb-5">
        <div class="row g-3">
            <div class="col-md-6">
                <a href="warranty.php" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background:var(--sec-blue); border:1px solid var(--border-color); transition:border-color 0.2s;" onmouseover="this.style.borderColor='rgba(212,175,55,0.3)'" onmouseout="this.style.borderColor='var(--border-color)'">
                    <div style="width:44px;height:44px;background:rgba(212,175,55,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-shield-halved text-gold"></i>
                    </div>
                    <div>
                        <div style="color:white;font-weight:600;font-size:0.9rem;">Warranty Policy</div>
                        <div style="color:#888;font-size:0.8rem;">24-month coverage on all timepieces</div>
                    </div>
                    <i class="fas fa-chevron-right text-gold ms-auto" style="font-size:0.75rem;"></i>
                </a>
            </div>
            <div class="col-md-6">
                <a href="returns.php" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background:var(--sec-blue); border:1px solid var(--border-color); transition:border-color 0.2s;" onmouseover="this.style.borderColor='rgba(212,175,55,0.3)'" onmouseout="this.style.borderColor='var(--border-color)'">
                    <div style="width:44px;height:44px;background:rgba(212,175,55,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-rotate-left text-gold"></i>
                    </div>
                    <div>
                        <div style="color:white;font-weight:600;font-size:0.9rem;">Returns &amp; Refunds</div>
                        <div style="color:#888;font-size:0.8rem;">14-day hassle-free return window</div>
                    </div>
                    <i class="fas fa-chevron-right text-gold ms-auto" style="font-size:0.75rem;"></i>
                </a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
