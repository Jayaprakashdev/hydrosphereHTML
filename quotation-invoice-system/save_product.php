<?php
include 'config/db.php';

$name  = $_POST['product_name'];
$price = $_POST['price'];

$stmt = $conn->prepare("INSERT INTO products (product_name, price) VALUES (?, ?)");
$stmt->bind_param("sd", $name, $price);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
?>