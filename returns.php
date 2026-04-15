<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns & Refunds - Power Watch</title>
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

        /* Quick summary — Baymard: users look for policy info immediately */
        .returns-summary {
            background: var(--sec-blue);
            border: 1px solid rgba(212,175,55,0.25);
            border-radius: 14px;
            padding: 1.5rem 2rem;
            margin-bottom: 2.5rem;
        }
        .summary-item { text-align: center; padding: 0.5rem; }
        .summary-item .value { font-family: 'Oswald', sans-serif; font-size: 1.6rem; color: var(--chp-gold); line-height: 1; display: block; }
        .summary-item .label { font-size: 0.78rem; color: #aaa; margin-top: 5px; display: block; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-divider { width: 1px; background: var(--border-color); align-self: stretch; margin: 0.25rem 0; }

        /* Policy card */
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

        /* Eligibility checklist */
        .checklist { list-style: none; padding: 0; margin: 0; }
        .checklist li { display: flex; align-items: flex-start; gap: 10px; padding: 0.55rem 0; border-bottom: 1px solid rgba(255,255,255,0.04); color: #bbb; font-size: 0.88rem; line-height: 1.5; }
        .checklist li:last-child { border-bottom: none; }
        .checklist li i { margin-top: 2px; flex-shrink: 0; }
        .checklist li.pass i { color: #2ecc71; }
        .checklist li.fail i { color: #e74c3c; }

        /* Process steps */
        .process-steps { display: flex; flex-direction: column; gap: 0; }
        .process-step { display: flex; align-items: flex-start; gap: 14px; position: relative; padding-bottom: 1.5rem; }
        .process-step:last-child { padding-bottom: 0; }
        .process-step::before {
            content: '';
            position: absolute;
            left: 16px;
            top: 34px;
            bottom: 0;
            width: 2px;
            background: rgba(212,175,55,0.2);
        }
        .process-step:last-child::before { display: none; }
        .step-num {
            width: 34px; height: 34px; flex-shrink: 0;
            background: var(--chp-gold); color: #000;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem; font-family: 'Oswald', sans-serif;
            position: relative; z-index: 1;
        }
        .process-step strong { color: white; font-size: 0.9rem; display: block; margin-bottom: 3px; }
        .process-step p { color: #aaa; font-size: 0.84rem; margin: 0; line-height: 1.5; }

        /* Warning box for engraving */
        .warning-box {
            background: rgba(231,76,60,0.08);
            border: 1px solid rgba(231,76,60,0.3);
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
            margin-top: 1rem;
        }
        .warning-box p { color: #ccc; font-size: 0.87rem; margin: 0; line-height: 1.6; }
        .warning-box strong { color: #e74c3c; }

        .highlight-box {
            background: rgba(212,175,55,0.07);
            border-left: 3px solid var(--chp-gold);
            border-radius: 0 8px 8px 0;
            padding: 1rem 1.25rem;
            margin: 1rem 0;
        }
        .highlight-box p { color: #ccc; font-size: 0.87rem; margin: 0; line-height: 1.6; }

        /* Refund options comparison */
        .refund-options { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.5rem; }
        .refund-option {
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
            background: rgba(255,255,255,0.02);
            text-align: center;
        }
        .refund-option i { font-size: 1.4rem; margin-bottom: 8px; display: block; }
        .refund-option h6 { color: white; font-size: 0.85rem; margin-bottom: 5px; font-weight: 700; }
        .refund-option p { color: #aaa; font-size: 0.78rem; margin: 0; }

        /* Sidebar */
        .sidebar-card {
            background: var(--sec-blue);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
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
        .contact-row a { color: #bbb; font-size: 0.83rem; transition: color 0.2s; }
        .contact-row a:hover { color: var(--chp-gold); }
        .contact-row span { color: #bbb; font-size: 0.83rem; }

        @media (max-width: 767px) {
            .policy-card { padding: 1.5rem 1.25rem; }
            .refund-options { grid-template-columns: 1fr; }
            .returns-summary { padding: 1.25rem; }
            .summary-divider { display: none; }
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
                    <li class="breadcrumb-item active">Returns &amp; Refunds</li>
                </ol>
            </nav>
            <h1 class="font-oswald text-white mb-2" style="font-size:2.4rem; letter-spacing:2px;">
                <i class="fas fa-rotate-left text-gold me-3" style="font-size:2rem;"></i>Returns &amp; Refunds
            </h1>
            <p class="text-muted mb-0" style="font-size:0.9rem; max-width:520px;">
                We want every purchase to be perfect. If it isn't, here's our straightforward, no-hassle return process.
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">

            <!-- Quick summary bar — SwiftOtter: show key facts immediately at top -->
            <div class="returns-summary">
                <div class="d-flex align-items-center justify-content-around flex-wrap gap-3">
                    <div class="summary-item">
                        <span class="value">14</span>
                        <span class="label">Day Return Window</span>
                    </div>
                    <div class="summary-divider d-none d-sm-block"></div>
                    <div class="summary-item">
                        <span class="value">5–7</span>
                        <span class="label">Days to Process Refund</span>
                    </div>
                    <div class="summary-divider d-none d-sm-block"></div>
                    <div class="summary-item">
                        <span class="value">100%</span>
                        <span class="label">Refund on Eligible Items</span>
                    </div>
                    <div class="summary-divider d-none d-sm-block"></div>
                    <div class="summary-item">
                        <span class="value" style="font-size:1.2rem;"><i class="fas fa-lock"></i></span>
                        <span class="label">Original Packaging Required</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Main content -->
                <div class="col-lg-8">

                    <!-- Return window -->
                    <div class="policy-card">
                        <div class="policy-card-title"><i class="fas fa-calendar"></i> Return Window</div>
                        <p>You have <strong>14 days from the date of delivery</strong> to initiate a return. After 14 days, we are unable to accept returns unless the item is under an active warranty claim.</p>
                        <div class="highlight-box">
                            <p><i class="fas fa-info-circle text-gold me-2"></i> The return window starts from the day your tracking shows "Delivered" — not the order date. Keep your delivery confirmation as reference.</p>
                        </div>
                    </div>

                    <!-- Eligibility -->
                    <div class="policy-card">
                        <div class="policy-card-title"><i class="fas fa-clipboard-check"></i> Eligibility Conditions</div>
                        <p class="mb-3">To qualify for a return and full refund, your timepiece <strong>must meet all of the following conditions:</strong></p>
                        <ul class="checklist">
                            <li class="pass"><i class="fas fa-check-circle"></i> Returned within 14 days of confirmed delivery</li>
                            <li class="pass"><i class="fas fa-check-circle"></i> Completely unworn — no signs of use, adjustment, or sizing</li>
                            <li class="pass"><i class="fas fa-check-circle"></i> All protective films and stickers intact and unremoved</li>
                            <li class="pass"><i class="fas fa-check-circle"></i> Original box, tags, and all packaging materials included</li>
                            <li class="pass"><i class="fas fa-check-circle"></i> No scratches, dents, or alterations of any kind</li>
                            <li class="fail"><i class="fas fa-times-circle"></i> Custom-engraved items — <strong>Final Sale, no returns accepted</strong></li>
                            <li class="fail"><i class="fas fa-times-circle"></i> Items returned without original packaging</li>
                            <li class="fail"><i class="fas fa-times-circle"></i> Items with removed or damaged protective stickers</li>
                        </ul>
                        <div class="warning-box mt-3">
                            <p><i class="fas fa-exclamation-triangle me-2"></i> <strong>Custom Engraving is Final Sale.</strong> Any timepiece customized with engraving — text, initials, or symbols — is strictly non-returnable and non-refundable under any circumstances. This policy is in place from the moment engraving is confirmed at checkout.</p>
                        </div>
                    </div>

                    <!-- Refund options -->
                    <div class="policy-card">
                        <div class="policy-card-title"><i class="fas fa-wallet"></i> Your Refund Options</div>
                        <p class="mb-3">For approved returns, you can choose how you'd like your refund:</p>
                        <div class="refund-options">
                            <div class="refund-option">
                                <i class="fas fa-building-columns text-gold"></i>
                                <h6>Bank Transfer</h6>
                                <p>Full refund back to your original bank account within 5–7 business days of us receiving the item.</p>
                            </div>
                            <div class="refund-option">
                                <i class="fas fa-tag" style="color:#7191D9;"></i>
                                <h6>Store Credit</h6>
                                <p>Receive <strong style="color:#7191D9;">110% of your purchase value</strong> as store credit — an extra 10% bonus for choosing this option.</p>
                            </div>
                        </div>
                        <p class="mt-3 mb-0" style="font-size:0.82rem; color:#777;">Shipping costs (original and return) are non-refundable unless the return is due to a Power Watch error or defective item.</p>
                    </div>

                    <!-- How to return -->
                    <div class="policy-card">
                        <div class="policy-card-title"><i class="fas fa-box-open"></i> How to Return an Item</div>
                        <div class="process-steps">
                            <div class="process-step">
                                <div class="step-num">1</div>
                                <div>
                                    <strong>Initiate Your Return Request</strong>
                                    <p>Email <a href="mailto:returns@powerwatch.lk" class="text-gold">returns@powerwatch.lk</a> or WhatsApp <a href="https://wa.me/94771234567" class="text-gold">+94 77 123 4567</a> with your order number and reason for return. We'll respond within 1 business day.</p>
                                </div>
                            </div>
                            <div class="process-step">
                                <div class="step-num">2</div>
                                <div>
                                    <strong>Receive Return Authorization</strong>
                                    <p>We'll send you a Return Authorization Number (RAN) and the return address. <strong>Items returned without an RAN will not be accepted.</strong> Do not ship until you have this number.</p>
                                </div>
                            </div>
                            <div class="process-step">
                                <div class="step-num">3</div>
                                <div>
                                    <strong>Package &amp; Ship Securely</strong>
                                    <p>Pack the timepiece carefully in its original box, write the RAN clearly on the outer packaging, and ship to the provided address. We recommend using a tracked courier service.</p>
                                </div>
                            </div>
                            <div class="process-step">
                                <div class="step-num">4</div>
                                <div>
                                    <strong>Inspection &amp; Refund</strong>
                                    <p>Once received, our team inspects the item within 2 business days. If approved, your refund or store credit is processed within <strong>5–7 business days</strong>. You'll receive email confirmation at every stage.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Non-returnable items -->
                    <div class="policy-card">
                        <div class="policy-card-title"><i class="fas fa-ban"></i> Non-Returnable Items</div>
                        <ul>
                            <li><strong>Custom-engraved timepieces</strong> — Final Sale, no exceptions</li>
                            <li>Items returned after 14 days from delivery</li>
                            <li>Items showing evidence of wear, use, or sizing adjustments</li>
                            <li>Items with damaged, removed, or missing original packaging or tags</li>
                            <li>Items purchased during clearance or final-sale promotions (marked as such at checkout)</li>
                        </ul>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar-card" style="position:sticky; top:90px;">
                        <h6><i class="fas fa-headset text-gold me-2"></i> Start a Return</h6>
                        <p style="color:#aaa; font-size:0.83rem; margin-bottom:1rem; line-height:1.5;">Have your order number ready. Our team responds within 1 business day.</p>
                        <div class="contact-row"><i class="fab fa-whatsapp"></i><a href="https://wa.me/94771234567?text=Hi%2C%20I%20would%20like%20to%20initiate%20a%20return.%20My%20order%20number%20is%3A">+94 77 123 4567 (WhatsApp)</a></div>
                        <div class="contact-row"><i class="fas fa-envelope"></i><a href="mailto:returns@powerwatch.lk">returns@powerwatch.lk</a></div>
                        <div class="contact-row"><i class="fas fa-clock"></i><span>Mon – Sat, 9am – 6pm</span></div>
                        <a href="https://wa.me/94771234567?text=Hi%2C%20I%20would%20like%20to%20initiate%20a%20return." target="_blank" class="btn btn-gold w-100 mt-3" style="font-size:0.85rem;">
                            <i class="fab fa-whatsapp me-2"></i> Initiate Return via WhatsApp
                        </a>
                    </div>

                    <div class="sidebar-card">
                        <h6><i class="fas fa-circle-question text-gold me-2"></i> Quick Answers</h6>
                        <div style="display:flex; flex-direction:column; gap:0;">
                            <details class="py-2" style="border-bottom:1px solid rgba(255,255,255,0.05);">
                                <summary style="color:#bbb; font-size:0.83rem; cursor:pointer; list-style:none; display:flex; justify-content:space-between; align-items:center;">
                                    Who pays return shipping? <i class="fas fa-chevron-down" style="font-size:0.65rem; color:#666;"></i>
                                </summary>
                                <p style="color:#888; font-size:0.8rem; margin:8px 0 4px; line-height:1.5;">You cover return shipping unless the return is due to our error or a defective item, in which case we cover it fully.</p>
                            </details>
                            <details class="py-2" style="border-bottom:1px solid rgba(255,255,255,0.05);">
                                <summary style="color:#bbb; font-size:0.83rem; cursor:pointer; list-style:none; display:flex; justify-content:space-between; align-items:center;">
                                    Can I exchange instead of refund? <i class="fas fa-chevron-down" style="font-size:0.65rem; color:#666;"></i>
                                </summary>
                                <p style="color:#888; font-size:0.8rem; margin:8px 0 4px; line-height:1.5;">Yes. Mention this when initiating your return and we'll process an exchange for the same or a different model of equal value.</p>
                            </details>
                            <details class="py-2">
                                <summary style="color:#bbb; font-size:0.83rem; cursor:pointer; list-style:none; display:flex; justify-content:space-between; align-items:center;">
                                    What if I received a wrong item? <i class="fas fa-chevron-down" style="font-size:0.65rem; color:#666;"></i>
                                </summary>
                                <p style="color:#888; font-size:0.8rem; margin:8px 0 4px; line-height:1.5;">Contact us immediately. We will cover all costs, arrange collection, and dispatch the correct item at no charge to you.</p>
                            </details>
                        </div>
                    </div>

                    <div class="sidebar-card">
                        <h6><i class="fas fa-book-open text-gold me-2"></i> Related Policies</h6>
                        <a href="warranty.php" class="d-flex align-items-center gap-2 py-2 border-bottom" style="border-color:rgba(255,255,255,0.06)!important; color:#aaa; font-size:0.85rem; transition:color 0.2s;">
                            <i class="fas fa-shield-halved text-gold" style="width:16px;"></i> Warranty Policy
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
