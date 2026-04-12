/**
 * Power Watch - Shopping Cart Manager
 * Handles all cart operations with localStorage persistence
 */

class ShoppingCart {
    constructor() {
        this.cart = this.loadCart();
    }

    // Load cart from localStorage
    loadCart() {
        const saved = localStorage.getItem('powerwatch_cart');
        return saved ? JSON.parse(saved) : [];
    }

    // Save cart to localStorage
    saveCart() {
        localStorage.setItem('powerwatch_cart', JSON.stringify(this.cart));
        this.updateCartBadge();
    }

    // Add item to cart
    addItem(product) {
        const existingIndex = this.cart.findIndex(item => 
            item.id === product.id && 
            JSON.stringify(item.options) === JSON.stringify(product.options)
        );

        if (existingIndex > -1) {
            this.cart[existingIndex].quantity += product.quantity || 1;
        } else {
            this.cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                quantity: product.quantity || 1,
                options: product.options || {}
            });
        }

        this.saveCart();
        return this.cart.length;
    }

    // Update item quantity
    updateQuantity(index, quantity) {
        if (quantity <= 0) {
            this.removeItem(index);
        } else {
            this.cart[index].quantity = quantity;
            this.saveCart();
        }
    }

    // Remove item from cart
    removeItem(index) {
        this.cart.splice(index, 1);
        this.saveCart();
    }

    // Get cart items
    getItems() {
        return this.cart;
    }

    // Get cart count
    getCount() {
        return this.cart.reduce((total, item) => total + item.quantity, 0);
    }

    // Calculate subtotal
    getSubtotal() {
        return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    // Clear cart
    clearCart() {
        this.cart = [];
        this.saveCart();
    }

    // Update cart badge in navbar
    updateCartBadge() {
        const badge = document.getElementById('cartBadge');
        const count = this.getCount();
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }

    // Format currency
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-LK', {
            style: 'currency',
            currency: 'LKR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }
}

// Initialize global cart instance
const cart = new ShoppingCart();

// Function to re-render the side cart UI
function renderSideCart() {
    const cartContainer = document.getElementById('sideCartItems');
    const totalEl = document.getElementById('sideCartTotal');
    const headerCount = document.getElementById('cartHeaderCount');
    
    if(!cartContainer) return;

    const items = cart.getItems();
    const subtotal = cart.getSubtotal();
    
    // Update Header Count
    if(headerCount) headerCount.innerText = `(${cart.getCount()})`;

    // Free Shipping Logic
    const freeShippingThreshold = 15000;
    const progressText = document.getElementById('freeShippingText');
    const progressBar = document.getElementById('freeShippingBar');
    
    if(progressText && progressBar) {
        if (subtotal === 0) {
            progressText.innerHTML = `Spend <span class="text-gold fw-bold">LKR 15,000</span> for Free Shipping!`;
            progressBar.style.width = '0%';
        } else if (subtotal >= freeShippingThreshold) {
            progressText.innerHTML = `<i class="fas fa-truck text-gold me-1"></i> You've unlocked <span class="text-gold fw-bold">Free Shipping!</span>`;
            progressBar.style.width = '100%';
            progressBar.classList.add('bg-success');
            progressBar.classList.remove('bg-gold');
        } else {
            const remaining = freeShippingThreshold - subtotal;
            const percentage = (subtotal / freeShippingThreshold) * 100;
            progressText.innerHTML = `You're <span class="text-gold fw-bold">${cart.formatCurrency(remaining)}</span> away from Free Shipping!`;
            progressBar.style.width = `${percentage}%`;
            progressBar.classList.remove('bg-success');
            progressBar.classList.add('bg-gold');
        }
    }

    if (items.length === 0) {
        cartContainer.innerHTML = `
            <div class="text-center py-5 mt-4">
                <i class="fas fa-shopping-bag text-secondary mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="text-white mb-1 fw-medium">Your cart is empty</p>
                <button class="btn btn-outline-gold mt-3 px-4 rounded-pill" data-bs-dismiss="offcanvas">Start Shopping</button>
            </div>`;
        totalEl.innerText = 'LKR 0.00';
        return;
    }

    cartContainer.innerHTML = '';
    items.forEach((item, index) => {
        cartContainer.innerHTML += `
            <div class="d-flex align-items-start mb-4 position-relative w-100">
                <img src="${item.image}" alt="Product" class="flex-shrink-0" style="width: 70px; height: 70px; object-fit: contain; background: white; border-radius: 8px; padding: 4px; border: 1px solid var(--border-color);">
                
                <div class="ms-3 flex-grow-1" style="min-width: 0;"> 
                    
                    <div class="d-flex justify-content-between align-items-start w-100 mb-1">
                        <div class="pe-2" style="min-width: 0;"> 
                            <p class="cart-item-title" title="${item.name}">${item.name}</p>
                        </div>
                        
                        <button class="btn btn-link p-0 text-secondary text-decoration-none flex-shrink-0" style="font-size: 0.85rem; transition: 0.2s;" onmouseover="this.className='btn btn-link p-0 text-danger text-decoration-none flex-shrink-0'" onmouseout="this.className='btn btn-link p-0 text-secondary text-decoration-none flex-shrink-0'" onclick="cart.removeItem(${index}); renderSideCart();">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    
                    <p class="text-gold fw-bold mb-2" style="font-size: 0.95rem; font-family: 'Oswald', sans-serif;">${cart.formatCurrency(item.price)}</p>
                    
                    <div class="qty-pill mt-1">
                        <button onclick="cart.updateQuantity(${index}, ${item.quantity - 1}); renderSideCart();"><i class="fas fa-minus" style="font-size: 10px;"></i></button>
                        <span class="text-white">${item.quantity}</span>
                        <button onclick="cart.updateQuantity(${index}, ${item.quantity + 1}); renderSideCart();"><i class="fas fa-plus" style="font-size: 10px;"></i></button>
                    </div>
                </div>
            </div>`;
    });

    totalEl.innerText = cart.formatCurrency(subtotal);
}

