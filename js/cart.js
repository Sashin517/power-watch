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

/**
 * Show toast notification
 */
// Function to re-render the side cart UI
function renderSideCart() {
    const cartContainer = document.getElementById('sideCartItems');
    const totalEl = document.getElementById('sideCartTotal');
    if(!cartContainer) return;

    const items = cart.getItems();
    cartContainer.innerHTML = '';

    if (items.length === 0) {
        cartContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart text-muted mb-3" style="font-size: 3rem;"></i>
                <p class="text-muted">Your cart is empty.</p>
            </div>`;
        totalEl.innerText = 'LKR 0.00';
        return;
    }

    items.forEach((item, index) => {
        cartContainer.innerHTML += `
            <div class="d-flex align-items-center mb-3 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.05) !important;">
                <img src="${item.image}" style="width: 60px; height: 60px; object-fit: contain; background: white; border-radius: 6px; padding: 2px;">
                <div class="ms-3 flex-grow-1">
                    <p class="text-white mb-1" style="font-size: 0.85rem; line-height: 1.2;">${item.name}</p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="text-gold fw-bold" style="font-size: 0.9rem;">${cart.formatCurrency(item.price)}</span>
                        <div class="d-flex align-items-center" style="background: var(--dark-grey); border-radius: 4px;">
                            <button class="btn btn-sm text-white px-2 py-0" onclick="cart.updateQuantity(${index}, ${item.quantity - 1}); renderSideCart();">-</button>
                            <span class="text-white px-2" style="font-size: 0.8rem;">${item.quantity}</span>
                            <button class="btn btn-sm text-white px-2 py-0" onclick="cart.updateQuantity(${index}, ${item.quantity + 1}); renderSideCart();">+</button>
                        </div>
                    </div>
                </div>
            </div>`;
    });

    totalEl.innerText = cart.formatCurrency(cart.getSubtotal());
}

// Override addToCart to update Side Panel instead of Toast
function addToCart(productData) {
    cart.addItem(productData);
    renderSideCart();
    
    // Show Offcanvas if it's not already open
    const cartCanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
    cartCanvas.show();
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

/**
 * Add to cart button handler
 */
function addToCart(productData) {
    try {
        const count = cart.addItem(productData);
        showCartToast(`${productData.name} added to cart!`, 'success');
        return true;
    } catch (error) {
        console.error('Error adding to cart:', error);
        showCartToast('Failed to add item to cart', 'error');
        return false;
    }
}

// Update badge on page load
document.addEventListener('DOMContentLoaded', () => {
    cart.updateCartBadge();
});
