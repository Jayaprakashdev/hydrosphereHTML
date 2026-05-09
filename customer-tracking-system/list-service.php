<?php
include 'config/db.php';

$engineer = $_GET['engineer'] ?? '';
$status = $_GET['status'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$type = $_GET['type'] ?? '';
$today = date('Y-m-d');

$where = "WHERE 1";

// Status filter
if (!empty($status)) {
    $where .= " AND s.status='$status'";
}

// Engineer filter
if (!empty($engineer)) {
    $where .= " AND s.assigned_to='$engineer'";
}

// Date range filter
if (!empty($from) && !empty($to)) {
    $where .= " AND s.service_date BETWEEN '$from' AND '$to'";
}

// ❌ Exclude completed & dropped ALWAYS for followup view
if (!empty($type)) {
    $where .= " AND s.status NOT IN ('Completed','Drop')";
}

// 🔴 Overdue
if ($type == 'overdue') {
    $where .= " AND s.service_date < '$today'";
}

// 🟢 Today
if ($type == 'today') {
    $where .= " AND s.service_date = '$today'";
}

// 🔵 Upcoming
if ($type == 'upcoming') {
    $where .= " AND s.service_date > '$today'";
}

$sql = "
SELECT 
    s.*, 
    s.installation_date,
    s.description,
    s.note,
    se.name as engineer_name,
    c.name as customer_name,
    c.mobile as customer_mobile,
    c.pincode as customer_pincode
FROM services s
JOIN service_engineers se ON s.assigned_to = se.id
LEFT JOIN customers c ON s.customer_id = c.id
$where
ORDER BY s.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Services</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="assets/css/table.css" rel="stylesheet">
<style>
    .table-white-space td.td-custom-width {
        min-width: 250px;
        white-space: normal;
        word-wrap: break-word;
    }
</style>
</head>

<body class="bg-light">

<div class="container mt-3">

<h5>
Services 

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
<table id="serviceTable" class="table table-bordered table-sm table-white-space">
<thead class="table-light">
<tr>
    <th>ID or LSD</th>
    <th>Date</th>
    <th>Customer</th>
    <th>Pincode</th>
    <th>Product</th>
    <th>Description</th>
    <th>Note</th>
    <!-- <th>Engineer</th> -->
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['installation_date'] ?></td>
            <td><?= $row['service_date'] ?></td>
            <td>
                <strong><?= $row['customer_name'] ?? '-' ?></strong><br>

                <?php if (!empty($row['customer_mobile'])): ?>
                    <a href="tel:<?= $row['customer_mobile'] ?>">📞</a>

                    <a href="https://wa.me/91<?= $row['customer_mobile'] ?>" target="_blank">
                        💬
                    </a>
                <?php endif; ?>
            </td>
            <td><?= $row['customer_pincode'] ?? '-' ?></td>
            <td><?= $row['product'] ?></td>
            <td class="td-custom-width"><?= $row['description'] ?? '-' ?></td>
            <td class="td-custom-width"><?= $row['note'] ?? '-' ?></td>
            <!-- <td><?= $row['engineer_name'] ?></td> -->
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

    var table = $('#serviceTable').DataTable({
        pageLength: 10,
        scrollX: true,   // 🔥 THIS FIXES WIDTH ISSUE
        autoWidth: false,
        columnDefs: [
            { width: "250px", targets: 3 },
            { width: "250px", targets: 4 }
        ]
    });

    // Product filter
    $('#productFilter').on('change', function () {
        var value = $(this).val();
        table.column(4).search(value).draw();
    });

});
</script>
</body>
</html>