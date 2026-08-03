<?php

include '../db-connection.php';

$product_id = $_POST['product_id'];
$new_discount = $_POST['new_discount'];

$link = "product-manager/dashboard.html";

$update = "UPDATE products SET product_discount = '$new_discount' WHERE product_id = '$product_id'";

if ($connect->query($update) === TRUE) {
    $message = "Product discount updated successfully!";
    header("Location: ../../frontend/message.php?message=" .$message ."&link=" . $link);
} else {
    $message = "Error updating product discount.";
    $link = "product-manager/update-discount.php?product_id=" . $product_id;
    header("Location: ../../frontend/message.php?message=" . urlencode($message) ."&link=" .$link);
}

?>