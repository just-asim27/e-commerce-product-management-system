<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/product-manager/update-inventory.css">
    <title>Update Price</title>
</head>
<body>
    <header>
        <h1>E-Commerce Product Management System</h1>
    </header>
    <div class="back-button-container">
        <a href="change-price.html" class="back-button">← Back</a>
    </div>
    <center>
        <form action="../../backend/product-manager/new-price.php" method="post" style="margin-top: 50px;">
            <fieldset>
                <h2><i>Update Price</i></h2>
                <input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>">
                <p>Product Name: <?php echo $_GET['product_name']; ?></p>
                <p>Current Price: <?php echo $_GET['product_price']; ?></p><br>
                <label>New Price:</label><br>
                <input type="number" name="new_price" placeholder="Enter New Price" required><br><br>
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