<?php
session_start();
include 'C:/xampp/htdocs/Project/website/backend/db-connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: /Project/website/frontend/customer/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id === 0) {
    echo "<p style='color:white;text-align:center;'>Invalid order ID.</p>";
    exit();
}

// Verify order belongs to customer & is delivered
$order_sql = "SELECT * FROM orders WHERE order_id = ? AND customer_id = ? AND LOWER(shipping_status) = 'delivered'";
$stmt = $connect->prepare($order_sql);
$stmt->bind_param('ii', $order_id, $customer_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "<p style='color:white;text-align:center;'>Order not found, access denied, or not delivered yet.</p>";
    exit();
}

$order = $order_result->fetch_assoc();

// Fetch products in the order
$items_sql = "
    SELECT p.product_id, p.product_name, p.image, od.quantity
    FROM order_details od
    JOIN products p ON od.product_id = p.product_id
    WHERE od.order_id = ?
";
$stmt2 = $connect->prepare($items_sql);
$stmt2->bind_param('i', $order_id);
$stmt2->execute();
$items_result = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Return Request</title>
  <style>
    /* GLOBAL RESETS & BASE */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html, body {
      width: 100%;
      min-height: 100vh;
      background-color: #A70E00;
      font-family: sans-serif;
      display: flex;
      flex-direction: column;
    }

    /* HEADER */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #FFE179;
      padding: 0 20px;
      height: 100px;
      flex-wrap: wrap;
    }
    header h1 {
      color: #000;
      font-size: 26px;
      flex: 1 0 200px;
    }
    nav {
      flex: 2 1 auto;
    }
    nav ul {
      list-style: none;
      display: flex;
      flex-wrap: wrap;
      justify-content: flex-end;
      gap: 10px;
    }
    nav ul li a {
      text-decoration: none;
      color: #fff;
      font-size: 16px;
      background-color: rgb(31, 66, 155);
      border-radius: 10px;
      padding: 8px 14px;
      transition: background 0.3s, color 0.3s;
      white-space: nowrap;
      display: inline-block;
    }
    nav ul li a:hover {
      background-color: gray;
      color: black;
    }

    /* MAIN */
    main {
      flex: 1;
      padding: 20px;
      max-width: 1200px;
      margin: 20px auto 40px;
      color: #fff;
    }
    main h2 {
      text-align: center;
      font-size: 35px;
      margin-bottom: 30px;
    }

    /* TABLE */
    table {
      width: 100%;
      border-collapse: collapse;
      background: transparent;
    }
    thead tr {
      background-color: rgb(31, 66, 155);
      color: #fff;
    }
    thead th {
      padding: 12px 10px;
      border: 1px solid #fff;
      font-weight: 600;
      text-align: center;
    }
    tbody tr {
      border: 1px solid #fff;
    }
    tbody td {
      border: 1px solid #fff;
      padding: 10px;
      text-align: center;
      vertical-align: middle;
      color: #fff;
    }

    /* Product images in table */
    tbody td img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }

    /* Inputs */
    input[type=number] {
      width: 60px;
      padding: 6px 8px;
      border-radius: 6px;
      border: 1.5px solid #1f429b;
      font-size: 16px;
      text-align: center;
      color: #000;
      outline-offset: 2px;
      outline-color: #1f429b;
    }
    input[type=number]:focus {
      border-color: gray;
      outline-color: gray;
    }

    textarea {
      width: 100%;
      min-height: 50px;
      padding: 6px 8px;
      font-size: 14px;
      border-radius: 6px;
      border: 1.5px solid #1f429b;
      resize: vertical;
      color: #000;
      outline-offset: 2px;
      outline-color: #1f429b;
    }
    textarea:focus {
      border-color: gray;
      outline-color: gray;
    }

    /* Submit button */
    button.btn-submit {
      background-color: rgb(31, 66, 155);
      border-radius: 16px;
      width: 260px;        /* Increased width */
      height: 50px;        /* Increased height */
      border: none;
      color: #fff;
      font-size: 20px;     /* Slightly bigger font */
      cursor: pointer;
      transition: background 0.3s, color 0.3s;
      margin: 30px auto 0; /* Added margin-top to move down */
      display: block;
    }
    button.btn-submit:hover {
      background-color: gray;
      color: black;
    }

    /* FOOTER */
    footer {
      background-color: #FFE179;
      text-align: center;
      padding: 15px;
      width: 100%;
      color: #000;
      font-size: 14px;
    }
  </style>
</head>
<body>

<header>
  <h1>E-Commerce Website</h1>
  <nav>
    <ul>
      <li><a href="/Project/website/frontend/customer/customer-dashboard.html">Home</a></li>
      <li><a href="/Project/website/frontend/customer/view-purchases.php">Purchased Products</a></li>
      <li><a href="/Project/website/frontend/customer/view-returns.php">Returns</a></li>
      <li><a href="/Project/website/frontend/customer/view-orders.php">Orders</a></li>
      <li><a href="/Project/website/frontend/customer/view-cart.php">Cart</a></li>
      <li><a href="/Project/website/backend/customer/logout.php">Logout</a></li>
    </ul>
  </nav>
</header>

<main>
  <h2>Return Items for Order #<?= htmlspecialchars($order_id) ?></h2>

  <form action="/Project/website/backend/customer/return-submit.php" method="POST">
    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">

    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Image</th>
          <th>Ordered Quantity</th>
          <th>Return Quantity</th>
          <th>Reason</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($item = $items_result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($item['product_name']) ?></td>
          <td>
            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" />
          </td>
          <td><?= intval($item['quantity']) ?></td>
          <td>
            <input
              type="number"
              name="return_quantity[<?= intval($item['product_id']) ?>]"
              min="0"
              max="<?= intval($item['quantity']) ?>"
              value="0"
              required
            />
          </td>
          <td>
            <textarea
              name="return_reason[<?= intval($item['product_id']) ?>]"
              rows="2"
              placeholder="Reason for return (optional)"
            ></textarea>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <button type="submit" class="btn-submit">Submit Return Request</button>
  </form>
</main>

<footer>
  <p>&copy; <?= date("Y") ?> E-Commerce Website. All rights reserved.</p>
</footer>

</body>
</html>
