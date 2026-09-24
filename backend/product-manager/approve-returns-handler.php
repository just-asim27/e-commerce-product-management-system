<?php
session_start();
include '../db-connection.php';

if (!isset($_SESSION['manager_id'])) {
    header("Location: ../../frontend/product-manager/login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager_id = intval($_SESSION['manager_id']);
    $return_id = isset($_POST['return_id']) ? intval($_POST['return_id']) : 0;
    $delivery_boy_id = isset($_POST['delivery_boy_id']) ? intval($_POST['delivery_boy_id']) : 0;

    // Validate inputs
    if ($return_id > 0 && $delivery_boy_id > 0) {
        // Check if the return contains a product added by this manager
        $checkSql = "
            SELECT COUNT(*) AS count
            FROM returns r
            INNER JOIN products p ON r.product_id = p.product_id
            WHERE r.return_id = ? AND p.manager_id = ?
        ";
        $stmtCheck = $connect->prepare($checkSql);
        $stmtCheck->bind_param("ii", $return_id, $manager_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $row = $resultCheck->fetch_assoc();

        if ($row && $row['count'] > 0) {
            // Perform the update: assign delivery boy, set return status to approved and update return_date
            $updateSql = "UPDATE returns SET delivery_boy_id = ?, return_status = 'approved', return_date = NOW() WHERE return_id = ?";
            $stmtUpdate = $connect->prepare($updateSql);
            $stmtUpdate->bind_param("ii", $delivery_boy_id, $return_id);

            if ($stmtUpdate->execute()) {
                $message = urlencode("Return successfully assigned to delivery boy and approved.");
            } else {
                $message = urlencode("Failed to assign return. Please try again.");
            }
        } else {
            $message = urlencode("Unauthorized: You can only assign returns containing your products.");
        }
    } else {
        $message = urlencode("Invalid input. Please select a valid delivery boy.");
    }

    // Redirect link to approve returns page in frontend
    $link = urlencode("product-manager/dashboard.html");
    header("Location: ../../frontend/message.php?message={$message}&link={$link}");
    exit;

} else {
    // If the request method is not POST
    $message = urlencode("Invalid request method.");
    $link = urlencode("product-manager/approve-returns.php");
    header("Location: ../../frontend/message.php?message={$message}&link={$link}");
    exit;
}
