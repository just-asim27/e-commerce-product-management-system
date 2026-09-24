<?php
session_start();
include '../db-connection.php';

if (!isset($_SESSION['manager_id'])) {
    header("Location: ../../frontend/product-manager/login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager_id = intval($_SESSION['manager_id']);
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $delivery_boy_id = isset($_POST['delivery_boy_id']) ? intval($_POST['delivery_boy_id']) : 0;

    // Validate inputs
    if ($order_id > 0 && $delivery_boy_id > 0) {
        // Check if the order contains a product added by this manager
        $checkSql = "
            SELECT COUNT(*) AS count
            FROM orders o
            INNER JOIN order_details od ON o.order_id = od.order_id
            INNER JOIN products p ON od.product_id = p.product_id
            WHERE o.order_id = ? AND p.manager_id = ?
        ";
        $stmtCheck = $connect->prepare($checkSql);
        $stmtCheck->bind_param("ii", $order_id, $manager_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $row = $resultCheck->fetch_assoc();

        if ($row && $row['count'] > 0) {
            // Perform the update
            $updateSql = "UPDATE orders SET delivery_boy_id = ?, manager_id = ? WHERE order_id = ?";
            $stmtUpdate = $connect->prepare($updateSql);
            $stmtUpdate->bind_param("iii", $delivery_boy_id, $manager_id, $order_id);

            if ($stmtUpdate->execute()) {
                $message = urlencode("Order successfully assigned to delivery boy.");
            } else {
                $message = urlencode("Failed to assign order. Please try again.");
            }
        } else {
            $message = urlencode("Unauthorized: You can only assign orders containing your products.");
        }
    } else {
        $message = urlencode("Invalid input. Please select a valid delivery boy.");
    }

    // Redirect link to assign order page in frontend
    
    $link = urlencode("product-manager/dashboard.html");
    header("Location: ../../frontend/message.php?message={$message}&link={$link}");
    exit;

} else {
    // If the request method is not POST
    $message = urlencode("Invalid request method.");
    $link = urlencode("product-manager/assign-orders.php");
    header("Location: ../../frontend/message.php?message={$message}&link={$link}");
    exit;
}
