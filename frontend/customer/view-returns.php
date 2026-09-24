<?php

session_start();

include '../../backend/db-connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.html");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "
    SELECT r.return_id, r.return_date, r.quantity, r.refund_amount, r.return_status, r.refund_status, r.picked_up_status,
           p.product_name
    FROM returns r
    JOIN products p ON r.product_id = p.product_id
    JOIN orders o ON r.order_id = o.order_id
    WHERE o.customer_id = ?
    ORDER BY r.return_date DESC
";

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
  <title>My Returns</title>
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
    <h2>My Returns</h2>
    <div class="Pics">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="bbq2">
            <h3>Return #<?php echo $row['return_id']; ?></h3>
            <p><strong>Date:</strong> <?php echo $row['return_date']; ?></p>
            <p><strong>Product:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
            <p><strong>Quantity Returned:</strong> <?php echo intval($row['quantity']); ?></p>
            <p><strong>Refund Amount:</strong> $<?php echo number_format($row['refund_amount'], 2); ?></p>
            <p><strong>Return Status:</strong> <?php echo $row['return_status']; ?></p>
            <p><strong>Refund Status:</strong> <?php echo $row['refund_status']; ?></p>
            <p><strong>Pickup Status:</strong> <?php echo $row['picked_up_status']; ?></p>
            <div class="button">
              <a href="return-details.php?return_id=<?php echo $row['return_id']; ?>"><button>View Details</button></a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>You have no returns yet.</p>
      <?php endif; ?>
    </div>
  </main>
  <footer>
    <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
  </footer>
</body>
</html>
