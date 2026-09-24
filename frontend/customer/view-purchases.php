<?php

session_start();

include '../../backend/db-connection.php';

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT p.product_id, p.product_name, p.image, p.product_price
        FROM orders o
        JOIN order_details od ON o.order_id = od.order_id
        JOIN products p ON od.product_id = p.product_id
        WHERE o.customer_id = ?
        GROUP BY p.product_id";

$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$reviews_sql = "SELECT product_id FROM reviews WHERE customer_id = ?";
$reviews_stmt = $connect->prepare($reviews_sql);
$reviews_stmt->bind_param("i", $customer_id);
$reviews_stmt->execute();
$reviews_result = $reviews_stmt->get_result();

$customer_reviews = [];

while ($review_row = $reviews_result->fetch_assoc()) {
    $customer_reviews[$review_row['product_id']] = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchased Products</title>
    <link rel="stylesheet" href="../../css/customer/view-purchases.css">
</head>
<body>
<div class="wrapper">
    <header>
        <h1>E-Commerce Product Management System</h1>
        <nav>
            <ul>
                <li><a href="products.html">Home</a></li>
                <li><a href="view-purchases.php">Purchased Products</a></li>
                <li><a href="view-returns.php">Returns</a></li>
                <li><a href="view-orders.php">Orders</a></li>
                <li><a href="view-cart.php">Cart</a></li>
                <li><a href="../../backend/customer/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Your Purchased Products</h2>
        <div class="Pics">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='bbq2'>";
                echo "<img src='../../images/" . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8') . "'>";
                echo "<h3>" . $row['product_name'] . "</h3>";
                echo "<p class='product-price'>Price: $" . $row['product_price'] . "</p>";
                echo "<div class='button'>";

                if (isset($customer_reviews[$row['product_id']])) {
                    echo "<a href='update-review.php?product_id=" . $row['product_id'] . "'><button>Update Review</button></a>";
                } else {
                    echo "<a href='give-review.php?product_id=" . $row['product_id'] . "'><button>Give Review</button></a>";
                }

                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-products'>You have not purchased any products yet.</p>";
        }
        ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
    </footer>
</div>
</body>
</html>

<?php $connect->close(); ?>
