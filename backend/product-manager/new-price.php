<?php

include '../db-connection.php';

$product_id = $_POST['product_id'];
$new_price = $_POST['new_price'];

$link = "product-manager/dashboard.html";

$update = "UPDATE products SET product_price = '$new_price' WHERE product_id = '$product_id'";

if ($connect->query($update) === TRUE) {
    $message = "Product price updated successfully!";
    header("Location: ../../frontend/message.php?message=" .$message ."&link=" . $link);
} else {
    $message = "Error updating product price.";
    $link = "product-manager/update-price.php?product_id=" . $product_id;
    header("Location: ../../frontend/message.php?message=" . urlencode($message) ."&link=" .$link);
}

?>