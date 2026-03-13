<?php

$host = "localhost";  // usually localhost
$username = "hydrosph_servicetrack";  
$password = "servicetract012983$$%^#";
$database = "hydrosph_service_tracking";

$conn = mysqli_connect($host, $username, $password, $database);

// $conn = mysqli_connect("localhost","root","","service_tracking");

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>