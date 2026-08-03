<?php

session_start();
unset($_SESSION['product_manager_id']);
header("Location: ../../index.html");
exit();

?>