<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warranty Policy - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css">
    <style>
        .policy-hero {
            background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, rgba(212,175,55,0.03) 100%);
            border-bottom: 1px solid rgba(212,175,55,0.2);
            padding: 3.5rem 0 2.5rem;
        }
        .policy-hero .breadcrumb-item a { color: var(--chp-gold); }
        .policy-hero .breadcrumb-item.active { color: #aaa; }
        .breadcrumb-item + .breadcrumb-item::before { color: #555; }

        /* Trust summary bar — visible immediately, reduces anxiety */
        .trust-summary {
            background: var(--sec-blue);
            border: 1px solid rgba(212,175,55,0.25);
            border-radius: 14px;
            padding: 1.5rem 2rem;
            margin-bottom: 2.5rem;
        }
        .trust-item { display: flex; align-items: flex-start; gap: 12px; }
        .trust-item i { color: var(--chp-gold); font-size: 1.2rem; flex-shrink: 0; margin-top: 2px; }
        .trust-item strong { color: white; font-size: 0.9rem; display: block; margin-bottom: 2px; }
        .trust-item span { color: #aaa; font-size: 0.8rem; line-height: 1.4; }

        /* Policy card layout */
        .policy-card {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 2rem 2.25rem;
            margin-bottom: 1.5rem;
            transition: border-color 0.25s;
        }
        .policy-card:hover { border-color: rgba(212,175,55,0.3); }
        .policy-card-title {
            font-family: 'Oswald', sans-serif;
            font-size: 1.1rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.25rem;
        }
        .policy-card-title i { color: var(--chp-gold); font-size: 1rem; }
        .policy-card p, .policy-card li { color: #bbb; font-size: 0.9rem; line-height: 1.7; }
        .policy-card strong { color: #ddd; }
        .policy-card ul { padding-left: 1.2rem; }
        .policy-card ul li { margin-bottom: 6px; }

        /* Covered / Not Covered two-col */
        .coverage-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .coverage-col { border-radius: 10px; padding: 1.25rem 1.5rem; }
        .coverage-col.covered { background: rgba(46,204,113,0.07); border: 1px solid rgba(46,204,113,0.2); }
        .coverage-col.not-covered { background: rgba(231,76,60,0.07); border: 1px solid rgba(231,76,60,0.2); }
        .coverage-col h6 { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.85rem; display: flex; align-items: center; gap: 8px; }
        .coverage-col.covered h6 { color: #2ecc71; }
        .coverage-col.not-covered h6 { color: #e74c3c; }
        .coverage-col ul { padding-left: 1rem; margin: 0; }
        .coverage-col ul li { color: #bbb; font-size: 0.84rem; margin-bottom: 5px; line-height: 1.5; }

        /* Claim steps */
        .claim-steps { display: flex; flex-direction: column; gap: 1rem; margin-top: 0.5rem; }
        .claim-step { display: flex; align-items: flex-start; gap: 14px; }
        .step-num {
            width: 34px; height: 34px; flex-shrink: 0;
            background: var(--chp-gold); color: #000;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem; font-family: 'Oswald', sans-serif;
        }
        .claim-step strong { color: white; font-size: 0.9rem; display: block; margin-bottom: 3px; }
        .claim-step p { color: #aaa; font-size: 0.84rem; margin: 0; line-height: 1.5; }

        /* CTA sidebar card */
        .sidebar-card {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
            position: sticky;
            top: 90px;
        }
        .sidebar-card h6 {
            font-family: 'Oswald', sans-serif;
            font-size: 0.95rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }
        .contact-row { display: flex; align-items: center; gap: 10px; padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .contact-row:last-of-type { border-bottom: none; }
        .contact-row i { color: var(--chp-gold); width: 18px; text-align: center; font-size: 0.9rem; }
        .contact-row span { color: #bbb; font-size: 0.83rem; }
        .contact-row a { color: #bbb; font-size: 0.83rem; transition: color 0.2s; }
        .contact-row a:hover { color: var(--chp-gold); }

        .highlight-box {
            background: rgba(212,175,55,0.07);
            border-left: 3px solid var(--chp-gold);
            border-radius: 0 8px 8px 0;
            padding: 1rem 1.25rem;
            margin: 1rem 0;
        }
        .highlight-box p { color: #ccc; font-size: 0.87rem; margin: 0; line-height: 1.6; }

        @media (max-width: 767px) {
            .policy-card { padding: 1.5rem 1.25rem; }
            .coverage-grid { grid-template-columns: 1fr; }
            .trust-summary { padding: 1.25rem; }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="policy-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0" style="font-size:0.82rem;">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Warranty Policy</li>
                </ol>
            </nav>
            <h1 class="font-oswald text-white mb-2" style="font-size:2.4rem; letter-spacing:2px;">
                <i class="fas fa-shield-halved text-gold me-3" style="font-size:2rem;"></i>Warranty Policy
            </h1>
            <p class="text-muted mb-0" style="font-size:0.9rem; max-width:520px;">
                Every Power Watch timepiece is backed by our commitment to precision and quality. Here's exactly what we stand behind.
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">

            <!-- Trust summary — shown at top, users see it before reading anything -->
            <div class="trust-summary">
                <div class="row g-3">
                    <div class="col-sm-6 col-lg-3">
                        <div class="trust-item">
                            <i class="fas fa-clock"></i>
                            <div><strong>24-Month Coverage</strong><span>International manufacturer warranty on all timepieces</span></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="trust-item">
                            <i class="fas fa-gears"></i>
                            <div><strong>Movement Covered</strong><span>Internal mechanism defects covered in full</span></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="trust-item">
                            <i class="fas fa-bolt"></i>
                            <div><strong>Fast Resolution</strong><span>Claim processed within 5–7 business days</span></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="trust-item">
                            <i class="fas fa-headset"></i>
                            <div><strong>Dedicated Support</strong><span>WhatsApp &amp; email support for all warranty queries</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Main content -->
                <div class="col-lg-8">

                    <!-- Duration -->
                    <div class="policy-card">
                        <div class="policy-card-title"><i class="fas fa-calendar-check"></i> Warranty Duration</div>
                        <p>All Power Watch timepieces are backed by a <strong>24-Month International Warranty</strong> from the verified date of purchase. The warranty is non-transferable and applies to the original purchaser only.</p>
                        <div class="highlight-box">
                            <p><i class="fas fa-info-circle text-gold me-2"></i> Your warranty period begins on the date your order is dispatched, as confirmed in your order confirmation email. Keep this email — it is your proof of purchase.</p>
                        </div>
                    </div>

                    <!-- Covered / Not Covered -->
                    <div class="policy-card">
                        <div class="policy-card-title"><i class="fas fa-list-check"></i> What Is &amp; Isn't Covered</div>
                        <p class="mb-3">Our warranty is designed to protect you from manufacturing defects — not from normal wear and tear. Here's the clear breakdown:</p>
                        <div class="coverage-grid">
                            <div class="coverage-col covered">
                                <h6><i class="fas fa-check-circle"></i> Covered</h6>
                                <ul>
                                    <li>Internal movement &amp; mechanism defects</li>
                                    <li>Manufacturing faults in the case or crown</li>
                                    <li>Faulty timekeeping accuracy (±15 sec/day)</li>
                                    <li>Dead battery on arrival (DOA only)</li>
                                    <li>Dial defects present at time of purchase</li>
                                </ul>
                            </div>
                            <div class="coverage-col not-covered">
                                <h6><i class="fas fa-times-circle"></i> Not Covered</h6>
                                <ul>
                                    <li>Routine battery replacements</li>
                                    <li>Scratches on crystal or case glass</li>
                                    <li>Wear &amp; peeling on straps or plating</li>
                                    <li>Water damage beyond rated ATM</li>
                                    <li>Damage from drops or accidents</li>
                                    <li>Unauthorized repairs or modifications</li>
                                    <li>Custom-engraved timepieces (Final Sale)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- How to claim -->
                    <div class="policy-card">
                        <div class="policy-card-title"><i class="fas fa-paper-plane"></i> How to Claim Your Warranty</div>
                        <p class="mb-3">The process is straightforward. You don't need to return anything upfront — just follow these three steps:</p>
                        <div class="claim-steps">
                            <div class="claim-step">
                                <div class="step-num">1</div>
                                <div>
                                    <strong>Contact Us With Your Order Details</strong>
                                    <p>Email <a href="mailto:warranty@powerwatch.lk" class="text-gold">warranty@powerwatch.lk</a> or WhatsApp <a href="https://wa.me/94771234567" class="text-gold">+94 77 123 4567</a> with your order number (e.g., PWORD123) and a brief description of the defect.</p>
                                </div>
                            </div>
                            <div class="claim-step">
                                <div class="step-num">2</div>
                                <div>
                                    <strong>Submit Photo or Video Evidence</strong>
                                    <p>Attach a clear photo or a short video (under 30 seconds) clearly showing the defect. This helps our team assess the claim remotely and speeds up approval.</p>
                                </div>
                            </div>
                            <div class="claim-step">
                                <div class="step-num">3</div>
                                <div>
                                    <strong>Receive Your Repair Authorization</strong>
                                    <p>Once reviewed (within 2 business days), you'll receive a Warranty Authorization Ticket with full instructions. Approved claims are resolved via repair or replacement within <strong>5–7 business days</strong>.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important notes -->
                    <div class="policy-card">
                        <div class="policy-card-title"><i class="fas fa-triangle-exclamation"></i> Important Notes</div>
                        <ul>
                            <li>Warranty claims without a valid order number or proof of purchase will not be processed.</li>
                            <li>Attempts to repair, open, or modify the watch outside of our authorized service will immediately void the warranty.</li>
                            <li><strong>Custom-engraved timepieces are Final Sale</strong> and are entirely excluded from all warranty and return provisions.</li>
                            <li>Power Watch reserves the right to repair or replace (at our discretion) any item under warranty. If an identical replacement is unavailable, a comparable model will be offered.</li>
                            <li>Shipping costs for returning a warranty claim are covered by Power Watch for approved claims originating within Sri Lanka.</li>
                        </ul>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar-card">
                        <h6><i class="fas fa-headset text-gold me-2"></i> Need Help?</h6>
                        <p style="color:#aaa; font-size:0.83rem; margin-bottom:1rem; line-height:1.5;">Our support team handles all warranty queries directly. Reach us through any of the channels below.</p>
                        <div class="contact-row"><i class="fab fa-whatsapp"></i><a href="https://wa.me/94771234567">+94 77 123 4567</a></div>
                        <div class="contact-row"><i class="fas fa-envelope"></i><a href="mailto:warranty@powerwatch.lk">warranty@powerwatch.lk</a></div>
                        <div class="contact-row"><i class="fas fa-phone"></i><span>011-22264589</span></div>
                        <div class="contact-row"><i class="fas fa-clock"></i><span>Mon – Sat, 9am – 6pm</span></div>
                        <a href="https://wa.me/94771234567?text=Hi%2C%20I%20need%20help%20with%20a%20warranty%20claim." target="_blank" class="btn btn-gold w-100 mt-3" style="font-size:0.85rem;">
                            <i class="fab fa-whatsapp me-2"></i> Start a Warranty Claim
                        </a>
                    </div>

                    <div class="sidebar-card" style="position:static;">
                        <h6><i class="fas fa-book-open text-gold me-2"></i> Related Policies</h6>
                        <a href="returns.php" class="d-flex align-items-center gap-2 py-2 border-bottom" style="border-color:rgba(255,255,255,0.06)!important; color:#aaa; font-size:0.85rem; transition:color 0.2s;">
                            <i class="fas fa-rotate-left text-gold" style="width:16px;"></i> Returns &amp; Refunds
                        </a>
                        <a href="about.php" class="d-flex align-items-center gap-2 py-2" style="color:#aaa; font-size:0.85rem; transition:color 0.2s;">
                            <i class="fas fa-building text-gold" style="width:16px;"></i> About Power Watch
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
