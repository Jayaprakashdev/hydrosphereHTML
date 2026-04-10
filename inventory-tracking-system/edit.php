<?php
include 'config.php';

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $qty = $_POST['quantity'];
    $price = $_POST['price'];

    $conn->query("UPDATE products 
                  SET name='$name', quantity='$qty', price='$price' 
                  WHERE id=$id");

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
<h3>Edit Product</h3>

<form method="POST">
<input type="text" name="name" value="<?= $data['name'] ?>" class="form-control mb-2" required>
<input type="number" name="quantity" value="<?= $data['quantity'] ?>" class="form-control mb-2" required>
<input type="number" step="0.01" name="price" value="<?= $data['price'] ?>" class="form-control mb-2" required>

<button class="btn btn-primary w-100">Update</button>
</form>

</div>

</body>
</html>