// The Main Add To Cart Trigger
function addToCart(productData) {
    try {
        cart.addItem(productData);
        renderSideCart(); // Updates the data silently in the background
        
        // Show the sleek minimal toast instead of opening the drawer
        showMinimalCartToast(productData.name);
        return true;
    } catch (error) {
        console.error('Error adding to cart:', error);
        return false;
    }
}

// Function for Addons
function addAddonToCart(addonName, price) {
    const addonData = {
        id: 'ADDON_' + addonName.replace(/\s+/g, ''),
        name: addonName,
        price: price,
        image: 'assets/images/brand-logos/logo5.png', // Use brand logo as placeholder for addons
        quantity: 1,
        options: { Type: 'Service/Extra' }
    };
    cart.addItem(addonData);
    renderSideCart();
}

// Update cart on page load
document.addEventListener('DOMContentLoaded', () => {
    cart.updateCartBadge();
    renderSideCart();
});

// Update badge on page load
document.addEventListener('DOMContentLoaded', () => {
    cart.updateCartBadge();
});

// The Sleek Vanishing Toast UI (Mobile Responsive)
function showMinimalCartToast(productName) {
    // Remove existing toast if user clicks "Add" multiple times quickly
    const existing = document.getElementById('minimalCartToast');
    if (existing) existing.remove();

    // The Toast HTML (Now using the responsive container class)
    const toastHtml = `
        <div id="minimalCartToast" class="minimal-toast-container">
            <div class="toast show align-items-center text-white border-0 w-100" role="alert" 
                 style="background-color: var(--sec-blue); border: 1px solid var(--chp-gold) !important; box-shadow: 0 10px 25px rgba(0,0,0,0.5); border-radius: 8px;">
                <div class="d-flex justify-content-between align-items-center p-2 px-3">
                    
                    <div class="toast-body p-1 text-truncate" style="font-size: 0.9rem; font-weight: 500;">
                        <i class="fas fa-check-circle text-success me-2"></i> 
                        <span class="text-white">${productName}</span> added.
                    </div>
                    
                    <div class="d-flex align-items-center gap-3 ms-3 flex-shrink-0">
                        <button type="button" class="btn btn-link text-gold p-0 text-decoration-none fw-bold" 
                                style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;" 
                                onclick="openOffcanvasFromToast()">
                            View Cart
                        </button>
                    </div>

                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', toastHtml);

    // Auto-vanish after 4 seconds
    setTimeout(() => {
        const toast = document.getElementById('minimalCartToast');
        if (toast) {
            toast.style.transition = 'opacity 0.4s ease';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400); 
        }
    }, 4000);
}

// The "View Cart" Click Handler
function openOffcanvasFromToast() {
    // Remove the toast
    const toast = document.getElementById('minimalCartToast');
    if (toast) toast.remove();
    
    // Slide open the Side Panel Drawer
    const cartCanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
    cartCanvas.show();
}