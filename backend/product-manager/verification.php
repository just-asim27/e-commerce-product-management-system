<?php

session_start();

include '../db-connection.php';

$manager_email = trim($_POST['email'] ?? '');
$manager_password = trim($_POST['password'] ?? '');

$link = "product-manager/login.html";

$sql = "SELECT * FROM product_managers WHERE manager_email = ? AND manager_password = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("ss", $manager_email, $manager_password);
$stmt->execute();
$result = $stmt->get_result();

if ($result -> num_rows > 0) {

    $manager = $result -> fetch_assoc();
    $_SESSION['manager_id'] = $manager['manager_id'];
    header("Location: ../../frontend/product-manager/dashboard.html");
    exit;

} else {

    $message = "Invalid Email or Password!";
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit;
    
}

?>


