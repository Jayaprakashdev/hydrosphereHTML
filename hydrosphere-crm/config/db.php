<?php

$host = "localhost";  // usually localhost
$username = "hydrosph_crm-user";  
$password = "Hydro@pwd!@5#$";
$database = "hydrosph_crm";

$conn = mysqli_connect($host, $username, $password, $database);

// $conn = mysqli_connect("localhost","root","","hydrosphere_crm");

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>