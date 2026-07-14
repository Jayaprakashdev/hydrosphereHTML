<?php

$host = "localhost";  // usually localhost
$username = "hydrosph_customertracking";
$password = "servicetract012983$$%^#";
$database = "hydrosph_customer_tracking";

$conn = mysqli_connect($host, $username, $password, $database);

// $conn = mysqli_connect("localhost","root","","customer_tracking");

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>