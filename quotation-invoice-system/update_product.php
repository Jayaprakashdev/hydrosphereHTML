<?php
include 'config/db.php';

$id    = $_POST['id'];
$name  = $_POST['product_name'];
$price = $_POST['price'];

$stmt = $conn->prepare("UPDATE products SET product_name=?, price=? WHERE id=?");
$stmt->bind_param("sdi", $name, $price, $id);

echo $stmt->execute() ? "updated" : "error";
?>