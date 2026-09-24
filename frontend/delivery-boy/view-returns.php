<?php

session_start();

include '../../backend/db-connection.php';

if (!isset($_SESSION['delivery_boy_id'])) {
    header("Location: login.html");
    exit;
}

$delivery_boy_id = intval($_SESSION['delivery_boy_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_picked_up'])) {
    $return_id = intval($_POST['return_id']);
    $connect->begin_transaction();

    try {
        $return_sql = "SELECT product_id, quantity, order_id FROM returns WHERE return_id = ? AND delivery_boy_id = ?";
        $stmt = $connect->prepare($return_sql);
        if (!$stmt) throw new Exception("Prepare failed (return details): " . $connect->error);
        $stmt->bind_param('ii', $return_id, $delivery_boy_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) throw new Exception("Return not found or not assigned to you.");
        $return_data = $result->fetch_assoc();
        $product_id = $return_data['product_id'];
        $returned_qty = intval($return_data['quantity']);
        $order_id = intval($return_data['order_id']);

        $stmt = $connect->prepare("SELECT quantity FROM order_details WHERE order_id = ? AND product_id = ?");
        if (!$stmt) throw new Exception("Prepare failed (order details): " . $connect->error);
        $stmt->bind_param('ii', $order_id, $product_id);
        $stmt->execute();
        $od_result = $stmt->get_result();
        if ($od_result->num_rows === 0) throw new Exception("Order detail not found.");
        $order_detail = $od_result->fetch_assoc();
        $current_qty = intval($order_detail['quantity']);

        $stmt = $connect->prepare("SELECT product_price, product_discount FROM products WHERE product_id = ?");
        if (!$stmt) throw new Exception("Prepare failed (product price): " . $connect->error);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $price_result = $stmt->get_result();
        if ($price_result->num_rows === 0) throw new Exception("Product price not found.");
        $product = $price_result->fetch_assoc();

        $price_per_unit = floatval($product['product_price']);
        $discount = floatval($product['product_discount']);
        $net_price = max(0, $price_per_unit - $discount);
        $reduction_amount = $returned_qty * $net_price;

        $stmt = $connect->prepare("
            UPDATE returns 
            SET picked_up_status = 'Picked', return_status = 'Approved', refund_status = 'Refunded', refund_amount = ?
            WHERE return_id = ?
        ");
        if (!$stmt) throw new Exception("Prepare failed (update returns): " . $connect->error);
        $stmt->bind_param('di', $reduction_amount, $return_id);
        $stmt->execute();

        $stmt = $connect->prepare("
            UPDATE products
            SET product_quantity = product_quantity + ?
            WHERE product_id = ?
        ");
        if (!$stmt) throw new Exception("Prepare failed (update products): " . $connect->error);
        $stmt->bind_param('ii', $returned_qty, $product_id);
        $stmt->execute();

        $new_qty = max(0, $current_qty - $returned_qty);
        $stmt = $connect->prepare("UPDATE order_details SET quantity = ? WHERE order_id = ? AND product_id = ?");
        if (!$stmt) throw new Exception("Prepare failed (update order_details): " . $connect->error);
        $stmt->bind_param('iii', $new_qty, $order_id, $product_id);
        $stmt->execute();

        $stmt = $connect->prepare("UPDATE orders SET total_amount = total_amount - ? WHERE order_id = ?");
        if (!$stmt) throw new Exception("Prepare failed (update orders): " . $connect->error);
        $stmt->bind_param('di', $reduction_amount, $order_id);
        $stmt->execute();

        $connect->commit();
        $message = urlencode("Return picked up successfully. Inventory and refund updated.");
    } catch (Exception $e) {
        $connect->rollback();
        $message = urlencode("Error processing return: " . $e->getMessage());
    }

    $link = urlencode("delivery-boy/view-returns.php");
    header("Location: ../message.php?message={$message}&link={$link}");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Assigned Returns</title>
  <link rel="stylesheet" href="../../css/delivery-boy/view-orders.css" />
</head>
<body>
  <header>
    <h1>E-Commerce Product Management System</h1>
  </header>
  <main>
    <h2>Returns Assigned to You</h2>
    <?php

    $sql = "
      SELECT r.return_id, r.return_date, r.quantity AS return_quantity, r.return_status, r.refund_status, r.picked_up_status,
             p.product_name, c.customer_name, c.customer_email, r.refund_amount
      FROM returns r
      JOIN products p ON r.product_id = p.product_id
      JOIN orders o ON r.order_id = o.order_id
      JOIN customers c ON o.customer_id = c.customer_id
      WHERE r.delivery_boy_id = ?
        AND r.picked_up_status != 'Picked'
      ORDER BY r.return_date DESC
    ";
    $stmt = $connect->prepare($sql);

    if (!$stmt) {
        echo "<p>Error preparing statement: " . htmlspecialchars($connect->error) . "</p>";
        exit;
    }

    $stmt->bind_param('i', $delivery_boy_id);

    if (!$stmt->execute()) {
        echo "<p>Error executing statement: " . htmlspecialchars($stmt->error) . "</p>";
        exit;
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo '<p>No pending returns are currently assigned to you.</p>';
    } else {
        while ($return = $result->fetch_assoc()) {
            $rid = $return['return_id'];
            echo '<div class="order">';
            echo '<h3>Return #'.htmlspecialchars($rid).'</h3>';
            echo '<p><strong>Customer:</strong> '.htmlspecialchars($return['customer_name']).' ('.htmlspecialchars($return['customer_email']).')</p>';
            echo '<p><strong>Return Date:</strong> '.htmlspecialchars($return['return_date']).'</p>';
            echo '<p><strong>Product:</strong> '.htmlspecialchars($return['product_name']).'</p>';
            echo '<p><strong>Quantity Returned:</strong> '.intval($return['return_quantity']).'</p>';
            echo '<p><strong>Return Status:</strong> '.htmlspecialchars($return['return_status']).'</p>';
            echo '<p><strong>Refund Status:</strong> '.htmlspecialchars($return['refund_status']).'</p>';
            echo '<p><strong>Picked Up Status:</strong> '.htmlspecialchars($return['picked_up_status']).'</p>';
            echo '<p><strong>Refund Amount:</strong> ₨'.number_format($return['refund_amount'], 2).'</p>';

            echo '
                <form method="POST" class="delivery-form">
                    <input type="hidden" name="return_id" value="'.htmlspecialchars($rid).'">
                    <button type="submit" name="mark_picked_up">Mark as Picked Up</button>
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
