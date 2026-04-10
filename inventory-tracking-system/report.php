<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Today Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
.badge-in { background: green; }
.badge-out { background: red; }
@media (max-width: 768px) {
    .table-td td {
        white-space: nowrap;
    }
}
</style>

</head>
<body>

<div class="container mt-4">

<h3 class="text-center mb-3">📊 Today's IN / OUT Report</h3>

<a href="index.php" class="btn btn-secondary mb-3">← Back</a>

<div class="table-responsive">
<table id="reportTable" class="table table-bordered table-striped text-center align-middle table-td">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Product</th>
<th>Type</th>
<th>Engineer</th>
<th>Qty</th>
<th>Date</th>
</tr>
</thead>

<tbody>

<?php
$query = "
SELECT 
  s.id,
  p.name, 
  s.type, 
  s.quantity, 
  s.created_at, 
  e.name as engineer
FROM stock_transactions s
JOIN products p ON p.id = s.product_id
LEFT JOIN engineers e ON e.id = s.engineer_id
WHERE DATE(s.created_at) = CURDATE()
ORDER BY s.id DESC
";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
?>

<tr>
<td><?= $row['id'] ?></td>

<td><?= htmlspecialchars($row['name']) ?></td>

<td>
<span class="badge <?= $row['type']=='IN' ? 'badge-in' : 'badge-out' ?>">
<?= $row['type'] ?>
</span>
</td>

<td>
<?= $row['engineer'] ? $row['engineer'] : '<span class="text-muted">N/A</span>' ?>
</td>

<td><?= $row['quantity'] ?></td>

<td><?= date('d-m-Y h:i A', strtotime($row['created_at'])) ?></td>

</tr>

<?php 
  }

} else {
  echo "<tr><td colspan='6'>No records found for today</td></tr>";
}
?>

</tbody>
</table>
</div>

</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function () {
    $('#reportTable').DataTable({
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        order: [[0, "desc"]],
        dom: 'Bfrtip',
        buttons: [],
        responsive: true
    });
});
</script>
</body>
</html>