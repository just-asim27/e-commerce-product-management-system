<?php
include '../../backend/db-connection.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.html");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_GET['product_id'] ?? null;

if (!$product_id) {
    header("Location: ../message.php?message=" . urlencode("Product not specified") . "&link=" . urlencode("customer/products.html"));
    exit;
}

// Check if review already exists for this customer and product
$check_sql = "SELECT * FROM reviews WHERE customer_id = ? AND product_id = ?";
$check_stmt = $connect->prepare($check_sql);
$check_stmt->bind_param("ii", $customer_id, $product_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Review already exists - redirect or show message
    $message = "You have already submitted a review for this product.";
    $link = "customer/view-purchases.php";
    header("Location: ../message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);
    $review_date = date('Y-m-d');

    if ($rating < 0 || $rating > 5) {
        die("Invalid rating. Must be between 0 and 5.");
    }

    $sql = "INSERT INTO reviews (rating, comment, review_date, customer_id, product_id) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("dssii", $rating, $comment, $review_date, $customer_id, $product_id);

    if ($stmt->execute()) {
        $message = "Review submitted successfully!";
        $link = "customer/view-purchases.php";
        header("Location: ../message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
        exit;
    } else {
        die("Error submitting review: " . $connect->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Give Review</title>
    <link rel="stylesheet" href="../../css/product-manager/add-product.css">
</head>
<body>

    <header>
        <h1>E-Commerce Product Management System</h1>
    </header>

    <center>
        <form method="post">
            <fieldset>
                <h2><i>Give Product Review</i></h2>

                <label>Rating (0 to 5):</label><br>
                <input type="number" name="rating" step="0.1" min="0" max="5" required><br><br>

                <label>Comment:</label><br>
                <textarea name="comment" class="description" required></textarea><br><br>

                <button type="submit">Submit Review</button>
            </fieldset>
        </form>
    </center>

    <footer style = "margin-top: 100px;">
        <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
    </footer>

</body>
</html>
