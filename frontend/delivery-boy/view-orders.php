<?php

session_start();

include '../../backend/db-connection.php';

if (!isset($_SESSION['delivery_boy_id'])) {
    header("Location: login.html");
    exit;
}

$delivery_boy_id = intval($_SESSION['delivery_boy_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_delivered'])) {
    $order_id = intval($_POST['order_id']);
    $update_sql = "
        UPDATE orders 
        SET shipping_status = 'Delivered', payment_status = 'Paid' 
        WHERE order_id = ? AND delivery_boy_id = ?
    ";
    $update_stmt = $connect->prepare($update_sql);
    $update_stmt->bind_param('ii', $order_id, $delivery_boy_id);
    $update_stmt->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Assigned Orders</title>
  <link rel="stylesheet" href="../../css/delivery-boy/view-orders.css" />
</head>
<body>
  <header>
    <h1>E-Commerce Product Management System</h1>
  </header>
  <main>
    <h2>Orders Assigned to You</h2>
    <?php

    $sql = "
      SELECT o.order_id, o.order_date, o.shipping_address, o.total_amount, o.payment_status, o.shipping_status,
             c.customer_name, c.customer_email
      FROM orders o
      JOIN customers c ON o.customer_id = c.customer_id
      WHERE o.delivery_boy_id = ? 
        AND o.shipping_status != 'Delivered' 
        AND o.payment_status != 'Paid'
      ORDER BY o.order_date DESC
    ";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param('i', $delivery_boy_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo '<p>No pending orders are currently assigned to you.</p>';
    } else {
        while ($order = $result->fetch_assoc()) {
            $oid = $order['order_id'];
            echo '<div class="order">';
            echo '<h3>Order #'.htmlspecialchars($oid).'</h3>';
            echo '<p><strong>Customer:</strong> '.htmlspecialchars($order['customer_name']).' ('.htmlspecialchars($order['customer_email']).')</p>';
            echo '<p><strong>Order Date:</strong> '.htmlspecialchars($order['order_date']).'</p>';
            echo '<p><strong>Address:</strong> '.nl2br(htmlspecialchars($order['shipping_address'])).'</p>';
            echo '<p><strong>Total:</strong> ₨'.number_format($order['total_amount'],2).'</p>';
            echo '<p><strong>Payment Status:</strong> '.htmlspecialchars($order['payment_status']).'</p>';
            echo '<p><strong>Shipping Status:</strong> '.htmlspecialchars($order['shipping_status']).'</p>';

            $sql2 = "
              SELECT p.product_name, od.quantity
              FROM order_details od
              JOIN products p ON od.product_id = p.product_id
              WHERE od.order_id = ?
            ";

            $stmt2 = $connect->prepare($sql2);
            $stmt2->bind_param('i', $oid);
            $stmt2->execute();
            $det = $stmt2->get_result();

            echo '<table><thead><tr><th>Product</th><th>Quantity</th></tr></thead><tbody>';
            while ($row = $det->fetch_assoc()) {
                echo '<tr><td>'.htmlspecialchars($row['product_name']).'</td><td>'.intval($row['quantity']).'</td></tr>';
            }
            echo '</tbody></table>';
            echo '
                <form method="POST" class="delivery-form">
                    <input type="hidden" name="order_id" value="'.htmlspecialchars($oid).'">
                    <button type="submit" name="mark_delivered">Mark as Delivered</button>
                </form>
            ';
            echo '</div>';
        }
    }

    $connect->close();

    ?>
  </main>
  <footer>
    <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
  </footer>
</body>
</html>
