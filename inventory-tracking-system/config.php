<?php

$host = "localhost";  // usually localhost
$username = "hydrosph_inventorytrack";  
$password = "servicetract012983$$%^#";
$database = "hydrosph_inventory_tracking";

$conn = mysqli_connect($host, $username, $password, $database);

// $conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>