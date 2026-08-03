<?php
include 'C:/xampp/htdocs/Project/website/backend/db-connection.php';
session_start();

$customer_id = $_SESSION['customer_id'] ?? null;
$product_id = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$customer_id || !$product_id || !$action) {
    header("Location: /Project/website/frontend/customer/view-cart.php");
    exit;
}

// Fetch current cart quantity
$stmt = $connect->prepare("SELECT quantity FROM shopping_cart WHERE customer_id = ? AND product_id = ?");
$stmt->bind_param("ii", $customer_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    header("Location: /Project/website/frontend/customer/view-cart.php");
    exit;
}

$current_quantity = $row['quantity'];

// Fetch available product quantity from products table
$product_stmt = $connect->prepare("SELECT product_quantity FROM products WHERE product_id = ?");
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if (!$product_row = $product_result->fetch_assoc()) {
    header("Location: /Project/website/frontend/customer/view-cart.php");
    exit;
}

$available_quantity = $product_row['product_quantity'];

// Apply action logic
if ($action === 'increase') {
    if ($current_quantity < $available_quantity) {
        $current_quantity++;
    }
} elseif ($action === 'decrease' && $current_quantity > 1) {
    $current_quantity--;
}

// Update quantity
$update_stmt = $connect->prepare("UPDATE shopping_cart SET quantity = ? WHERE customer_id = ? AND product_id = ?");
$update_stmt->bind_param("iii", $current_quantity, $customer_id, $product_id);
$update_stmt->execute();

// Redirect back to cart
header("Location: /Project/website/frontend/customer/view-cart.php");
exit;
