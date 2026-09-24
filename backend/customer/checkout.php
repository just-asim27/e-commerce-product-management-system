<?php
include '../db-connection.php';
session_start();

$customer_id = $_SESSION['customer_id'];

// 0. Get the customer's address from the customer table
$address_sql = "SELECT customer_address FROM customers WHERE customer_id = ?";
$stmt_address = $connect->prepare($address_sql);
$stmt_address->bind_param("i", $customer_id);
$stmt_address->execute();
$result_address = $stmt_address->get_result();

if ($result_address->num_rows === 0) {
    die("Customer address not found.");
}

$row_address = $result_address->fetch_assoc();
$shipping_address = $row_address['customer_address'];

// 1. Get cart items for this customer
$query = "SELECT sc.product_id, sc.quantity, p.product_price, p.product_discount
          FROM shopping_cart sc
          JOIN products p ON sc.product_id = p.product_id
          WHERE sc.customer_id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../../frontend/customer/view-cart.php?message=Cart is empty");
    exit;
}

// 2. Calculate total amount
$total_amount = 0;
$items = [];

while ($row = $result->fetch_assoc()) {
    $price = $row['product_price'];
    $discount = $row['product_discount'];
    
    $discounted_price = $price * (1 - $discount / 100);
    $total_amount += $discounted_price * $row['quantity'];
    
    $items[] = $row;
}

// 3. Insert new order
$order_date = date('Y-m-d H:i:s');
$payment_status = 'pending';
$shipping_status = 'pending';

$insert_order_sql = "INSERT INTO orders (customer_id, order_date, shipping_address, total_amount, payment_status, shipping_status)
                     VALUES (?, ?, ?, ?, ?, ?)";
$stmt_order = $connect->prepare($insert_order_sql);
$stmt_order->bind_param("issdss", $customer_id, $order_date, $shipping_address, $total_amount, $payment_status, $shipping_status);

if (!$stmt_order->execute()) {
    die("Failed to create order: " . $connect->error);
}

$order_id = $stmt_order->insert_id;

// 4. Insert into order_details for each item
$insert_details_sql = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)";
$stmt_details = $connect->prepare($insert_details_sql);

foreach ($items as $item) {
    $stmt_details->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
    if (!$stmt_details->execute()) {
        die("Failed to add order details: " . $connect->error);
    }
}

// 4.5 Subtract purchased quantity from products table
$update_product_sql = "UPDATE products SET product_quantity = product_quantity - ? WHERE product_id = ?";
$stmt_update_product = $connect->prepare($update_product_sql);

foreach ($items as $item) {
    $stmt_update_product->bind_param("ii", $item['quantity'], $item['product_id']);
    if (!$stmt_update_product->execute()) {
        die("Failed to update product stock: " . $connect->error);
    }
}

// 5. Clear shopping cart for customer
$clear_cart_sql = "DELETE FROM shopping_cart WHERE customer_id = ?";
$stmt_clear = $connect->prepare($clear_cart_sql);
$stmt_clear->bind_param("i", $customer_id);
$stmt_clear->execute();

// 6. Redirect to success message
$message = "Order placed successfully. Your Order ID is #" . $order_id;
$link = "customer/products.html";

header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
exit;
?>
