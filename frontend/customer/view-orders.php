<?php

session_start();

include '../../backend/db-connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: /Project/website/frontend/customer/customer-login.html");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT order_id, order_date, total_amount, shipping_status FROM orders WHERE customer_id = ? ORDER BY order_date DESC";

$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../css/customer/products.css" />
  <title>My Orders</title>
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
    <h2>My Orders</h2>
    <div class="Pics">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="bbq2">
            <h3>Order #<?php echo $row['order_id']; ?></h3>
            <p><strong>Date:</strong> <?php echo $row['order_date']; ?></p>
            <p><strong>Total:</strong> $<?php echo number_format($row['total_amount'], 2); ?></p>
            <p><strong>Status:</strong> <?php echo $row['shipping_status']; ?></p>
            <div class="button">
              <a href="/Project/website/frontend/customer/order-details.php?order_id=<?php echo $row['order_id']; ?>"><button>View Details</button></a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>You have no orders yet.</p>
      <?php endif; ?>
    </div>
  </main>
  <footer>
    <p>&copy; 2025 E-Commerce Website. All rights reserved.</p>
  </footer>
</body>
</html>