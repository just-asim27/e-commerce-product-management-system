<?php

include '../db-connection.php';

$product_id = $_POST['product_id'];
$quantity_to_add = $_POST['new_quantity'];

$link = "product-manager/dashboard.html";

$select = "SELECT product_quantity FROM products WHERE product_id = '$product_id'";
$result = $connect->query($select);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_quantity = $row['product_quantity'];
    $new_quantity = $current_quantity + $quantity_to_add;
    $update = "UPDATE products SET product_quantity = '$new_quantity' WHERE product_id = '$product_id'";
    if ($connect->query($update) === TRUE) {
        $message = "Product quantity updated successfully!";
        header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    } else {
        $message = "Error updating product quantity.";
        $link = "product-manager/update-quantity.php?product_id=" . $product_id;
        header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    }
} else {
    $message = "Product not found.";
    $link = "product-manager/update-quantity.php?product_id=" . $product_id;
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
}

?>
