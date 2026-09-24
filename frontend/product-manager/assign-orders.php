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
  <title>Assign Orders</title>
  <link rel="stylesheet" href="../../css/product-manager/dashboard.css" />
  <style>
    body {
      margin: 0;
      background-color: #A70E00;
      font-family: Arial, sans-serif;
    }

    header {
      background-color: #FFE179;
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      margin: 0;
      font-size: 24px;
    }

    .logout-button button {
      padding: 10px 20px;
      background-color: #c9302c;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .logout-button button:hover {
      background-color: #d9534f;
    }

    h2 {
      color: white;
      text-align: center;
      margin-top: 30px;
    }

    .orders-container {
      max-width: 1000px;
      margin: 30px auto;
      padding: 20px;
      background-color: white;
      border-radius: 10px;
      color: #333;
    }

    .order-box {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
    }

    .assign-form {
      margin-top: 15px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
    }

    select, button {
      padding: 8px 12px;
      font-size: 14px;
      border-radius: 5px;
      border: 1px solid #999;
      cursor: pointer;
    }

    button {
      background-color: #1f429b;
      color: white;
      border: none;
    }

    button:hover {
      background-color: gray;
      color: black;
    }

    p.no-orders {
      color: black;
      text-align: center;
      font-size: 18px;
    }

    footer {
      background-color: #FFE179;
      text-align: center;
      padding: 10px;
      position: fixed;
      bottom: 0;
      width: 100%;
    }

    footer p {
      margin: 0;
      color: black;
    }
  </style>
</head>
<body>

  <header>
    <h1>E-Commerce Product Management System</h1>
    <div class="logout-button">
      <a href="../../backend/product-manager/logout.php">
        <button>Logout</button>
      </a>
    </div>
  </header>

  <h2>Assign Orders to Delivery Boys</h2>

  <div class="orders-container">

  <?php
    $sqlOrders = "
        SELECT DISTINCT o.order_id, o.customer_id, o.delivery_boy_id, o.order_date, o.shipping_address, o.total_amount, o.payment_status, o.shipping_status,
               c.customer_name
        FROM orders o
        INNER JOIN order_details od ON o.order_id = od.order_id
        INNER JOIN products p ON od.product_id = p.product_id
        INNER JOIN customers c ON o.customer_id = c.customer_id
        WHERE p.manager_id = ? AND o.shipping_status = 'Pending' AND (o.delivery_boy_id IS NULL OR o.delivery_boy_id = 0)
        ORDER BY o.order_date DESC
    ";

    $stmtOrders = $connect->prepare($sqlOrders);
    $stmtOrders->bind_param('i', $manager_id);
    $stmtOrders->execute();
    $resultOrders = $stmtOrders->get_result();

    if ($resultOrders->num_rows > 0) {
        while ($order = $resultOrders->fetch_assoc()) {
            $orderId = $order['order_id'];

            $sqlDetails = "
                SELECT p.product_name, od.quantity
                FROM order_details od
                INNER JOIN products p ON od.product_id = p.product_id
                WHERE od.order_id = ?
            ";

            $stmtDetails = $connect->prepare($sqlDetails);
            $stmtDetails->bind_param('i', $orderId);
            $stmtDetails->execute();
            $resultDetails = $stmtDetails->get_result();

            echo '<div class="order-box">';
            echo '<strong>Order ID:</strong> ' . htmlspecialchars($orderId) . '<br>';
            echo '<strong>Customer:</strong> ' . htmlspecialchars($order['customer_name']) . '<br>';
            echo '<strong>Order Date:</strong> ' . htmlspecialchars($order['order_date']) . '<br>';
            echo '<strong>Shipping Address:</strong> ' . htmlspecialchars($order['shipping_address']) . '<br>';
            echo '<strong>Total Amount:</strong> $' . htmlspecialchars($order['total_amount']) . '<br>';
            echo '<strong>Payment Status:</strong> ' . htmlspecialchars($order['payment_status']) . '<br>';

            echo '<strong>Products:</strong>';
            echo '<table><thead><tr><th>Product Name</th><th>Quantity</th></tr></thead><tbody>';

            while ($product = $resultDetails->fetch_assoc()) {
                echo '<tr><td>' . htmlspecialchars($product['product_name']) . '</td><td>' . intval($product['quantity']) . '</td></tr>';
            }

            echo '</tbody></table>';

            $deliverySql = "SELECT delivery_boy_id, delivery_boy_name FROM delivery_boys ORDER BY delivery_boy_name ASC";
            $deliveryResult = $connect->query($deliverySql);

            echo '<form class="assign-form" method="post" action="../../backend/product-manager/assign-order-handler.php">';
            echo '<input type="hidden" name="order_id" value="' . htmlspecialchars($orderId) . '">';
            echo '<select name="delivery_boy_id" required>';
            echo '<option value="" disabled selected>Assign Delivery Boy</option>';

            if ($deliveryResult->num_rows > 0) {
                while ($delivery = $deliveryResult->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($delivery['delivery_boy_id']) . '">' . htmlspecialchars($delivery['delivery_boy_name']) . '</option>';
                }
            } else {
                echo '<option disabled>No delivery boys available</option>';
            }

            echo '</select>';
            echo '<button type="submit">Assign Order</button>';
            echo '</form>';

            echo '</div>';
        }
    } else {
        echo '<p class="no-orders">No pending orders found containing your products.</p>';
    }

    $stmtOrders->close();
    $connect->close();
  ?>
  
  </div>

  <footer>
    <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
  </footer>

</body>
</html>