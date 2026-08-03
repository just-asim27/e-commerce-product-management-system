<?php

session_start();

include '../db-connection.php';

$delivery_boy_email = trim($_POST['email'] ?? '');
$delivery_boy_password = trim($_POST['password'] ?? '');

$link = "delivery-boy/login.html";

if (empty($delivery_boy_email) || empty($delivery_boy_password)) {
    $message = "Please enter both email and password.";
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit;
}

$sql = "SELECT * FROM delivery_boys WHERE delivery_boy_email = ? AND delivery_boy_password = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("ss", $delivery_boy_email, $delivery_boy_password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $delivery_boy = $result->fetch_assoc();
    $_SESSION['delivery_boy_id'] = $delivery_boy['delivery_boy_id'];
    header("Location: ../../frontend/delivery-boy/dashboard.html");
    exit;
} else {
    $message = "Invalid Email or Password!";
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit;
}

?>
