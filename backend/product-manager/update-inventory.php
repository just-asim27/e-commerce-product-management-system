<?php

include '../../backend/db-connection.php';

$product_id = $_POST['product_id'];
$link = "product-manager/dashboard.html";

$select = "SELECT * FROM products WHERE product_id = '$product_id'";
$result = $connect->query($select);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_name = $row['product_name'];
    $product_quantity = $row['product_quantity'];
    header("Location: ../../frontend/product-manager/update-quantity.php?product_id=" . $product_id . "&product_name=" .$product_name. "&product_quantity=" . $product_quantity);
} else {
    $message = "Product with this ID Not Available.";
    $link = "product-manager/update-inventory.html";
    header("Location: ../../frontend/message.php?message=" .$message ."&link=" .$link);
}

?>