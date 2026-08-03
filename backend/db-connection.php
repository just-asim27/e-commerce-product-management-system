<?php 

$server = 'localhost';
$username = 'root';
$password = '';
$database = 'ecommerce';

$connect = mysqli_connect($server, $username, $password, $database);

if (!$connect) {
	echo "Database didn't connect!<br>";
}

?>