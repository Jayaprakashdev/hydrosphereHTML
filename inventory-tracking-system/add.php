<?php
include 'config.php';

$name = $_POST['name'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

$conn->query("INSERT INTO products(name,quantity,price) VALUES('$name','$quantity','$price')");

header("Location: index.php");
?>