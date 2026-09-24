<?php
session_start();
include '../db-connection.php';

// Check customer session
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../../frontend/customer/login.html");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$return_quantities = $_POST['return_quantity'] ?? [];
$return_reasons = $_POST['return_reason'] ?? [];

$link = "customer/view-orders.php"; // Redirect after message

// Validation
if ($order_id === 0 || empty($return_quantities)) {
    $message = "Invalid return request. Please try again.";
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit();
}

$inserted_any = false;

// Get shipping address from orders table
$address_sql = "SELECT shipping_address FROM orders WHERE order_id = ? AND customer_id = ?";
$address_stmt = $connect->prepare($address_sql);
$address_stmt->bind_param("ii", $order_id, $customer_id);
$address_stmt->execute();
$address_result = $address_stmt->get_result();

if ($address_result->num_rows === 0) {
    $message = "Unable to fetch address for the return.";
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit();
}

$address_row = $address_result->fetch_assoc();
$shipping_address = $address_row['shipping_address'];

// Loop through return items
foreach ($return_quantities as $product_id => $quantity) {
    $quantity = intval($quantity);
    if ($quantity <= 0) continue;

    $product_id = intval($product_id);
    $reason = trim($return_reasons[$product_id] ?? '');

    // Get product details
    $product_sql = "SELECT product_price, manager_id FROM products WHERE product_id = ?";
    $stmt = $connect->prepare($product_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();

    if ($product_result->num_rows === 0) continue;

    $product = $product_result->fetch_assoc();
    $product_price = floatval($product['product_price']);
    $manager_id = intval($product['manager_id']);

    // Calculate refund: 90% of price × quantity
    $refund_amount = round($product_price * $quantity * 0.9, 2);
    $return_date = date("Y-m-d H:i:s");

    // Insert into returns table
    $insert_sql = "
        INSERT INTO returns (
            customer_id,
            product_id,
            order_id,
            delivery_boy_id,
            manager_id,
            return_date,
            refund_amount,
            reason,
            quantity,
            address,
            return_status,
            refund_status
        ) VALUES (?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, 'Pending', 'Pending')
    ";

    $insert_stmt = $connect->prepare($insert_sql);
    $insert_stmt->bind_param(
        "iiiidssis",
        $customer_id,
        $product_id,
        $order_id,
        $manager_id,
        $return_date,
        $refund_amount,
        $reason,
        $quantity,
        $shipping_address
    );

    if ($insert_stmt->execute()) {
        $inserted_any = true;
    }
}

// Final message and redirect
if ($inserted_any) {
    $message = "Your return request has been submitted successfully!";
} else {
    $message = "No valid return items found or return request failed.";
}

header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
exit();
?>
