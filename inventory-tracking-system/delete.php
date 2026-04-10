<?php
include 'config.php';

$id = $_GET['id'];

// Optional: delete related transactions first
$conn->query("DELETE FROM stock_transactions WHERE product_id=$id");

// Delete product
$conn->query("DELETE FROM products WHERE id=$id");

header("Location: index.php");
?>