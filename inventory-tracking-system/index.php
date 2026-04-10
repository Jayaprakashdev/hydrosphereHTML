<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Inventory</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
@media (max-width: 768px) {
    .mobile-gap-15 {
        margin: 15px 0;
    }
    .table-td td {
        white-space: nowrap;
    }
}
</style>
</head>
<body>

<div class="container mt-4">
<h3 class="text-center">Inventory Management</h3>
<a href="report.php" class="btn btn-dark mb-3">View Today Report</a>
<?php
// Today IN & OUT
$today_in = $conn->query("SELECT SUM(quantity) as total FROM stock_transactions WHERE type='IN' AND DATE(created_at)=CURDATE()")->fetch_assoc()['total'] ?? 0;

$today_out = $conn->query("SELECT SUM(quantity) as total FROM stock_transactions WHERE type='OUT' AND DATE(created_at)=CURDATE()")->fetch_assoc()['total'] ?? 0;

// Stock Value
$stock_value = $conn->query("SELECT SUM(quantity * price) as total FROM products")->fetch_assoc()['total'] ?? 0;
?>

<div class="row text-center mb-3">

<div class="col-md-4">
<div class="card bg-success text-white p-3">
<h5>Today IN</h5>
<h3><?= $today_in ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="card bg-danger text-white p-3 mobile-gap-15">
<h5>Today OUT</h5>
<h3><?= $today_out ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="card bg-primary text-white p-3">
<h5>Total Stock Value</h5>
<h3>₹ <?= number_format($stock_value,2) ?></h3>
</div>
</div>

</div>
<!-- Add Form -->
<form method="POST" action="add.php" class="row g-2">
  <div class="col-md-4">
    <input type="text" name="name" class="form-control" placeholder="Product Name" required>
  </div>
  <div class="col-md-3">
    <input type="number" name="quantity" class="form-control" placeholder="Qty" required>
  </div>
  <div class="col-md-3">
    <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
  </div>
  <div class="col-md-2 d-grid">
    <button class="btn btn-primary">Add</button>
  </div>
</form>

<hr>

<!-- Table -->
<div class="table-responsive">
<table id="productTable" class="table table-bordered table-striped table-td">
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Qty</th>
<th>Price</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

<?php
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");

if($result){
  while($row = $result->fetch_assoc()){
?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td>
<?= $row['quantity'] ?>

<?php if($row['quantity'] < 5){ ?>
<span class="badge bg-danger">Low</span>
<?php } ?>

</td>
<td><?= $row['price'] ?></td>
<td>
<a href="stock.php?id=<?= $row['id'] ?>&type=IN" class="btn btn-success btn-sm">IN</a>
<a href="stock.php?id=<?= $row['id'] ?>&type=OUT" class="btn btn-danger btn-sm">OUT</a>
<a href="edit.php?id=<?= $row['id'] ?>" 
   class="btn btn-warning btn-sm">Edit</a>

<!-- <a href="delete.php?id=<?= $row['id'] ?>" 
   class="btn btn-dark btn-sm"
   onclick="return confirm('Are you sure to delete this product?')">
   Delete
</a> -->
</td>
</tr>
<?php 
  }
} else {
  echo "<tr><td colspan='5'>No Data</td></tr>";
}
?>

</tbody>
</table>
</div>

</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#productTable').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50],
        "order": [[0, "desc"]],
        "responsive": true
    });
});
</script>
</body>
</html>