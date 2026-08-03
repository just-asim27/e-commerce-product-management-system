<?php

$message = $_REQUEST["message"];
$link = $_REQUEST["link"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
</head>
<style>
    body {
        margin: 0px;
        background-size: cover;
        background-color: #A70E00;
    }

    h1 {
        text-align: center;
        background-color: #FFE179;
        margin: 0px;
        padding: 20px;
    }

    a {
        text-align: center;
        text-decoration: none;
        color: #FFFFFF;
        font-size: 20px;
        margin-top: 40px;
        padding: 0px;
        background-color: rgb(31, 66, 155);
        border-radius: 10px;
    }

    a button {
        padding: 4px;
        width: 15%;
        height: 40px;
        font-size: 20px;
        background-color: rgb(31, 66, 155);
        color: #FFFFFF;
        border: 3px solid #FFFFFF;
        border-radius: 10px;
    }

    button:hover {
        width: 15%;
        height: 40px;
        background-color: gray;
        font-size: 20px;
        cursor: pointer;    
        color: black;
    }

    p {
        text-align: center;
        background-color: #FFE179;
        margin: 0px;
        padding: 20px;
    }

    footer {
        text-align: center;
        background-color: #FFE179;
        padding: 10px;
        position: fixed;
        width: 100%;
        bottom: 0;
    }
</style>
<body>
    <header>
        <h1>E-Commerce Website</h1>
    </header>
    <center>
        <h2 style="color: white;"><?php echo $message; ?></h2>
        <a href="<?php echo $link; ?>"><button>Go Back</button></a>
    </center>
    <footer>   
        <p>&copy; 2025 E-Commerce Website. All rights reserved.</p>
    </footer>
</body>
</html>