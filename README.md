# Power Watch - E-Commerce Flow Implementation

## 🎯 Overview
Complete shopping cart and checkout system with custom order numbers, persistent cart storage, and beautiful UI/UX.

## 📦 Files Created

### 1. **Database Schema**
- `database/orders_schema.sql` - Complete order management tables

### 2. **Frontend Pages**
- `product-page.php` - Product detail with add to cart
- `checkout.php` - Checkout form with cart summary
- `order-success.php` - Order confirmation page

### 3. **JavaScript**
- `js/cart.js` - Shopping cart manager (localStorage-based)

### 4. **Backend**
- `actions/create-order.php` - Order processing script

---

## 🚀 Setup Instructions

### Step 1: Database Setup
```sql
-- Run the schema file
source database/orders_schema.sql;

-- Or copy and execute the SQL directly in phpMyAdmin
```

### Step 2: File Structure
```
power-watch/
├── index.php (home page)
├── product-page.php
├── checkout.php
├── order-success.php
├── js/
│   └── cart.js
├── actions/
│   └── create-order.php
├── assets/
│   └── images/
│       └── brand-logos/
│           └── logo5.png
└── database/
    └── orders_schema.sql
```

### Step 3: Include Cart.js in All Pages
Add this before closing `</body>` tag:
```html
<script src="js/cart.js"></script>
```

### Step 4: Add Cart Badge to Navbar
```html
<a href="checkout.php" class="text-white cart-icon-wrapper">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-badge" id="cartBadge">0</span>
</a>

<style>
.cart-icon-wrapper {
    position: relative;
    display: inline-block;
}

.cart-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: var(--chp-gold);
    color: #000;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
    display: none;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
</style>
```

---

## 🔄 Complete User Flow

### 1. **Browse Products (Home Page)**
- User views products on home page
- Click product → Navigate to `product-page.php?id=X`

### 2. **Product Page**
```javascript
// Add to cart button
<button onclick="handleAddToCart()">Add to Cart</button>

// Product data
const productData = {
    id: 1,
    name: 'Titan Quartz Watch',
    price: 12000,
    image: 'path/to/image.jpg',
    quantity: 1,
    options: { color: 'Blue', strap: 'Steel' }
};

function handleAddToCart() {
    addToCart(productData); // From cart.js
}
```

### 3. **Toast Notification**
When product is added, a toast appears with two options:
- **Checkout** → Goes to checkout.php
- **Continue Shopping** → Closes toast, stays on page

### 4. **Cart Badge Updates**
Badge automatically updates showing total item count across all pages.

### 5. **Checkout Page**
- Displays all cart items from localStorage
- Shows item thumbnails with quantity badges
- Real-time total calculation
- Form validation before submission

### 6. **Order Processing**
```javascript
// Form submission
fetch('actions/create-order.php', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        cart.clearCart(); // Clear localStorage
        window.location.href = `order-success.php?order=${data.order_number}`;
    }
});
```

### 7. **Order Success Page**
- Shows custom order number: `#PWORD1`, `#PWORD2`, etc.
- Confetti animation
- Order details
- Action buttons (Back to Home, View Order)

---

## 🎨 Key Features

### ✅ Persistent Cart
- Uses `localStorage` - cart survives page refresh
- Cart syncs across all pages
- Auto-updates badge count

### ✅ Custom Order Numbers
```php
// Format: #PWORD1, #PWORD2, #PWORD3...
function generateOrderNumber($connection) {
    $result = $connection->query("SELECT order_number FROM orders ORDER BY order_id DESC LIMIT 1");
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        preg_match('/#PWORD(\d+)/', $row['order_number'], $matches);
        $next_number = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
    } else {
        $next_number = 1; // First order
    }
    
    return '#PWORD' . $next_number;
}
```

### ✅ Toast Notifications
- Success/Error states
- Auto-dismiss after 8 seconds
- Interactive buttons (Checkout / Continue Shopping)

### ✅ Responsive Design
- Mobile-first approach
- Touch-friendly buttons
- Optimized layouts for all devices

### ✅ Security
- SQL injection prevention (prepared statements)
- XSS protection (htmlspecialchars)
- CSRF protection (session tokens - add if needed)
- Input validation and sanitization

---

## 💾 Database Schema

### Orders Table
```sql
- order_id (PK)
- order_number (UNIQUE) → #PWORD1, #PWORD2...
- user_id (FK, nullable)
- customer_email
- customer_fname, customer_lname
- customer_phone
- shipping_address, city, postal
- payment_method (card/koko/cod)
- subtotal, shipping_cost, discount_amount, total_amount
- order_status (pending/processing/shipped/delivered/cancelled)
- payment_status (unpaid/paid/refunded)
- created_at, updated_at
```

