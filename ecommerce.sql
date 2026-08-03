-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2026 at 05:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(250) DEFAULT NULL,
  `customer_email` varchar(250) DEFAULT NULL,
  `customer_password` varchar(250) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` varchar(250) DEFAULT NULL,
  `registration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_email`, `customer_password`, `customer_phone`, `customer_address`, `registration_date`) VALUES
(1, 'Muhammad Asim', 'asim@gmail.com', '123', '03054816428', 'Shahzad Town Near Falcon School', '2025-08-06'),
(2, 'Muhammad Husnain Asif', 'husnain.asif472@gmail.com', 'ranag', '03161405753', 'Allah Baksh Colony Street No 12', '2025-08-07');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boys`
--

CREATE TABLE `delivery_boys` (
  `delivery_boy_id` int(11) NOT NULL,
  `delivery_boy_name` varchar(100) NOT NULL,
  `delivery_boy_email` varchar(100) NOT NULL,
  `delivery_boy_phone` varchar(15) NOT NULL,
  `delivery_boy_address` text DEFAULT NULL,
  `delivery_boy_password` varchar(255) NOT NULL,
  `delivery_boy_salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_boys`
--

INSERT INTO `delivery_boys` (`delivery_boy_id`, `delivery_boy_name`, `delivery_boy_email`, `delivery_boy_phone`, `delivery_boy_address`, `delivery_boy_password`, `delivery_boy_salary`) VALUES
(1, 'John Smith', 'john@example.com', '1234567890', '12 Baker Street, London', 'john123', 18000.00),
(2, 'Mike Johnson', 'mike@example.com', '1234567891', '45 Elm Street, Manchester', 'mike123', 17500.00),
(3, 'David Miller', 'david@example.com', '1234567892', '89 Oak Avenue, Birmingham', 'david123', 17000.00),
(4, 'James Wilson', 'james@example.com', '1234567893', '33 Pine Road, Bristol', 'james123', 16500.00),
(5, 'Robert Brown', 'robert@example.com', '1234567894', '78 Maple Street, Leeds', 'robert123', 16000.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('Pending','Paid') DEFAULT 'Pending',
  `shipping_status` enum('Pending','Delivered') DEFAULT 'Pending',
  `delivery_boy_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_date`, `shipping_address`, `total_amount`, `payment_status`, `shipping_status`, `delivery_boy_id`, `manager_id`) VALUES
(1, 1, '2025-08-06 16:40:21', 'Shahzad Town Near Falcon School', -10.00, 'Paid', 'Delivered', 1, 1),
(2, 1, '2025-08-06 17:23:19', 'Shahzad Town Near Falcon School', 990.00, 'Pending', 'Pending', 4, 1),
(3, 1, '2025-08-06 18:22:18', 'Shahzad Town Near Falcon School', 990.00, 'Paid', 'Delivered', 1, 1),
(4, 1, '2025-08-07 04:34:44', 'Shahzad Town Near Falcon School', 990.99, 'Pending', 'Pending', 5, 1),
(5, 1, '2025-08-07 05:11:41', 'Shahzad Town Near Falcon School', 990.00, 'Paid', 'Delivered', 1, 1),
(6, 1, '2025-08-07 05:48:57', 'Shahzad Town Near Falcon School', 2978.91, 'Pending', 'Pending', 3, 1),
(7, 1, '2025-08-07 06:00:39', 'Shahzad Town Near Falcon School', 0.99, 'Pending', 'Pending', 4, 1),
(8, 1, '2025-08-07 06:16:41', 'Shahzad Town Near Falcon School', -0.99, 'Paid', 'Delivered', 1, 1),
(9, 1, '2025-08-07 06:27:45', 'Shahzad Town Near Falcon School', 0.99, 'Paid', 'Delivered', 1, 1),
(10, 1, '2025-08-07 06:30:56', 'Shahzad Town Near Falcon School', 990.00, 'Pending', 'Pending', 3, 1),
(11, 1, '2025-08-07 06:33:08', 'Shahzad Town Near Falcon School', 990.00, 'Pending', 'Pending', 3, 1),
(12, 1, '2025-08-07 07:04:12', 'Shahzad Town Near Falcon School', 990.00, 'Pending', 'Pending', 3, 1),
(13, 1, '2025-08-07 07:06:16', 'Shahzad Town Near Falcon School', 990.00, 'Pending', 'Pending', 5, 1),
(14, 1, '2025-08-07 07:13:24', 'Shahzad Town Near Falcon School', 0.99, 'Pending', 'Pending', 3, 1),
(15, 1, '2025-08-07 07:24:09', 'Shahzad Town Near Falcon School', 1987.92, 'Paid', 'Delivered', 1, 1),
(16, 1, '2025-08-07 07:34:18', 'Shahzad Town Near Falcon School', -6.93, 'Paid', 'Delivered', 1, 1),
(17, 1, '2025-08-07 08:36:28', 'Shahzad Town Near Falcon School', -10.00, 'Paid', 'Delivered', 3, 1),
(18, 1, '2025-08-07 11:19:55', 'Shahzad Town Near Falcon School', 990.00, 'Paid', 'Delivered', 1, 1),
(19, 1, '2025-08-07 15:07:57', 'Shahzad Town Near Falcon School', 92.83, 'Paid', 'Delivered', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`) VALUES
(1, 1, 0),
(2, 1, 1),
(3, 1, 0),
(4, 1, 1),
(4, 4, 1),
(5, 1, 1),
(6, 1, 3),
(6, 4, 9),
(7, 4, 1),
(8, 10, 0),
(9, 4, 1),
(10, 1, 1),
(11, 1, 1),
(12, 1, 1),
(13, 1, 1),
(14, 4, 1),
(15, 1, 2),
(15, 4, 8),
(16, 10, 0),
(17, 1, 0),
(18, 1, 1),
(19, 10, 1),
(19, 12222, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(250) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `sub_category` varchar(100) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `product_discount` decimal(5,2) DEFAULT NULL,
  `product_quantity` int(11) DEFAULT NULL,
  `product_description` varchar(250) DEFAULT NULL,
  `image` varchar(225) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `sub_category`, `product_price`, `product_discount`, `product_quantity`, `product_description`, `image`, `manager_id`) VALUES
(1, 'I Phone', 'Electronics', 'Mobile Phones', 1000.00, 1.00, 0, 'I phone ', '/Project/website/images/img_689368f246d988.48884626.jpg', 1),
(3, 'qqq', 'Electronics', 'Mobile Phones', 212.00, 22.00, 0, 'weqww', '/Project/website/images/img_68940d5ff19aa0.66806522.jpg', 1),
(4, 'asa', 'Electronics', 'Earbuds', 1.00, 1.00, 306, 'ewewe', '/Project/website/images/img_68940a2fb9bd34.21777508.jpg', 1),
(10, 'I Phone 16 Pro Max', 'Electronics', 'Mobile Phones', 110.00, 0.90, 23, ' m ,mb kjb,m kjdvb., sdjhv sd,m', '/Project/website/images/img_689421afa1e5b8.04906570.jpg', 1),
(1234, 'I Phone 16 Pro Max', 'Electronics', 'Mobile Phones', -1.00, 0.90, 12, 'awassda', '/Project/website/images/img_68949174dba303.62661913.jpg', 1),
(4321, 'dadsdggj', 'Electronics', 'Mobile Phones', 234.00, 23.00, 3, 'qwerty', '/Project/website/images/img_6894aa03916325.78688084.jpg', 1),
(12222, 'I Phone 16 Pro Max', 'Electronics', 'Mobile Phones', 1.00, 909.00, 8, 'mnnasknonds', '/Project/website/images/img_6894929888e083.72167896.jpg', 1),
(123423, 'I Phone 16 Pro Max', 'Electronics', 'Mobile Phones', -1.00, 0.90, -2, 'awassda', '/Project/website/images/img_6894918a8428c5.37805648.jpg', 1),
(909023, 'I Phone 16 Pro Max', 'Electronics', 'Smartwatches', 1.00, 0.00, 8, 'jbknlknkl', '/Project/website/images/img_6894931b46dde6.71449198.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_managers`
--

CREATE TABLE `product_managers` (
  `manager_id` int(11) NOT NULL,
  `manager_name` varchar(250) DEFAULT NULL,
  `manager_email` varchar(250) DEFAULT NULL,
  `manager_password` varchar(250) DEFAULT NULL,
  `manager_phone` varchar(250) DEFAULT NULL,
  `manager_salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_managers`
--

INSERT INTO `product_managers` (`manager_id`, `manager_name`, `manager_email`, `manager_password`, `manager_phone`, `manager_salary`) VALUES
(1, 'Alice Johnson', 'manager1@example.com', '123', '555-123-4567', 85000.00),
(2, 'Bob Smith', 'manager2@example.com', '123', '555-987-6543', 90000.00),
(3, 'Clara Evans', 'manager3@example.com', '123', '555-234-6789', 92000.00),
(4, 'David Lee', 'manager4@example.com', '123', '555-345-7890', 88000.00);

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `return_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_boy_id` int(11) DEFAULT NULL,
  `manager_id` int(11) NOT NULL,
  `return_date` datetime NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `return_status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `picked_up_status` enum('Pending','Picked') NOT NULL DEFAULT 'Pending',
  `address` varchar(255) DEFAULT NULL,
  `refund_status` enum('Pending','Refunded') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`return_id`, `customer_id`, `product_id`, `order_id`, `delivery_boy_id`, `manager_id`, `return_date`, `refund_amount`, `reason`, `quantity`, `return_status`, `picked_up_status`, `address`, `refund_status`) VALUES
(16, 1, 10, 16, 1, 1, '2025-08-07 12:15:14', 220.00, '', 2, 'Approved', 'Picked', 'Shahzad Town Near Falcon School', 'Refunded'),
(17, 1, 1, 17, 3, 1, '2025-08-07 11:40:42', 1000.00, '', 1, 'Approved', 'Picked', 'Shahzad Town Near Falcon School', 'Refunded'),
(18, 1, 1, 18, 4, 1, '2025-08-07 14:22:59', 900.00, '', 1, 'Approved', 'Pending', 'Shahzad Town Near Falcon School', 'Pending'),
(19, 1, 12222, 19, 1, 1, '2025-08-07 18:12:57', 0.00, '', 2, 'Approved', 'Picked', 'Shahzad Town Near Falcon School', 'Refunded');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `review_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `customer_id`, `product_id`, `rating`, `comment`, `review_date`) VALUES
(0, 1, 1, 5.0, 'qqq', '2025-08-07 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_email` (`customer_email`);

--
-- Indexes for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD PRIMARY KEY (`delivery_boy_id`),
  ADD UNIQUE KEY `delivery_boy_email` (`delivery_boy_email`),
  ADD UNIQUE KEY `delivery_boy_phone` (`delivery_boy_phone`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `fk_delivery_boys` (`delivery_boy_id`),
  ADD KEY `fk_product_managers` (`manager_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `product_managers`
--
ALTER TABLE `product_managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `manager_email` (`manager_email`),
  ADD UNIQUE KEY `manager_phone` (`manager_phone`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `delivery_boy_id` (`delivery_boy_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  MODIFY `delivery_boy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_managers`
--
ALTER TABLE `product_managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_delivery_boys` FOREIGN KEY (`delivery_boy_id`) REFERENCES `delivery_boys` (`delivery_boy_id`),
  ADD CONSTRAINT `fk_product_managers` FOREIGN KEY (`manager_id`) REFERENCES `product_managers` (`manager_id`),
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `product_managers` (`manager_id`);

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `returns_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `returns_ibfk_4` FOREIGN KEY (`delivery_boy_id`) REFERENCES `delivery_boys` (`delivery_boy_id`),
  ADD CONSTRAINT `returns_ibfk_5` FOREIGN KEY (`manager_id`) REFERENCES `product_managers` (`manager_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `shopping_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
