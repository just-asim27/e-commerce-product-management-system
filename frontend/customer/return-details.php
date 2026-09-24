<?php
session_start();
include '../../backend/db-connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.html");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$return_id = isset($_GET['return_id']) ? intval($_GET['return_id']) : 0;

if ($return_id === 0) {
    echo "<p style='color:white;text-align:center;'>Invalid return ID.</p>";
    exit();
}

// Fetch return
$return_sql = "SELECT * FROM returns WHERE return_id = ? AND customer_id = ?";
$stmt = $connect->prepare($return_sql);
$stmt->bind_param("ii", $return_id, $customer_id);
$stmt->execute();
$return_result = $stmt->get_result();

if ($return_result->num_rows === 0) {
    echo "<p style='color:white;text-align:center;'>Return not found or access denied.</p>";
    exit();
}
$return = $return_result->fetch_assoc();

// Fetch product details
$product_sql = "SELECT product_name, image FROM products WHERE product_id = ?";
$stmt = $connect->prepare($product_sql);
$stmt->bind_param("i", $return['product_id']);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Optionally order info
$order_sql = "SELECT order_date, total_amount FROM orders WHERE order_id = ?";
$stmt = $connect->prepare($order_sql);
$stmt->bind_param("i", $return['order_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../css/customer/order-details.css" />
  <title>Return Details</title>
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
    <h2>Return #<?= htmlspecialchars($return['return_id']) ?></h2>
    <div style="color:white;margin-bottom:30px;">
      <p><strong>Product:</strong> <?= htmlspecialchars($product['product_name']) ?></p>
      <p><strong>Return Date:</strong> <?= htmlspecialchars($return['return_date']) ?></p>
      <p><strong>Quantity Returned:</strong> <?= intval($return['quantity']) ?></p>
      <p><strong>Refund Amount:</strong> Rs. <?= number_format($return['refund_amount'], 2) ?></p>
      <p><strong>Return Status:</strong> <?= htmlspecialchars($return['return_status']) ?></p>
      <p><strong>Refund Status:</strong> <?= htmlspecialchars($return['refund_status']) ?></p>
      <p><strong>Pickup Status:</strong> <?= htmlspecialchars($return['picked_up_status']) ?></p>
      <?php if (!empty($return['reason'])): ?>
        <p><strong>Reason:</strong> <?= nl2br(htmlspecialchars($return['reason'])) ?></p>
      <?php endif; ?>
      <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
      <p><strong>Original Order Total:</strong> Rs. <?= number_format($order['total_amount'], 2) ?></p>
    </div>

    <div class="Pics">
      <div class="bbq2">
        <img src="../../images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
        <h3><?= htmlspecialchars($product['product_name']) ?></h3>
      </div>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
  </footer>
</body>
</html>
