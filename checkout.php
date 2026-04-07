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

    <style>
        :root {
            --prm-blue: #0A111F;
            --sec-blue: #111b2e;
            --chp-gold: #D4AF37;
            --chp-gold-hover: #b5952f;
            --text-light: #f8f9fa;
            --text-muted: #adb5bd;
            --border-color: #2d3748;
            --input-bg: #1a2332;
            --success-green: #2ecc71;
            --danger-red: #e74c3c;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--prm-blue);
            color: var(--text-light);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        .checkout-container {
            display: flex;
            flex-wrap: wrap;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            padding: 2rem 5%;
            border-right: 1px solid var(--border-color);
        }

        .sidebar-summary {
            flex: 0 0 40%;
            background-color: #0f1724;
            padding: 2rem 5%;
            border-left: 1px solid var(--border-color);
        }

        a { color: var(--chp-gold); text-decoration: none; transition: 0.3s; }
        a:hover { color: var(--chp-gold-hover); }
        
        .breadcrumb-item + .breadcrumb-item::before { color: var(--text-muted); }
        .breadcrumb a { color: var(--text-muted); font-size: 0.9rem; }
        .breadcrumb-item.active { color: var(--text-light); font-weight: 600; }

        .form-control, .form-select {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
            padding: 0.8rem;
            border-radius: 6px;
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg);
            border-color: var(--chp-gold);
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.15);
        }

        .form-control::placeholder { color: #6c757d; }
        
        .form-floating label { color: #888; }
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--chp-gold);
        }

        .radio-card-group {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
        }

        .radio-card {
            background-color: var(--input-bg);
            padding: 1rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: background 0.2s;
        }

        .radio-card:last-child { border-bottom: none; }
        .radio-card:hover { background-color: #242e42; }
        
        .radio-card input[type="radio"] {
            accent-color: var(--chp-gold);
            width: 1.2em;
            height: 1.2em;
            margin-right: 1rem;
        }

        .btn-gold {
            background-color: var(--chp-gold);
            color: #000;
            font-weight: 700;
            padding: 1rem 2rem;
            border-radius: 8px;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            transition: all 0.3s;
        }

        .btn-gold:hover {
            background-color: var(--chp-gold-hover);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-gold:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .product-thumbnail-wrapper {
            position: relative;
            width: 70px;
            height: 70px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #fff;
            padding: 5px;
        }

        .product-thumbnail-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .product-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--chp-gold);
            color: #000;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .products-list {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .products-list::-webkit-scrollbar { width: 5px; }
        .products-list::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .products-list::-webkit-scrollbar-thumb { background: #444; border-radius: 5px; }

        .empty-cart {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-cart i {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .error-message {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid var(--danger-red);
            color: var(--danger-red);
            padding: 0.75rem;
            border-radius: 6px;
            font-size: 0.9rem;
            display: none;
        }

        @media (max-width: 991px) {
            .sidebar-summary {
                flex: 0 0 100%;
                border-left: none;
                border-top: 1px solid var(--border-color);
            }
            
            .main-content {
                border-right: none;
            }
        }

        @media (max-width: 576px) {
            .main-content, .sidebar-summary {
                padding: 1.5rem 4%;
            }
        }
    </style>
</head>
<body>

    <div class="checkout-container">
        
        <!-- Left Side: Checkout Form -->
        <div class="main-content">
            
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="index.php" class="navbar-brand" style="text-decoration: none;">
                    <span>POWER <span class="text-gold">WATCH</span></span>
                </a>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ol>
                </nav>
            </div>

            <form id="checkoutForm">
                <!-- Contact Information -->
                <section class="mb-5">
                    <h5 class="mb-3">Contact Information</h5>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email address</label>
                    </div>
                </section>

                <!-- Shipping Address -->
                <section class="mb-5">
                    <h5 class="mb-3">Shipping Address</h5>
                    
                    <div class="row g-3 mb-3">
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

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                        <label for="address">Address</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="apartment" name="apartment" placeholder="Apartment">
                        <label for="apartment">Apartment, suite, etc. (optional)</label>
                    </div>

                    <div class="row g-3 mb-3">
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

                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone" required>
                        <label for="phone">Phone</label>
                    </div>
                </section>

                <!-- Payment -->
                <section class="mb-5">
                    <h5 class="mb-1">Payment Method</h5>
                    <p class="text-muted small mb-3">All transactions are secure and encrypted.</p>
                    
                    <div class="radio-card-group">
                        <label class="radio-card justify-content-between">
                            <div class="d-flex align-items-center">
                                <input type="radio" name="paymentMethod" value="card" checked required>
                                <span>Credit/Debit Card</span>
                            </div>
                            <div class="d-flex gap-1">
                                <i class="fab fa-cc-visa fa-lg"></i>
                                <i class="fab fa-cc-mastercard fa-lg"></i>
                            </div>
                        </label>

                        <label class="radio-card justify-content-between">
                            <div class="d-flex align-items-center">
                                <input type="radio" name="paymentMethod" value="koko" required>
                                <span>Pay with <span style="color:#6F95E8; font-weight:bold;">KOKO</span></span>
                            </div>
                            <span class="badge bg-secondary">3 Installments</span>
                        </label>

                        <label class="radio-card">
                            <div class="d-flex align-items-center">
                                <input type="radio" name="paymentMethod" value="cod" required>
                                <span>Cash on Delivery (COD)</span>
                            </div>
                        </label>
                    </div>
                </section>

                <!-- Error Message -->
                <div class="error-message mb-4" id="errorMessage">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="errorText"></span>
                </div>

                <!-- Actions -->
                <div class="d-flex flex-column-reverse flex-md-row justify-content-between align-items-center mt-4">
                    <a href="index.php" class="mt-3 mt-md-0"><i class="fas fa-chevron-left me-2"></i>Continue Shopping</a>
                    <button type="submit" class="btn btn-gold px-5 py-3 w-md-auto" id="submitBtn">
                        <i class="fas fa-lock me-2"></i> Place Order
                    </button>
                </div>
                
                <div class="mt-5 pt-3 border-top border-secondary text-muted small">
                    <a href="#" class="text-muted me-3">Refund policy</a>
                    <a href="#" class="text-muted me-3">Shipping policy</a>
                    <a href="#" class="text-muted me-3">Privacy policy</a>
                    <a href="#" class="text-muted">Terms of service</a>
                </div>
            </form>
        </div>

        <!-- Right Side: Order Summary -->
        <div class="sidebar-summary">
            
            <h5 class="mb-4">Order Summary</h5>

            <!-- Products List -->
            <div class="products-list mb-3" id="cartItemsList">
                <div class="empty-cart" id="emptyCart">
                    <i class="fas fa-shopping-cart"></i>
                    <h6 class="text-muted">Your cart is empty</h6>
                    <a href="index.php" class="btn btn-outline-gold mt-3">Start Shopping</a>
                </div>
            </div>

            <!-- Totals -->
            <div class="border-top border-secondary pt-4">
                <div class="d-flex justify-content-between mb-2 text-muted">
                    <span>Subtotal (<span id="itemCount">0</span> items)</span>
                    <span id="subtotalAmount">LKR 0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-muted">
                    <span>Shipping</span>
                    <span class="small">Free</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
                <span class="h5 mb-0">Total</span>
                <div class="d-flex align-items-baseline">
                    <small class="text-muted me-2">LKR</small>
                    <span class="h3 mb-0 text-gold" id="totalAmount">0.00</span>
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
                    <div class="d-flex align-items-center mb-4 cart-item">
                        <div class="product-thumbnail-wrapper">
                            <div class="product-badge">${item.quantity}</div>
                            <img src="${item.image}" alt="${item.name}">
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            ${item.options && Object.keys(item.options).length > 0 ? 
                                `<small class="text-muted d-block">${Object.entries(item.options).map(([k,v]) => `${k}: ${v}`).join(', ')}</small>` 
                                : ''}
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-gold">${cart.formatCurrency(item.price * item.quantity)}</div>
                            <button class="btn btn-link text-danger p-0 mt-1 small" onclick="removeCartItem(${index})">Remove</button>
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
            
            document.getElementById('itemCount').textContent = count;
            document.getElementById('subtotalAmount').textContent = cart.formatCurrency(subtotal);
            document.getElementById('totalAmount').textContent = subtotal.toFixed(2);
        }

        // Remove item from cart
        function removeCartItem(index) {
            cart.removeItem(index);
            renderCartItems();
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
                const response = await fetch('actions/create-order.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Clear cart
                    cart.clearCart();
                    // Redirect to success page
                    window.location.href = `order-success.php?order=${data.order_number}`;
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
            
            // Scroll to error
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>
