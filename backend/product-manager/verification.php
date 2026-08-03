<?php

session_start();

include '../db-connection.php';

$manager_email = $_REQUEST['email'];
$manager_password = $_REQUEST['password'];

$link = "../../frontend/product-manager/login.html";

$sql = "SELECT * FROM product_managers WHERE manager_email='$manager_email' AND manager_password='$manager_password'";
$result = $connect -> query($sql);

if ($result -> num_rows > 0) {

    $manager = $result -> fetch_assoc();
    $_SESSION['manager_id'] = $manager['manager_id'];
    header("Location: ../../frontend/product-manager/dashboard.html");

} else {

    $message = "Invalid Email or Password!";
    header("Location: ../../frontend/message.php?message=" . $message . "&link=" . $link);
    
}

?>



