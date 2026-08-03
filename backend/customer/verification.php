<?php

session_start();

include '../db-connection.php';

$customer_email = $_REQUEST['email'];
$customer_password = $_REQUEST['password'];

$sql = "SELECT * FROM Customers WHERE customer_email='$customer_email' AND customer_password='$customer_password'";
$result = $connect -> query($sql);

if ($result -> num_rows > 0) {
    $customer = $result -> fetch_assoc();
    $_SESSION['customer_id'] = $customer['customer_id'];
    header("Location: ../../frontend/customer/products.html");
} else {
    header("Location: ../../frontend/customer/sign-up.html");
}

?>



