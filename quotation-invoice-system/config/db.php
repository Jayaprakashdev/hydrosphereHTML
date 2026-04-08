<?php

$host = "localhost";  // usually localhost
$username = "hydrosph_quotation_invoice";
$password = "servicetract012983$$%^#";
$database = "hydrosph_qi_system";

$conn = mysqli_connect($host, $username, $password, $database);

// $conn = new mysqli("localhost", "root", "", "invoice_quotation");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>