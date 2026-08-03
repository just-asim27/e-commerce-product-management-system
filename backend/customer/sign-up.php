<?php

include '../db-connection.php';

$customer_name = $_POST['full_name'];
$customer_email = $_POST['email'];
$customer_password = $_POST['password'];
$customer_phone = $_POST['phone_number'];
$customer_address = $_POST['address'];
$registration_date = date("Y-m-d H:i:s");

$select = "SELECT * FROM Customers WHERE customer_email='$customer_email'";
$result = $connect -> query($select);

if ($result -> num_rows > 0) {
    $link = "../../frontend/customer/sign-up.html";
    $message = "Email already exists";
    header("Location: ../../frontend/message.php?message=" .$message ."&link=" .$link);
} else {
    $sql = "INSERT INTO Customers (customer_name, customer_email, customer_password, customer_phone, customer_address, registration_date) 
    VALUES ('$customer_name', '$customer_email', '$customer_password', '$customer_phone', '$customer_address', '$registration_date')";
    $result = $connect -> query($sql);
    if ($result) {
        $link = "customer/login.html";
        $message = "Account created successfully";
        header("Location: ../../frontend/message.php?message=" .$message ."&link=" .$link);
    } else {
        $link = "../../frontend/customer/sign-up.html";
        $message = "Registration failed";
        header("Location: ../../frontend/message.php?message=" .$message ."&link=" .$link);
    }
}

?>
