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
function showCartToast(message, type = 'success') {
    // Remove existing toast
    const existing = document.getElementById('cartToast');
    if (existing) existing.remove();

    const toastHtml = `
        <div id="cartToast" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999; margin-top: 80px;">
            <div class="toast show align-items-center border-0 ${type === 'success' ? 'bg-success' : 'bg-danger'}" role="alert">
                <div class="d-flex">
                    <div class="toast-body text-white d-flex align-items-center gap-3">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} fa-lg"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1">${message}</div>
                            <div class="d-flex gap-2 mt-2">
                                <button onclick="window.location.href='checkout.php'" class="btn btn-sm btn-light">
                                    <i class="fas fa-shopping-cart me-1"></i> Checkout
                                </button>
                                <button onclick="document.getElementById('cartToast').remove()" class="btn btn-sm btn-outline-light">
                                    Continue Shopping
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="document.getElementById('cartToast').remove()"></button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', toastHtml);

    // Auto-remove after 8 seconds
    setTimeout(() => {
        const toast = document.getElementById('cartToast');
        if (toast) {
            toast.style.transition = 'opacity 0.3s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }
    }, 8000);
}

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
