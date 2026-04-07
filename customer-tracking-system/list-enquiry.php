<?php
include 'config/db.php';

// Get filters
$from     = $_GET['from'] ?? '';
$to       = $_GET['to'] ?? '';
$status   = $_GET['status'] ?? '';
$engineer = $_GET['engineer'] ?? '';
$type     = $_GET['type'] ?? '';

$today = date('Y-m-d');

// ✅ SINGLE WHERE (NO DUPLICATE)
$where = "WHERE 1";

// Status filter
if (!empty($status)) {
    $where .= " AND e.status='$status'";
}

// Engineer filter
if (!empty($engineer)) {
    $where .= " AND se.name='$engineer'";
}

// Date filter
if (!empty($from) && !empty($to)) {
    $where .= " AND e.enquiry_date BETWEEN '$from' AND '$to'";
}

// ✅ Followup filters (exclude completed/drop automatically)
if ($type == 'overdue') {
    $where .= " AND e.followup_date < '$today' AND e.status NOT IN ('Completed','Drop')";
}

if ($type == 'today') {
    $where .= " AND e.followup_date = '$today' AND e.status NOT IN ('Completed','Drop')";
}

if ($type == 'upcoming') {
    $where .= " AND e.followup_date > '$today' AND e.status NOT IN ('Completed','Drop')";
}

// ✅ FINAL QUERY (ONLY ONCE)
$sql = "
SELECT e.*, se.name as engineer_name
FROM enquiries e
LEFT JOIN service_engineers se ON e.assigned_to = se.id
$where
ORDER BY e.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Enquiries</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

</head>

<body class="bg-light">

<div class="container mt-3">

<h5>
Enquiries 
<?php if ($from && $to): ?>
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
<table id="enquiryTable" class="table table-bordered table-sm">

<thead class="table-light">
<tr>
<th>Date</th>
<th>Follow Up Date</th>
<th>Product</th>
<th>Engineer</th>
<th>Status</th>
<th>Amount</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['enquiry_date'] ?></td>

            <!-- ✅ Highlight followup -->
            <td>
                <?php
                if ($row['followup_date'] < $today) {
                    echo "<span class='text-danger fw-bold'>{$row['followup_date']}</span>";
                } elseif ($row['followup_date'] == $today) {
                    echo "<span class='text-success fw-bold'>{$row['followup_date']}</span>";
                } else {
                    echo $row['followup_date'];
                }
                ?>
            </td>

            <td><?= $row['product'] ?></td>
            <td><?= $row['engineer_name'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['amount'] ?? '-' ?></td>

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
        <td colspan="6" class="text-center text-danger">No data found</td>
    </tr>
<?php endif; ?>

</tbody>
</table>
</div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {

    var table = $('#enquiryTable').DataTable({
        pageLength: 10
    });

    // ✅ FIXED column index (Product = column 2)
    $('#productFilter').on('change', function () {
        var value = $(this).val();
        table.column(2).search(value).draw();
    });

});
</script>

</body>
</html>