<?php
session_start();
include '../db-connection.php';

// Validate customer session
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../../frontend/customer/login.html");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$return_quantities = $_POST['return_quantity'] ?? [];
$return_reasons = $_POST['return_reason'] ?? [];

$link = "../../frontend/customer/view-orders.php"; // Default link back

if ($order_id === 0 || empty($return_quantities)) {
    $message = "Invalid return request. Please try again.";
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit();
}

$inserted_any = false;

foreach ($return_quantities as $product_id => $quantity) {
    $quantity = intval($quantity);
    if ($quantity <= 0) continue;

    $product_id = intval($product_id);
    $reason = trim($return_reasons[$product_id] ?? '');

    // Get product details (price, manager)
    $product_sql = "SELECT product_price, manager_id FROM products WHERE product_id = ?";
    $stmt = $connect->prepare($product_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();

    if ($product_result->num_rows === 0) continue;

    $product = $product_result->fetch_assoc();
    $product_price = floatval($product['product_price']);
    $manager_id = intval($product['manager_id']);

    // Calculate 90% refund
    $refund_amount = round($product_price * $quantity * 0.9, 2);
    $return_date = date("Y-m-d H:i:s");

    // Insert into returns
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
            return_status,
            refund_status
        ) VALUES (?, ?, ?, NULL, ?, ?, ?, ?, ?, 'Pending', 'Not Refunded')
    ";

    $insert_stmt = $connect->prepare($insert_sql);
    $insert_stmt->bind_param(
        "iiiisdssi",
        $customer_id,
        $product_id,
        $order_id,
        $manager_id,
        $return_date,
        $refund_amount,
        $reason,
        $quantity
    );

    if ($insert_stmt->execute()) {
        $inserted_any = true;
    }
}

if ($inserted_any) {
    $message = "Your return request has been submitted successfully!";
} else {
    $message = "No valid return items found or return request failed.";
}

// Redirect to universal message handler
header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
exit();