### Order Items Table
```sql
- item_id (PK)
- order_id (FK)
- product_id (FK)
- product_name (snapshot)
- product_image (snapshot)
- product_price (snapshot)
- quantity
- product_options (JSON)
- item_total
```

### Order Status History Table (Optional)
```sql
- history_id (PK)
- order_id (FK)
- status_from
- status_to
- notes
- changed_by (admin user ID)
- changed_at
```

---

## 🔧 Cart.js API Reference

```javascript
// Initialize cart
const cart = new ShoppingCart();

// Add item
cart.addItem({
    id: 1,
    name: 'Product Name',
    price: 1000,
    image: 'image.jpg',
    quantity: 1,
    options: { color: 'Blue' }
});

// Get all items
const items = cart.getItems();

// Get cart count
const count = cart.getCount();

// Get subtotal
const subtotal = cart.getSubtotal();

// Update quantity
cart.updateQuantity(index, newQuantity);

// Remove item
cart.removeItem(index);

// Clear cart
cart.clearCart();

// Format currency
const formatted = cart.formatCurrency(12000); // "LKR 12,000"
```

---

## 🎨 UI/UX Highlights

### Color Scheme
```css
--prm-blue: #0A111F;      /* Primary background */
--chp-gold: #D4AF37;      /* Gold accent */
--success-green: #2ecc71; /* Success states */
--danger-red: #e74c3c;    /* Error states */
--text-light: #f8f9fa;    /* Light text */
--text-muted: #adb5bd;    /* Muted text */
```

### Typography
- **Headings**: Oswald (uppercase, bold)
- **Body**: Montserrat (clean, modern)

### Animations
- Fade-in on page load
- Slide-up toast notifications
- Confetti on order success
- Smooth hover transitions
- Cart badge pulse effect

### Button States
- Default → Hover → Active → Disabled
- Loading states with spinners
- Clear visual feedback

---

## 🔐 Security Checklist

- [x] Prepared SQL statements
- [x] Input sanitization
- [x] Email validation
- [x] Phone validation
- [x] Cart total verification
- [x] Error logging
- [x] Transaction rollback on failure
- [ ] CSRF tokens (add for production)
- [ ] Rate limiting (add for production)
- [ ] SSL/HTTPS (configure on server)

---

## 📱 Testing Checklist

### Product Page
- [ ] Add to cart button works
- [ ] Toast appears with correct message
- [ ] Cart badge updates
- [ ] Quantity selector works
- [ ] Image gallery functional

### Checkout
- [ ] Cart items display correctly
- [ ] Totals calculate properly
- [ ] Form validation works
- [ ] Required fields enforced
- [ ] Payment method selection

### Order Processing
- [ ] Order created in database
- [ ] Custom order number generated
- [ ] Order items saved correctly
- [ ] Cart cleared after success
- [ ] Error handling works

### Order Success
- [ ] Correct order number displayed
- [ ] Confetti animation plays
- [ ] Links work correctly
- [ ] Mobile responsive

---

## 🚨 Common Issues & Solutions

### Issue: Cart badge not updating
**Solution**: Ensure `cart.js` is loaded before other scripts that use it.

### Issue: Order number not incrementing
**Solution**: Check database AUTO_INCREMENT value and ensure transaction completes.

### Issue: Cart items not showing in checkout
**Solution**: Verify localStorage is enabled in browser and check console for errors.

### Issue: Toast not appearing
**Solution**: Check z-index values and ensure Bootstrap CSS is loaded.

### Issue: Form submission fails
**Solution**: 
1. Check database connection in `create-order.php`
2. Verify all required fields are filled
3. Check browser console for errors
4. Review server error logs

---

## 📈 Future Enhancements

1. **Admin Dashboard Integration**
   - View orders in admin panel
   - Update order status
   - Track inventory

2. **Email Notifications**
   - Order confirmation email
   - Shipping updates
   - Delivery notifications

3. **Payment Gateway Integration**
   - Stripe/PayPal for card payments
   - KOKO payment API
   - Payment verification

4. **User Account Features**
   - Order history
   - Saved addresses
   - Wishlist sync

5. **Advanced Features**
   - Discount codes
   - Gift wrapping
   - Order tracking
   - Reviews and ratings

---

## 📞 Support

For questions or issues:
- Email: dev@powerwatch.lk
- Phone: +94 77 123 4567

---

## 📄 License

© 2026 Power Watch. All rights reserved.
