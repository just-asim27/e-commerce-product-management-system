<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/product-manager/update-inventory.css">
    <title>Update Quantity</title>
</head>
<body>
    <header>
        <h1>E-Commerce Product Management System</h1>
    </header>
    <div class="back-button-container">
        <a href="update-inventory.html" class="back-button">← Back</a>
    </div>
    <center>
        <form action="../../backend/product-manager/new-quantity.php" method="post" style =" margin-top: 45px;">
            <fieldset>
                <h2><i>Update Quantity</i></h2>
                <input type="hidden" min="1" max="3" name="product_id" value="<?php echo $_GET['product_id']; ?>">
                <p>Product Name: <?php echo $_GET['product_name']; ?></p>
                <p>Current Quantity: <?php echo $_GET['product_quantity']; ?></p><br>
                <label>New Quantity:</label><br>
                <input type="number" name="new_quantity" placeholder="Enter Quantity" required><br><br>
                <a href=""><button type="submit">Enter</button></a>
            </fieldset>
        </form>    
    </center>
    <br><br>
    <footer>   
        <p>&copy; 2025 E-Commerce Product Management System. All rights reserved.</p>
    </footer>
</body>
</html>