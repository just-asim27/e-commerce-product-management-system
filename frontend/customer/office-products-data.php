<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Products</title>
    <link rel="stylesheet" href="../../css/customer/products-data.css">
</head>
<body>
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
        <h2>Browse Products</h2>

        <?php
    
        include '../../backend/db-connection.php';

        $category = $_GET['category'];
        $sub_category = $_GET['sub_category'];

        $sql = "SELECT * FROM products WHERE category = '$category' AND sub_category = '$sub_category'";
        $result = $connect->query($sql);

        echo "<div class='Pics'>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='bbq2'>";
                echo "<img src='../../images/" . $row['image'] . "' alt='" . $row['product_name'] . "'>";
                echo "<h3>" . $row['product_name'] . "</h3>";
                echo "<p style='color: #fff; text-align: center;'>Price: $" . $row['product_price'] . "</p>";
                echo "<div class='button'>";
                echo "<a href='product-details.php?id=" . $row['product_id'] . "'><button>View Details</button></a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            $message = "No products found in this category.";
            $link = "customer/office-products.html";
            header("Location: ../message.php?message=" . $message . "&link=" . $link);
        }

        echo "</div>"; 

        $connect->close();

        ?>
    </main>
    <footer>
        <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
    </footer>
</body>
</html>
