<?php

$host = "localhost";  // usually localhost
$username = "hydrosph_moneytrack";  
$password = "servicetract012983$$%^#";
$database = "hydrosph_moneytracking";

$conn = mysqli_connect($host, $username, $password, $database);

// $conn = new mysqli("localhost", "root", "", "work_tracker");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>