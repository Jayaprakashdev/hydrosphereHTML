<?php

$host = "localhost";  // usually localhost
$username = "hydrosph_saletracking";  
$password = "servicetract012983$$%^#";
$database = "hydrosph_sales_tracking";

$conn = mysqli_connect($host, $username, $password, $database);

// $conn = mysqli_connect("localhost","root","","sales_tracking");

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>