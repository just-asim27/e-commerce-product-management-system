<?php

session_start();

include '../db-connection.php';

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$category = $_POST['category'];
$sub_category = $_POST['sub_category'];
$product_price = $_POST['product_price'];
$product_discount = $_POST['product_discount'];
$product_quantity = $_POST['product_quantity'];
$product_description = $_POST['product_description'];
$manager_id = $_SESSION['manager_id'];

$link = "product-manager/dashboard.html";

$valid_subcategories = [
    "Electronics" => ["Mobile Phones", "Earbuds", "Smartwatches", "Cable & Chargers"],
    "Home & Kitchen" => ["Kitchen Tools", "Table Lamps", "Shelving Units", "Clocks"],
    "Personal Care" => ["Grooming Tools", "Skincare Items", "Compact Mirrors", "Hand Sanitizers"],
    "Office & Study" => ["Notebooks & Planners", "Writing Instruments", "Desk Organizers", "Sticky Notes & Labels"]
];

if (
    !array_key_exists($category, $valid_subcategories) ||
    !in_array($sub_category, $valid_subcategories[$category])
) {
    $message = "Invalid category and subcategory combination.";
    $link = "product-manager/add-product.html";
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit();
}

$target_dir = __DIR__ . "/../../images/";

$image = $_FILES['image'];

$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$file_extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

if (!in_array($file_extension, $allowed_extensions)) {
    $message = "Invalid file type.";
    $link = "product-manager/add-product.html";
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit();
}

$unique_name = uniqid("img_", true) . '.' . $file_extension;

$target_file = $target_dir . $unique_name;
$image_db_path = $unique_name;

if (!move_uploaded_file($image['tmp_name'], $target_file)) {
    $message = "Failed to upload image.";
    $link = "product-manager/add-product.html";
    header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit();
}

$select = "SELECT * FROM products WHERE product_id = '$product_id'";
$result = $connect->query($select);

if ($result->num_rows > 0) {
    $message = "Product with this ID already exists.";
    $link = "product-manager/add-product.html";
} else {
    $sql = "INSERT INTO products (
                product_id, product_name, category, sub_category, product_price,
                product_discount, product_quantity, product_description, image, manager_id
            ) VALUES (
                '$product_id', '$product_name', '$category', '$sub_category',
                '$product_price', '$product_discount', '$product_quantity',
                '$product_description', '$image_db_path', '$manager_id'
            )";
    if ($connect->query($sql) === TRUE) {
        $message = "New product added successfully!";
    } else {
        $message = "Failed to add product!";
    }
}

header("Location: ../../frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
exit();

?>
