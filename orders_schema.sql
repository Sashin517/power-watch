-- ==============================================
-- POWER WATCH - ORDER MANAGEMENT SCHEMA
-- ==============================================

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    user_id INT,
    
    -- Customer Information
    customer_email VARCHAR(255) NOT NULL,
    customer_fname VARCHAR(100) NOT NULL,
    customer_lname VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    
    -- Shipping Address
    shipping_address TEXT NOT NULL,
    shipping_apartment VARCHAR(100),
    shipping_city VARCHAR(100) NOT NULL,
    shipping_postal VARCHAR(20),
    
    -- Billing Address (NULL if same as shipping)
    billing_address TEXT,
    billing_apartment VARCHAR(100),
    billing_city VARCHAR(100),
    billing_postal VARCHAR(20),
    
    -- Payment & Pricing
    payment_method ENUM('card', 'koko', 'cod') NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping_cost DECIMAL(10, 2) DEFAULT 0.00,
    discount_amount DECIMAL(10, 2) DEFAULT 0.00,
    total_amount DECIMAL(10, 2) NOT NULL,
    
    -- Order Status
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_order_number (order_number),
    INDEX idx_user_id (user_id),
    INDEX idx_order_status (order_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    
    -- Product Details (snapshot at time of order)
    product_name VARCHAR(255) NOT NULL,
    product_image VARCHAR(500),
    product_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    
    -- Product Options (stored as JSON for flexibility)
    product_options JSON,
    
    -- Calculated
    item_total DECIMAL(10, 2) NOT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE RESTRICT,
    
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Status History Table (optional but recommended for tracking)
CREATE TABLE IF NOT EXISTS order_status_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status_from VARCHAR(50),
    status_to VARCHAR(50) NOT NULL,
    notes TEXT,
    changed_by INT, -- admin user ID
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==============================================
-- CUSTOM ORDER NUMBER GENERATION
-- ==============================================

-- Function to generate custom order number
DELIMITER $$

CREATE FUNCTION generate_order_number()
RETURNS VARCHAR(50)
DETERMINISTIC
BEGIN
    DECLARE next_id INT;
    DECLARE order_num VARCHAR(50);
    
    -- Get the next AUTO_INCREMENT value
    SELECT AUTO_INCREMENT INTO next_id 
    FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'orders';
    
    -- Generate format: #PWORD1, #PWORD2, etc.
    SET order_num = CONCAT('#PWORD', next_id);
    
    RETURN order_num;
END$$

DELIMITER ;

-- ==============================================
-- SAMPLE DATA FOR TESTING
-- ==============================================

-- Insert a sample order
-- INSERT INTO orders (
--     order_number, user_id, customer_email, customer_fname, customer_lname, 
--     customer_phone, shipping_address, shipping_city, 
--     payment_method, subtotal, total_amount
-- ) VALUES (
--     generate_order_number(), 1, 'john@example.com', 'John', 'Doe',
--     '+94771234567', 'No. 123, Main Street', 'Colombo',
--     'card', 12000.00, 12000.00
-- );

-- ==============================================
-- USEFUL QUERIES
-- ==============================================

-- Get all orders with customer details
-- SELECT o.*, u.email, u.fname, u.lname 
-- FROM orders o
-- LEFT JOIN users u ON o.user_id = u.id
-- ORDER BY o.created_at DESC;

-- Get order with all items
-- SELECT o.*, oi.*, p.name as product_name
-- FROM orders o
-- JOIN order_items oi ON o.order_id = oi.order_id
-- JOIN products p ON oi.product_id = p.product_id
-- WHERE o.order_number = '#PWORD1';

-- Get order statistics
-- SELECT 
--     COUNT(*) as total_orders,
--     SUM(total_amount) as total_revenue,
--     AVG(total_amount) as avg_order_value,
--     order_status,
--     COUNT(*) as status_count
-- FROM orders
-- GROUP BY order_status;
