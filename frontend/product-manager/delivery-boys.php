<?php
include '../../backend/db-connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Order</title>
    <link rel="stylesheet" href="../../css/product-manager/dashboard.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #A70E00;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #FFE179;
            padding: 20px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #FFFFFF;
            margin-top: 20px;
        }

        .delivery-list {
            width: 80%;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
        }

        .delivery-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f1f1f1;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 8px;
        }

        .delivery-info {
            font-size: 18px;
            color: #333;
        }

        .assign-button {
            padding: 10px 15px;
            background-color: rgb(31, 66, 155);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .assign-button:hover {
            background-color: gray;
            color: black;
        }

        footer {
            background-color: #FFE179;
            text-align: center;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>E-Commerce Product Management System</h1>
    </header>

    <div class="main-content">
        <h2>Assign Order to Delivery Boy</h2>

        <div class="delivery-list">
            <?php
            $sql = "SELECT * FROM delivery_boys";
            $result = $connect->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="delivery-item">';
                    echo '<div class="delivery-info">';
                    echo '<strong>' . htmlspecialchars($row['delivery_boy_name']) . '</strong><br>';
                    echo 'Phone: ' . htmlspecialchars($row['delivery_boy_phone']) . '<br>';
                    echo 'Email: ' . htmlspecialchars($row['delivery_boy_email']);
                    echo '</div>';
                    echo '<form action="../../backend/product-manager/assign-order-handler.php" method="post">';
                    echo '<input type="hidden" name="delivery_boy_id" value="' . $row['delivery_boy_id'] . '">';
                    echo '<button type="submit" class="assign-button">Assign Order</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "<p>No delivery boys found.</p>";
            }

            $connect->close();
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
    </footer>
</body>
</html>
