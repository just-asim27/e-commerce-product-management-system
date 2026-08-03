<?php
include 'C:/xampp/htdocs/Project/website/backend/db-connection.php';

session_start();

$customer_id = $_SESSION['customer_id'];
$product_id = $_POST['product_id'];

// Check if the product is already in the cart
$checkQuery = "SELECT quantity FROM shopping_cart WHERE customer_id = '$customer_id' AND product_id = '$product_id'";
$result = $connect->query($checkQuery);

if ($result && $result->num_rows > 0) {
    // Product exists - update quantity by 1
    $row = $result->fetch_assoc();
    $newQuantity = $row['quantity'] + 1;
    $updateQuery = "UPDATE shopping_cart SET quantity = $newQuantity WHERE customer_id = '$customer_id' AND product_id = '$product_id'";
    if ($connect->query($updateQuery)) {
        $message = "Product quantity updated in cart.";
    } else {
        $message = "Error updating product quantity: " . $connect->error;
    }
} else {
    // Product not in cart - insert new
    $insertQuery = "INSERT INTO shopping_cart (customer_id, product_id, quantity) VALUES ('$customer_id', '$product_id', 1)";
    if ($connect->query($insertQuery)) {
        $message = "Product added to cart successfully.";
    } else {
        $message = "Error adding product to cart: " . $connect->error;
    }
}

$link = "/Project/website/frontend/customer/customer-dashboard.html";
header("Location: /Project/website/frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
exit();
?>
