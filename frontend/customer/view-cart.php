<?php

session_start();

include '../../backend/db-connection.php';

$customer_id = $_SESSION['customer_id'];

$query = "SELECT sc.product_id, sc.quantity, p.product_name, p.image
          FROM shopping_cart sc 
          JOIN products p ON sc.product_id = p.product_id 
          WHERE sc.customer_id = '$customer_id'";
$result = $connect->query($query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Shopping Cart</title>
    <style>
        <?php include '../../css/customer/view-cart.css'; ?>

        .quantity-controls {
            text-align: center;
            margin: 10px 0;
        }

        .quantity-controls button {
            width: 30px;
            height: 30px;
            font-size: 20px;
            font-weight: bold;
            margin: 0 5px;
            border-radius: 50%;
            border: none;
            background-color: rgb(31, 66, 155);
            color: white;
        }

        .quantity-controls span {
            font-size: 20px;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>E-Commerce Website</h1>
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
        <h2>Items in Your Cart</h2>
        <div class="Pics">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bbq2">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product">
                        <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>

                        <form class="quantity-controls" method="POST" action="/Project/website/backend/customer/update-quantity.php">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        
                            <button type="submit" name="action" value="decrease">−</button>
                            <span><?php echo $row['quantity']; ?></span>
                            <button type="submit" name="action" value="increase">+</button>
                        </form>

                        <form action="/Project/website/backend/customer/remove-from-cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:white; font-size:20px;">Your cart is empty.</p>
            <?php endif; ?>
        </div>
        <form method="POST" action="/Project/website/backend/customer/checkout.php">
            <button type="submit" class="checkout-button">
                Checkout
            </button>
        </form>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> E-Commerce Website. All rights reserved.</p>
    </footer>
</body>
</html>
