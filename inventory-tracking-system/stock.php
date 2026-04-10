<?php
include 'config.php';

$id = $_GET['id'];
$type = $_GET['type'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $qty = $_POST['quantity'];
    $engineer_id = $_POST['engineer_id'];

    // ✅ ONLY ONE INSERT
    $conn->query("INSERT INTO stock_transactions(product_id,type,quantity,engineer_id) 
    VALUES('$id','$type','$qty','$engineer_id')");

    // ✅ Update main stock
    if ($type == 'IN') {
        $conn->query("UPDATE products SET quantity = quantity + $qty WHERE id=$id");
    } else {
        $conn->query("UPDATE products SET quantity = quantity - $qty WHERE id=$id");
    }

    // ✅ Prevent duplicate submit
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

<h3><?= htmlspecialchars($type) ?> Stock</h3>

<form method="POST">

<input type="number" name="quantity" class="form-control mb-2" placeholder="Enter Quantity" required>

<select name="engineer_id" class="form-control mb-2" required>
<option value="">Select Engineer</option>

<?php
$eng = $conn->query("SELECT * FROM engineers");
while($e = $eng->fetch_assoc()){
?>
<option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['name']) ?></option>
<?php } ?>

</select>

<button class="btn btn-primary w-100">Submit</button>

</form>

</div>

</body>
</html>