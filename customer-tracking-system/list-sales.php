<?php
include 'config/db.php';

// Filters
$engineer = $_GET['engineer'] ?? '';
$status   = $_GET['status'] ?? '';
$from     = $_GET['from'] ?? '';
$to       = $_GET['to'] ?? '';
$type     = $_GET['type'] ?? '';

$today = date('Y-m-d');

$where = "WHERE 1";

// Status
if (!empty($status)) {
    $where .= " AND s.status='$status'";
}

// Engineer
if (!empty($engineer)) {
    $where .= " AND s.assigned_to='$engineer'";
}

// Date filter
if (!empty($from) && !empty($to)) {
    $where .= " AND s.sale_date BETWEEN '$from' AND '$to'";
}

// ✅ IMPORTANT: Only pending payments
// $where .= " AND s.pending_amount > 0";

// ✅ Followup filters
if ($type == 'overdue') {
    $where .= " AND s.sale_date < '$today' AND s.status NOT IN ('Completed','Drop')";
}

if ($type == 'today') {
    $where .= " AND s.sale_date = '$today' AND s.status NOT IN ('Completed','Drop')";
}

if ($type == 'upcoming') {
    $where .= " AND s.sale_date > '$today' AND s.status NOT IN ('Completed','Drop')";
}

// 💰 Payment Followup Filters
if ($type == 'overdue_payment') {
    $where .= " AND s.pending_amount > 0 
                AND s.sale_date < '$today' 
                AND s.status NOT IN ('Completed','Drop')";
}

if ($type == 'today_payment') {
    $where .= " AND s.pending_amount > 0 
                AND s.sale_date = '$today' 
                AND s.status NOT IN ('Completed','Drop')";
}

if ($type == 'upcoming_payment') {
    $where .= " AND s.pending_amount > 0 
                AND s.sale_date > '$today' 
                AND s.status NOT IN ('Completed','Drop')";
}

// Query
$sql = "
SELECT s.*, se.name as engineer_name
FROM sales s
LEFT JOIN service_engineers se ON s.assigned_to = se.id
$where
ORDER BY s.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Sales</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body class="bg-light">

<div class="container mt-3">

<h5>
Sales 
<?php if (!empty($from) && !empty($to)): ?>
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
<table id="salesTable" class="table table-bordered table-sm">
<thead class="table-light">
<tr>
<th>Date</th>
<th>Product</th>
<th>Total</th>
<th>Advance</th>
<th>Pending</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
            <?php
            if ($row['sale_date'] < $today) {
                echo "<span class='text-danger fw-bold'>{$row['sale_date']}</span>";
            } elseif ($row['sale_date'] == $today) {
                echo "<span class='text-success fw-bold'>{$row['sale_date']}</span>";
            } else {
                echo $row['sale_date'];
            }
            ?>
            </td>
            <td><?= $row['product'] ?></td>
            <td><?= $row['total_amount'] ?></td>
            <td><?= $row['advance_amount'] ?></td>
            <td>
                <?php if ($row['pending_amount'] > 0): ?>
                    <span class="text-danger fw-bold">₹<?= $row['pending_amount'] ?></span>
                <?php else: ?>
                    <span class="text-success">Paid</span>
                <?php endif; ?>
            </td>
            <td><?= $row['status'] ?></td>
            <td>
                <a href="customer-view.php?id=<?= $row['customer_id'] ?>" 
                   class="btn btn-sm btn-primary">
                   View
                </a>
                <a href="https://wa.me/91<?= $row['customer_mobile'] ?>?text=<?= urlencode(
                    "Hello, your pending payment is ₹".$row['pending_amount'].". Please make payment."
                    ) ?>" 
                    target="_blank" 
                    class="btn btn-sm btn-success">
                    💬
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="text-center text-danger">No data found</td>
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

    var table = $('#salesTable').DataTable({
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