<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Product Details</title>
    <link rel="stylesheet" href="../../css/customer/product-details.css" />
    <style>
        button[disabled] {
            background-color: gray;
            color: white;
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <header>
        <h1>E-Commerce Website</h1>
        <nav>
            <ul>
                <li><a href="dashboard.html">Home</a></li>
                <li><a href="view-purchases.php">Purchased Products</a></li>
                <li><a href="view-returns.php">Returns</a></li>
                <li><a href="view-orders.php">Orders</a></li>
                <li><a href="view-cart.php">Cart</a></li>
                <li><a href="../../backend/customer/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Product Details</h2>
        <?php

        include '../../backend/db-connection.php';

        $product_id = $_GET['id'];
        $select = "SELECT * FROM products WHERE product_id = '$product_id'";
        $result = $connect->query($select);

        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();

        ?>
        <div class="product-card">
            <img src="../../images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">

            <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>

            <p class="price">
                Price: $<?php echo number_format($product['product_price'], 2); ?>
            </p>

            <p class="description">
                <strong>Description:</strong><br>
                <?php echo htmlspecialchars($product['product_description']); ?>
            </p>

            <p class="status" style="color: <?php echo ($product['product_quantity'] > 0) ? '#00FF00' : '#FF4C4C'; ?>;">
                <strong>Status:</strong> <?php echo ($product['product_quantity'] > 0) ? 'In Stock' : 'Out of Stock'; ?>
            </p>

            <p class="discount">
                <strong>Discount:</strong> <?php echo number_format($product['product_discount'], 2); ?>%
            </p>

            <div class="add-cart-button">
                <?php if ($product['product_quantity'] > 0): ?>
                    <form method="post" action="../../backend/customer/add-to-cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                <?php else: ?>
                    <button type="button" disabled>Out of Stock</button>
                <?php endif; ?>
            </div>
        </div>

        <div id="reviews">
            <h2>Customer Reviews</h2>
            <?php

            $review_query = "
                SELECT r.rating, r.comment, r.review_date, c.customer_name 
                FROM reviews r
                JOIN customers c ON r.customer_id = c.customer_id
                WHERE r.product_id = ?
                ORDER BY r.review_date DESC
            ";

            $stmt = $connect->prepare($review_query);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $reviews = $stmt->get_result();

            if ($reviews->num_rows > 0) {
                while ($review = $reviews->fetch_assoc()) {
                    echo "<div class='review-box'>";
                    echo "<p><strong>By:</strong> " . htmlspecialchars($review['customer_name']) . "</p>";
                    echo "<p><strong>Rating:</strong> " . number_format($review['rating'], 1) . " / 5</p>";
                    echo "<p><strong>Comment:</strong><br>" . nl2br(htmlspecialchars($review['comment'])) . "</p>";
                    echo "<p class='review-date'>" . $review['review_date'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p class='no-reviews'>No reviews yet for this product.</p>";
            }

            ?>
        </div>

        <?php

        } else {
            echo "<p class='no-reviews'>Product not found.</p>";
        }

        $connect->close();

        ?>
    </main>
    <footer>
        <p>&copy; 2025 E-Commerce Website. All rights reserved.</p>
    </footer>
</body>
</html>
