<?php
include 'config/db.php';

$engineer = $_GET['engineer'] ?? '';
$status = $_GET['status'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$type = $_GET['type'] ?? '';
$today = date('Y-m-d');

$where = "WHERE 1";

if (!empty($status)) {
    $where .= " AND c.status='$status'";
}

if (!empty($engineer)) {
    $where .= " AND c.assigned_to='$engineer'";
}

if (!empty($from) && !empty($to)) {
    $where .= " AND c.complaint_date BETWEEN '$from' AND '$to'";
}

$where = "WHERE 1";

// Status filter
if (!empty($status)) {
    $where .= " AND c.status='$status'";
}

// Engineer filter
if (!empty($engineer)) {
    $where .= " AND c.assigned_to='$engineer'";
}

// Date filter
if (!empty($from) && !empty($to)) {
    $where .= " AND c.complaint_date BETWEEN '$from' AND '$to'";
}

// ❌ Exclude Completed & Drop for followup view
if (!empty($type)) {
    $where .= " AND c.status NOT IN ('Completed','Drop')";
}

// 🔴 Overdue
if ($type == 'overdue') {
    $where .= " AND c.complaint_date < '$today'";
}

// 🟢 Today
if ($type == 'today') {
    $where .= " AND c.complaint_date = '$today'";
}

// 🔵 Upcoming
if ($type == 'upcoming') {
    $where .= " AND c.complaint_date > '$today'";
}

$sql = "
SELECT c.*, se.name as engineer_name
FROM complaints c
JOIN service_engineers se ON c.assigned_to = se.id
$where
ORDER BY c.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Complaints</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body class="bg-light">

<div class="container mt-3">

<h5>
Complaints 

<?php if ($type == 'overdue'): ?>
    (Overdue)
<?php elseif ($type == 'today'): ?>
    (Today)
<?php elseif ($type == 'upcoming'): ?>
    (Upcoming)
<?php elseif (!empty($from) && !empty($to)): ?>
    (<?= $from ?> to <?= $to ?>)
<?php else: ?>
    (All Records)
<?php endif; ?>

</h5>

<div class="row mb-2">

    <div class="col-12 col-md-3 mb-2">
        <select id="productFilter" class="form-control">
            <option value="">All Products</option>
            <option>Water Softener</option>
            <option>Industrial RO Plants</option>
            <option>DMF & IRON Removal</option>
            <option>Alkaline Ionizer</option>
            <option>Alkaline Water Purifiers</option>
            <option>Domestic RO</option>
            <option>Commercial RO</option>
        </select>
    </div>

</div>

<div class="table-responsive">
<table id="complaintTable" class="table table-bordered table-sm">
<thead class="table-light">
<tr>
<th>Date</th>
<th>Product</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['complaint_date'] ?></td>
            <td><?= $row['product'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <a href="customer-view.php?id=<?= $row['customer_id'] ?>" 
                   class="btn btn-sm btn-primary">
                   View
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center text-danger">No data found</td>
    </tr>
<?php endif; ?>
</tbody>

</table>
</div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {

    var table = $('#complaintTable').DataTable({
        "pageLength": 10
    });

    // Product filter
    $('#productFilter').on('change', function () {
        var value = $(this).val();
        table.column(1).search(value).draw();
    });

});
</script>
</body>
</html>