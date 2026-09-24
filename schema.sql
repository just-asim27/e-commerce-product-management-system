CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(250) DEFAULT NULL,
    customer_email VARCHAR(250) DEFAULT NULL UNIQUE,
    customer_password VARCHAR(250) DEFAULT NULL,
    customer_phone VARCHAR(20) DEFAULT NULL,
    customer_address VARCHAR(250) DEFAULT NULL,
    registration_date DATE DEFAULT NULL
);

CREATE TABLE delivery_boys (
    delivery_boy_id INT AUTO_INCREMENT PRIMARY KEY,
    delivery_boy_name VARCHAR(100) NOT NULL,
    delivery_boy_email VARCHAR(100) NOT NULL UNIQUE,
    delivery_boy_phone VARCHAR(15) NOT NULL UNIQUE,
    delivery_boy_address TEXT DEFAULT NULL,
    delivery_boy_password VARCHAR(255) NOT NULL,
    delivery_boy_salary DECIMAL(10,2) NOT NULL
);

CREATE TABLE product_managers (
    manager_id INT AUTO_INCREMENT PRIMARY KEY,
    manager_name VARCHAR(250) DEFAULT NULL,
    manager_email VARCHAR(250) DEFAULT NULL UNIQUE,
    manager_password VARCHAR(250) DEFAULT NULL,
    manager_phone VARCHAR(250) DEFAULT NULL UNIQUE,
    manager_salary DECIMAL(10,2) NOT NULL
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(250) DEFAULT NULL,
    category VARCHAR(100) DEFAULT NULL,
    sub_category VARCHAR(100) DEFAULT NULL,
    product_price DECIMAL(10,2) DEFAULT NULL,
    product_discount DECIMAL(5,2) DEFAULT NULL,
    product_quantity INT DEFAULT NULL,
    product_description VARCHAR(250) DEFAULT NULL,
    image VARCHAR(225) DEFAULT NULL,
    manager_id INT DEFAULT NULL,
    FOREIGN KEY (manager_id) REFERENCES product_managers(manager_id)
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT DEFAULT NULL,
    order_date DATETIME DEFAULT NULL,
    shipping_address TEXT DEFAULT NULL,
    total_amount DECIMAL(10,2) DEFAULT NULL,
    payment_status ENUM('Pending', 'Paid') DEFAULT 'Pending',
    shipping_status ENUM('Pending', 'Delivered') DEFAULT 'Pending',
    delivery_boy_id INT DEFAULT NULL,
    manager_id INT DEFAULT NULL,
    FOREIGN KEY (delivery_boy_id) REFERENCES delivery_boys(delivery_boy_id),
    FOREIGN KEY (manager_id) REFERENCES product_managers(manager_id),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

CREATE TABLE order_details (
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT NULL,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE returns (
    return_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    order_id INT NOT NULL,
    delivery_boy_id INT DEFAULT NULL,
    manager_id INT NOT NULL,
    return_date DATETIME NOT NULL,
    refund_amount DECIMAL(10,2) NOT NULL,
    reason TEXT DEFAULT NULL,
    quantity INT NOT NULL,
    return_status ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    picked_up_status ENUM('Pending', 'Picked') NOT NULL DEFAULT 'Pending',
    address VARCHAR(255) DEFAULT NULL,
    refund_status ENUM('Pending', 'Refunded') NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (delivery_boy_id) REFERENCES delivery_boys(delivery_boy_id),
    FOREIGN KEY (manager_id) REFERENCES product_managers(manager_id)
);

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT DEFAULT NULL,
    product_id INT DEFAULT NULL,
    rating DECIMAL(3,1) DEFAULT NULL,
    comment TEXT DEFAULT NULL,
    review_date DATETIME DEFAULT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE shopping_cart (
    customer_id INT DEFAULT NULL,
    product_id INT DEFAULT NULL,
    quantity INT DEFAULT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Seed Data

INSERT INTO `product_managers`(`manager_name`, `manager_email`, `manager_password`, `manager_phone`, `manager_salary`) VALUES ('Asim','asim@gmail.com','123','0305-9999999', 70000.00);

INSERT INTO `delivery_boys`
(`delivery_boy_name`, `delivery_boy_email`, `delivery_boy_phone`, `delivery_boy_address`, `delivery_boy_password`, `delivery_boy_salary`)
VALUES
('Ahmed', 'ahmed@gmail.com', '0301-1234567', 'Satellite Town, Gujranwala', '123', 45000.00),
('Bilal', 'bilal@gmail.com', '0302-7654321', 'Model Town, Gujranwala', '123', 45000.00);