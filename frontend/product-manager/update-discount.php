<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/product-manager/update-inventory.css">
    <title>Update Discount</title>
</head>
<body>
    <header>
        <h1>E-Commerce Website</h1>
    </header>
    <div class="back-button-container">
        <a href="manage-discounts.html" class="back-button">← Back</a>
    </div>
    <center>
        <form action="../../backend/product-manager/new-discount.php" method="post" style="margin-top: 45px;">
            <fieldset>
                <h2><i>Update Discount</i></h2>
                <input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>">
                <p>Product Name: <?php echo $_GET['product_name']; ?></p>
                <p>Current Discount: <?php echo $_GET['product_discount']; ?></p><br>
                <label>New Discount:</label><br>
                <input type="number" name="new_discount" placeholder="Enter New Discount" required step="0.01"><br><br>
                <a href=""><button type="submit">Enter</button></a>
            </fieldset>
        </form> 
    </center>
    <br><br>
    <footer>   
        <p>&copy; 2025 E-Commerce Website. All rights reserved.</p>
    </footer>
</body>
</html>