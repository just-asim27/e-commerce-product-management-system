<?php
session_start();
include '../../backend/db-connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.html");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id === 0) {
    echo "<p style='color:white;text-align:center;'>Invalid order ID.</p>";
    exit();
}

$order_sql = "SELECT * FROM orders WHERE order_id = $order_id AND customer_id = $customer_id";
$order_result = $connect->query($order_sql);

if ($order_result->num_rows === 0) {
    echo "<p style='color:white;text-align:center;'>Order not found or access denied.</p>";
    exit();
}

$order = $order_result->fetch_assoc();
$customer_sql = "SELECT * FROM customers WHERE customer_id = $customer_id";
$customer_result = $connect->query($customer_sql);
$customer = $customer_result->fetch_assoc();

$items_sql = "
    SELECT p.product_name, p.image, p.product_price, p.product_discount, od.quantity
    FROM order_details od
    JOIN products p ON od.product_id = p.product_id
    WHERE od.order_id = $order_id
";
$items_result = $connect->query($items_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="../../css/customer/order-details.css">
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
    <h2>Order #<?= $order['order_id'] ?></h2>

    <div style="color:white;margin-bottom:30px;">
        <p><strong>Customer:</strong> <?= htmlspecialchars($customer['customer_name']) ?></p>
        <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>
        <p><strong>Shipping Address:</strong> <?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
        <p><strong>Payment Status:</strong> <?= ucfirst($order['payment_status']) ?></p>
        <p><strong>Shipping Status:</strong> <?= ucfirst($order['shipping_status']) ?></p>
        <p><strong>Total Amount:</strong> Rs. <?= number_format($order['total_amount'], 2) ?></p>
    </div>

    <div class="Pics">
        <?php while ($item = $items_result->fetch_assoc()):
            $price = $item['product_price'];
            $discount = $item['product_discount'];
            $final_price = $price - ($price * $discount / 100);
            $subtotal = $final_price * $item['quantity'];
        ?>
            <div class="bbq2">
                <img src="../../images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                <p>Price: Rs. <?= number_format($final_price, 2) ?></p>
                <p>Quantity: <?= $item['quantity'] ?></p>
                <p>Subtotal: Rs. <?= number_format($subtotal, 2) ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- RETURN BUTTON -->
    <?php if (trim(strtolower($order['shipping_status'])) === 'delivered'): ?>
        <div style="text-align: center; margin-top: 30px;">
            <a class="btn-link" href="return-request.php?order_id=<?= $order['order_id'] ?>">Return Items</a>
        </div>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
</footer>

</body>
</html>
