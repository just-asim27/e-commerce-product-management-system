<?php

session_start();

include '../../backend/db-connection.php';

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.html");
    exit;
}

$manager_id = intval($_SESSION['manager_id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Approve Returns</title>
  <link rel="stylesheet" href="../../css/product-manager/dashboard.css" />
  <style>
    body { margin: 0; background-color: #A70E00; font-family: Arial, sans-serif; }
    header { background-color: #FFE179; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
    header h1 { margin: 0; font-size: 24px; }
    .logout-button button { padding: 10px 20px; background: #c9302c; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .logout-button button:hover { background: #d9534f; }
    h2 { color: white; text-align: center; margin-top: 30px; }
    .container { max-width: 1000px; margin: 30px auto; padding: 20px; background: white; border-radius: 10px; color: #333; }
    .return-box { border: 1px solid #ccc; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #f0f0f0; }
    .approve-form { margin-top: 15px; display: flex; gap: 10px; align-items: center; }
    select, button { padding: 8px 12px; font-size: 14px; border-radius: 5px; border: 1px solid #999; cursor: pointer; }
    button { background: #1f429b; color: white; border: none; }
    button:hover { background: gray; color: black; }
    p.no-returns { color: black; text-align: center; font-size: 18px; }
    footer { background: #FFE179; text-align: center; padding: 10px; position: fixed; bottom: 0; width: 100%; }
    footer p { margin: 0; color: black; }
  </style>
</head>
<body>

  <header>
    <h1>E-Commerce Product Management System</h1>
    <div class="logout-button">
      <a href="../../backend/product-manager/logout.php"><button>Logout</button></a>
    </div>
  </header>

  <h2>Approve Return Requests</h2>

  <div class="container">

  <?php
  $sql = "
    SELECT r.return_id, r.order_id, r.product_id, r.quantity, r.reason, r.address, r.return_date, r.refund_amount, c.customer_name, p.product_name
    FROM returns r
    JOIN products p ON r.product_id = p.product_id
    JOIN customers c ON r.customer_id = c.customer_id
    WHERE p.manager_id = ? AND r.return_status = 'Pending'
    ORDER BY r.return_date DESC
  ";

  $stmt = $connect->prepare($sql);
  $stmt->bind_param("i", $manager_id);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      echo '<div class="return-box">';
      echo '<strong>Return ID:</strong> ' . $row['return_id'] . '<br>';
      echo '<strong>Customer:</strong> ' . htmlspecialchars($row['customer_name']) . '<br>';
      echo '<strong>Product:</strong> ' . htmlspecialchars($row['product_name']) . '<br>';
      echo '<strong>Quantity:</strong> ' . intval($row['quantity']) . '<br>';
      echo '<strong>Reason:</strong> ' . htmlspecialchars($row['reason']) . '<br>';
      echo '<strong>Return Date:</strong> ' . htmlspecialchars($row['return_date']) . '<br>';
      echo '<strong>Address:</strong> ' . htmlspecialchars($row['address']) . '<br>';
      echo '<strong>Refund Amount:</strong> $' . htmlspecialchars($row['refund_amount']) . '<br>';

      $delSQL = "SELECT delivery_boy_id, delivery_boy_name FROM delivery_boys ORDER BY delivery_boy_name ASC";
      $delRes = $connect->query($delSQL);

      echo '<form class="approve-form" method="post" action="../../backend/product-manager/approve-returns-handler.php">';
      echo '<input type="hidden" name="return_id" value="' . $row['return_id'] . '">';
      echo '<select name="delivery_boy_id" required>';
      echo '<option value="" disabled selected>Assign Delivery Boy</option>';
      if ($delRes->num_rows > 0) {
        while ($db = $delRes->fetch_assoc()) {
          echo '<option value="' . $db['delivery_boy_id'] . '">' . htmlspecialchars($db['delivery_boy_name']) . '</option>';
        }
      } else {
        echo '<option disabled>No delivery boys available</option>';
      }
      echo '</select>';
      echo '<button type="submit">Approve & Assign</button>';
      echo '</form>';

      echo '</div>';
    }
  } else {
    echo '<p class="no-returns">No pending return requests at the moment.</p>';
  }
  $stmt->close();
  $connect->close();
  ?>

  </div>

  <footer>
    <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
  </footer>

</body>
</html>