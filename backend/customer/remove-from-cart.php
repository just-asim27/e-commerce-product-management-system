<?php
include '../db-connection.php';
session_start();

$customer_id = $_SESSION['customer_id'] ?? null;
$product_id = $_POST['product_id'] ?? null;

if ($customer_id && $product_id) {
    $stmt = $connect->prepare("DELETE FROM shopping_cart WHERE customer_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
}

header("Location: ../../frontend/customer/view-cart.php");
exit;
