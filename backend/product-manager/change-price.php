<?php

include '../db-connection.php';

$product_id = $_POST['product_id'];
$link = "product-manager/dashboard.html";

$select = "SELECT * FROM products WHERE product_id = '$product_id'";
$result = $connect->query($select);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_name = $row['product_name'];
    $product_price = $row['product_price'];
    header("Location: ../../frontend/product-manager/update-price.php?product_id=" . $product_id . "&product_name=" .$product_name. "&product_price=" . $product_price);
} else {
    $message = "Product with this ID Not Available.";
    $link = "product-manager/change-price.html";
    header("Location: ../../frontend/message.php?message=" .$message ."&link=" .$link);
}

?>