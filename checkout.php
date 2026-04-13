<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Power Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/global.css">    

    <style>
        .checkout-container { display: flex; flex-wrap: wrap; min-height: 100vh; }
        .main-content { flex: 1; padding: 3rem 5%; border-right: 1px solid var(--border-color); }
        .sidebar-summary { flex: 0 0 42%; background-color: #0f1724; padding: 3rem 5%; border-left: 1px solid var(--border-color); }
        
        .breadcrumb { margin-bottom: 2rem; }
        .breadcrumb-item + .breadcrumb-item::before { color: var(--text-faded); font-size: 0.8rem; }
        .breadcrumb a { color: var(--chp-gold); font-size: 0.8rem; font-weight: 500; }
        .breadcrumb-item.active { color: var(--text-light); font-weight: 500; font-size: 0.8rem; }

        .form-floating > .form-control, .form-floating > .form-select { background-color: var(--input-bg); border: 1px solid var(--border-color); color: white; font-size: 0.9rem; border-radius: 6px; height: calc(3.2rem + 2px); min-height: calc(3.2rem + 2px); }
        .form-floating > label { padding: 0.8rem 0.75rem; color: #888; font-size: 0.85rem; }
        .form-floating > .form-control:focus, .form-floating > .form-select:focus { background-color: var(--input-bg); border-color: var(--chp-gold); color: white; box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2); }
        .form-control::placeholder { color: transparent; }
        .form-floating > .form-control:focus ~ label, .form-floating > .form-control:not(:placeholder-shown) ~ label { color: var(--chp-gold); transform: scale(.85) translateY(-0.75rem) translateX(0.15rem); }

        .radio-card-group { border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden; background-color: var(--input-bg); }
        .radio-card { padding: 1rem; display: flex; align-items: center; border-bottom: 1px solid var(--border-color); cursor: pointer; transition: background 0.2s; margin: 0; }
        .radio-card:last-child { border-bottom: none; }
        .radio-card:hover { background-color: #242e42; }
        .radio-card input[type="radio"] { accent-color: var(--chp-gold); width: 1.1em; height: 1.1em; margin-right: 0.8rem; cursor: pointer; }

        .product-thumbnail-wrapper { position: relative; width: 64px; height: 64px; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; background: rgba(255,255,255,0.02); padding: 4px; }
        .product-thumbnail-wrapper img { width: 100%; height: 100%; object-fit: contain; }
        .product-badge { position: absolute; top: -8px; right: -8px; background-color: rgba(212, 175, 55, 0.9); color: #000; border-radius: 50%; width: 20px; height: 20px; font-size: 0.7rem; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .products-list { max-height: 40vh; overflow-y: auto; padding-right: 10px; padding-top: 7px; }
        .products-list::-webkit-scrollbar { width: 4px; }
        .products-list::-webkit-scrollbar-track { background: transparent; }
        .products-list::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        .mobile-header { display: none; padding: 1.25rem 5%; border-bottom: 1px solid var(--border-color); text-align: center; background-color: var(--prm-blue); }
        .order-summary-toggle { background-color: #0f1724; border-bottom: 1px solid var(--border-color); padding: 1.25rem 5%; display: none; color: var(--chp-gold); cursor: pointer; font-size: 0.95rem; }
        .order-summary-toggle .fa-chevron-down { transition: transform 0.3s; }
        .order-summary-toggle[aria-expanded="true"] .fa-chevron-down { transform: rotate(180deg); }

        .error-message { background: rgba(231, 76, 60, 0.1); border: 1px solid var(--danger-red); color: var(--danger-red); padding: 0.75rem; border-radius: 6px; font-size: 0.85rem; display: none; }

        @media (max-width: 991px) {
            .checkout-container { flex-direction: column-reverse; }
            .mobile-header { display: block; }
            .order-summary-toggle { display: flex; justify-content: space-between; align-items: center; }
            .sidebar-summary { flex: 0 0 100%; border-left: none; border-bottom: 1px solid var(--border-color); padding: 1.5rem 5%; background-color: #0f1724; }
            .sidebar-summary:not(.show) { display: none; }
            .main-content { border-right: none; padding: 1.5rem 5%; }
            .desktop-logo { display: none !important; }
        }
        @media (max-width: 576px) {
            .radio-card { font-size: 0.85rem; padding: 0.8rem 1rem; }
            .form-floating > label { font-size: 0.8rem; }
        }
    </style>
</head>
<body>

    <div class="mobile-header">
        <a href="index.php" class="text-decoration-none">
            <img src="assets/images/brand-logos/logo5.png" alt="Power Watch" class="brand-logo-img">
        </a>
    </div>

    <div class="order-summary-toggle" data-bs-toggle="collapse" data-bs-target="#mobileSummary" aria-expanded="false">
        <div class="d-flex align-items-center">
            <i class="fas fa-shopping-cart me-2"></i>
            <span class="me-2">Show order summary</span>
            <i class="fas fa-chevron-down small"></i>
        </div>
        <div class="fw-bold fs-5" id="mobileTotalDisplay">LKR 0.00</div>
    </div>

    <div class="checkout-container">
        
        <div class="main-content">
            
            <div class="mb-4 desktop-logo">
                <a href="index.php" class="text-decoration-none">
                    <img src="assets/images/brand-logos/logo5.png" alt="Power Watch" class="brand-logo-img mb-2">
                </a>
            </div>

            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php">Cart</a></li>
                    <li class="breadcrumb-item active">Information & Payment</li>
                </ol>
            </nav>

            <form id="checkoutForm">
                <section class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <h5 class="mb-2">Contact Information</h5>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email address</label>
                    </div>
                </section>

                <section class="mb-4">
                    <h5 class="mb-3">Delivery Address</h5>
                    
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" required>
                                <label for="fname">First name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" required>
                                <label for="lname">Last name</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                        <label for="address">Address</label>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="apartment" name="apartment" placeholder="Apartment">
                        <label for="apartment">Apartment, suite, etc. (optional)</label>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                                <label for="city">City</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="postal" name="postal" placeholder="Postal Code">
                                <label for="postal">Postal code (optional)</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone" required>
                        <label for="phone">Phone</label>
                    </div>
                </section>

                <section class="mb-4">
                    <!-- <h5 class="mb-1">Payment</h5>
                    <p class="text-faded small mb-3">All transactions are secure and encrypted.</p>
                    
                    <div class="radio-card-group">
                        <label class="radio-card justify-content-between">
                            <div class="d-flex align-items-center">
                                <input type="radio" name="paymentMethod" value="card" checked required>
                                <span>Credit/Debit Card</span>
                            </div>
                            <div class="d-flex gap-2 text-faded">
                                <i class="fab fa-cc-visa fa-lg"></i>
                                <i class="fab fa-cc-mastercard fa-lg"></i>
                            </div>
                        </label>

                        <label class="radio-card justify-content-between">
                            <div class="d-flex align-items-center">
                                <input type="radio" name="paymentMethod" value="koko" required>
                                <span>Pay with <span style="color:#6F95E8; font-weight:bold;">KOKO</span></span>
                            </div>
                            <span class="badge bg-secondary opacity-75 fw-normal" style="font-size: 0.7rem;">3 Installments</span>
                        </label>

                        <label class="radio-card">
                            <div class="d-flex align-items-center">
                                <input type="radio" name="paymentMethod" value="cod" required>
                                <span>Cash on Delivery (COD)</span>
                            </div>
                        </label>
                    </div> -->
                    <h5 class="text-white font-oswald mb-3 mt-5"><i class="fas fa-credit-card text-gold me-2"></i> Payment Method</h5>
                    
                    <div class="radio-card-group mb-4">
                        <label class="radio-card">
                            <input type="radio" name="paymentMethod" value="card" checked>
                            <div class="ms-2">
                                <span class="d-block fw-bold text-white mb-1">Direct Bank Transfer</span>
                                <span class="text-muted small" style="line-height: 1.4; display: block;">
                                    Place your order now and transfer the funds directly to our bank account. Instructions and account details will be provided on the next screen.
                                </span>
                            </div>
                        </label>

                        <label class="radio-card">
                            <input type="radio" name="paymentMethod" value="cod">
                            <div class="ms-2">
                                <span class="d-block fw-bold text-white mb-1">Cash on Delivery (COD)</span>
                                <span class="text-muted small" style="line-height: 1.4; display: block;">
                                    Pay with cash directly to the courier when your watch is delivered to your doorstep.
                                </span>
                            </div>
                        </label>
                    </div>
                </section>

                <div class="error-message mb-3" id="errorMessage">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    <span id="errorText"></span>
                </div>

                <div class="d-flex flex-column-reverse flex-md-row justify-content-between align-items-center mt-4 pt-2">
                    <a href="index.php" class="mt-4 mt-md-0 small"><i class="fas fa-chevron-left me-2"></i>Return to shop</a>
                    <button type="submit" class="btn btn-gold px-5 w-md-auto" id="submitBtn">
                        <i class="fas fa-lock me-2 opacity-75"></i> Place Order
                    </button>
                </div>
                
                <div class="mt-5 pt-4 border-top border-secondary text-secondary text-center text-md-start" style="font-size: 0.75rem;">
                    <a href="#" class="text-secondary me-3">Refund policy</a>
                    <a href="#" class="text-secondary me-3">Shipping policy</a>
                    <a href="#" class="text-secondary me-3">Privacy policy</a>
                    <a href="#" class="text-secondary">Terms of service</a>
                </div>
            </form>
        </div>

        <div class="sidebar-summary collapse d-lg-block" id="mobileSummary">
            
            <div class="products-list mb-3" id="cartItemsList">
                <div class="text-center py-5" id="emptyCart">
                    <i class="fas fa-shopping-cart mb-3 text-faded" style="font-size: 3rem; opacity: 0.5;"></i>
                    <p class="text-faded small">Your cart is empty</p>
                    <a href="index.php" class="btn btn-sm btn-outline-gold mt-2 px-4 rounded-pill">Start Shopping</a>
                </div>
            </div>

            <div class="d-flex gap-2 my-4">
                <div class="form-floating flex-grow-1">
                    <input type="text" class="form-control" id="discountCode" placeholder="Discount code" style="height: calc(3rem + 2px); min-height: calc(3rem + 2px);">
                    <label for="discountCode">Discount code</label>
                </div>
                <button type="button" class="btn btn-secondary px-4" style="background-color: #333; border: 1px solid var(--border-color); color: #ccc; font-weight: 500;">Apply</button>
            </div>

            <div class="border-top border-secondary pt-3">
                <div class="d-flex justify-content-between mb-2 text-faded small">
                    <span>Subtotal (<span id="itemCount">0</span> items)</span>
                    <span id="subtotalAmount" class="text-light">LKR 0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-faded small">
                    <span>Shipping</span>
                    <span class="text-light">Free</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-end mt-3 pt-3 border-top border-secondary">
                <span class="mb-0 text-light" style="font-size: 1.1rem;">Total</span>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="text-faded small">LKR</span>
                    <span class="font-oswald text-gold" id="totalAmount" style="font-size: 1.8rem; font-weight: 500;">0.00</span>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cart.js"></script>

    <script>
        // Load cart items on page load
        document.addEventListener('DOMContentLoaded', () => {
            renderCartItems();
        });

        // Render cart items in sidebar
        function renderCartItems() {
            const items = cart.getItems();
            const container = document.getElementById('cartItemsList');
            const emptyCart = document.getElementById('emptyCart');
            
            if (items.length === 0) {
                emptyCart.style.display = 'block';
                container.querySelectorAll('.cart-item').forEach(el => el.remove());
                updateTotals();
                return;
            }

            emptyCart.style.display = 'none';
            container.innerHTML = '';

            items.forEach((item, index) => {
                const itemHtml = `
                    <div class="d-flex align-items-center mb-3 cart-item">
                        <div class="product-thumbnail-wrapper flex-shrink-0">
                            <div class="product-badge">${item.quantity}</div>
                            <img src="${item.image}" alt="${item.name}">
                        </div>
                        <div class="ms-3 flex-grow-1" style="min-width: 0;">
                            <h6 class="mb-1 text-white" style="font-size: 0.85rem; font-weight: 500; word-break: break-word; white-space: normal; line-height: 1.4;">${item.name}</h6>
                            ${item.options && Object.keys(item.options).length > 0 ? 
                                `<small class="text-faded d-block" style="font-size: 0.75rem;">${Object.entries(item.options).map(([k,v]) => `${v}`).join(', ')}</small>` 
                                : ''}
                        </div>
                        <div class="text-end ms-2 flex-shrink-0">
                            <div class="fw-bold text-white" style="font-size: 0.9rem;">${cart.formatCurrency(item.price * item.quantity)}</div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHtml);
            });

            updateTotals();
        }

        // Update totals
        function updateTotals() {
            const subtotal = cart.getSubtotal();
            const count = cart.getCount();
            const formattedTotal = cart.formatCurrency(subtotal);
            
            document.getElementById('itemCount').textContent = count;
            document.getElementById('subtotalAmount').textContent = formattedTotal;
            document.getElementById('totalAmount').textContent = subtotal.toLocaleString('en-LK', {minimumFractionDigits: 2});
            
            // Update Mobile Toggle Header
            const mobileTotal = document.getElementById('mobileTotalDisplay');
            if(mobileTotal) mobileTotal.textContent = formattedTotal;
        }

        // Handle form submission
        document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const items = cart.getItems();
            if (items.length === 0) {
                showError('Your cart is empty. Please add items before checkout.');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';

            const formData = new FormData(e.target);
            formData.append('cart', JSON.stringify(items));
            formData.append('subtotal', cart.getSubtotal());
            formData.append('total', cart.getSubtotal());

            try {
                const response = await fetch('admin/actions/create-order.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    cart.clearCart();
                    // encodeURIComponent safely converts the '#' so PHP can read it!
                    window.location.href = `order-success.php?order=${encodeURIComponent(data.order_number)}`;
                } else {
                    showError(data.message || 'Failed to place order. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Connection error. Please check your internet and try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // Show error message
        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            errorText.textContent = message;
            errorDiv.style.display = 'block';
            
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>