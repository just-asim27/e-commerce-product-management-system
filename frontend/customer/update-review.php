<?php
include 'C:/xampp/htdocs/Project/website/backend/db-connection.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: /Project/website/frontend/customer/login.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_GET['product_id'] ?? null;

if (!$product_id) {
    header("Location: /Project/website/frontend/message.php?message=Product not specified&link=/Project/website/frontend/customer/index.php");
    exit;
}

// Fetch existing review
$review_sql = "SELECT rating, comment FROM reviews WHERE customer_id = ? AND product_id = ?";
$review_stmt = $connect->prepare($review_sql);
$review_stmt->bind_param("ii", $customer_id, $product_id);
$review_stmt->execute();
$review_result = $review_stmt->get_result();

if ($review_result->num_rows === 0) {
    $message = "No review found to update for this product.";
    $link = "/Project/website/frontend/customer/view-purchases.php";
    header("Location: /Project/website/frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
    exit;
}

$existing_review = $review_result->fetch_assoc();

// Handle update on POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);

    if ($rating < 0 || $rating > 5) {
        die("Invalid rating. Must be between 0 and 5.");
    }

    $update_sql = "UPDATE reviews SET rating = ?, comment = ?, review_date = ? 
                   WHERE customer_id = ? AND product_id = ?";
    $stmt = $connect->prepare($update_sql);
    $review_date = date('Y-m-d');
    $stmt->bind_param("dssii", $rating, $comment, $review_date, $customer_id, $product_id);

    if ($stmt->execute()) {
        $message = "Review updated successfully!";
        $link = "/Project/website/frontend/customer/view-purchases.php";
        header("Location: /Project/website/frontend/message.php?message=" . urlencode($message) . "&link=" . urlencode($link));
        exit;
    } else {
        die("Error updating review: " . $connect->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Review</title>
    <link rel="stylesheet" href="/Project/website/css/product-manager/add-product.css">
</head>
<body>

    <header>
        <h1>E-Commerce Website</h1>
    </header>

    <center>
        <form method="post">
            <fieldset>
                <h2><i>Update Product Review</i></h2>

                <label>Rating (0 to 5):</label><br>
                <input type="number" name="rating" step="0.1" min="0" max="5" required 
                       value="<?= htmlspecialchars($existing_review['rating']) ?>"><br><br>

                <label>Comment:</label><br>
                <textarea name="comment" class="description" required><?= htmlspecialchars($existing_review['comment']) ?></textarea><br><br>

                <div class="button">
                    <button type="submit">Update Review</button>
                </div>
            </fieldset>
        </form>
    </center>

    <footer style = "margin-top: 72px;">
        <p>&copy; 2025 E-Commerce Website. All rights reserved.</p>
    </footer>

</body>
</html>
