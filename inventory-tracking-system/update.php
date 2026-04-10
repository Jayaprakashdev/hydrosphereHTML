<?php
include 'config.php';
$id = $_POST['id'];
$name = $_POST['name'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

$conn->query("UPDATE products SET name='$name', quantity='$quantity', price='$price' WHERE id=$id");

header("Location: index.php");
?>