<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config/db.php';

// Filter
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

// ✅ WHERE conditions FIRST (IMPORTANT)
$whereEnq = (!empty($from) && !empty($to)) ? "WHERE enquiry_date BETWEEN '$from' AND '$to'" : "";
$whereSales = (!empty($from) && !empty($to)) ? "WHERE sale_date BETWEEN '$from' AND '$to'" : "";
$whereInstall = (!empty($from) && !empty($to)) ? "WHERE installation_date BETWEEN '$from' AND '$to'" : "";
$whereService = (!empty($from) && !empty($to)) ? "WHERE service_date BETWEEN '$from' AND '$to'" : "";
$whereComplaint = (!empty($from) && !empty($to)) ? "WHERE complaint_date BETWEEN '$from' AND '$to'" : "";

// Total Customers
$totalCustomers = $conn->query("SELECT COUNT(*) as total FROM customers")
    ->fetch_assoc()['total'];

// Counts
$enq = $conn->query("SELECT COUNT(*) as total FROM enquiries $whereEnq")->fetch_assoc()['total'];
$sales = $conn->query("SELECT COUNT(*) as total FROM sales $whereSales")->fetch_assoc()['total'];
$install = $conn->query("SELECT COUNT(*) as total FROM installations $whereInstall")->fetch_assoc()['total'];
$service = $conn->query("SELECT COUNT(*) as total FROM services $whereService")->fetch_assoc()['total'];
$complaint = $conn->query("SELECT COUNT(*) as total FROM complaints $whereComplaint")->fetch_assoc()['total'];

// ✅ SAFE base WHERE for status
$baseWhere = (!empty($from) && !empty($to)) 
    ? "WHERE enquiry_date BETWEEN '$from' AND '$to'" 
    : "WHERE 1";

// Enquiry Status Counts
$open = $conn->query("SELECT COUNT(*) as total FROM enquiries $baseWhere AND status='Open'")->fetch_assoc()['total'];
$inprogress = $conn->query("SELECT COUNT(*) as total FROM enquiries $baseWhere AND status='Inprogress'")->fetch_assoc()['total'];
$completed = $conn->query("SELECT COUNT(*) as total FROM enquiries $baseWhere AND status='Completed'")->fetch_assoc()['total'];
$dropped = $conn->query("SELECT COUNT(*) as total FROM enquiries $baseWhere AND status='Drop'")->fetch_assoc()['total'];

// Sales Status Counts
$baseWhereSales = (!empty($from) && !empty($to)) 
    ? "WHERE sale_date BETWEEN '$from' AND '$to'" 
    : "WHERE 1";

$sale_new = $conn->query("SELECT COUNT(*) as total FROM sales $baseWhereSales AND status='Open'")
    ->fetch_assoc()['total'];

$sale_partial = $conn->query("SELECT COUNT(*) as total FROM sales $baseWhereSales AND status='Inprogress'")
    ->fetch_assoc()['total'];

$sale_completed = $conn->query("SELECT COUNT(*) as total FROM sales $baseWhereSales AND status='Completed'")
    ->fetch_assoc()['total'];

$sale_cancelled = $conn->query("SELECT COUNT(*) as total FROM sales $baseWhereSales AND status='Drop'")
    ->fetch_assoc()['total'];

// Installation Status Counts
$baseWhereInstall = (!empty($from) && !empty($to)) 
    ? "WHERE installation_date BETWEEN '$from' AND '$to'" 
    : "WHERE 1";

$inst_pending = $conn->query("SELECT COUNT(*) as total FROM installations $baseWhereInstall AND status='Open'")
    ->fetch_assoc()['total'];

$inst_inprogress = $conn->query("SELECT COUNT(*) as total FROM installations $baseWhereInstall AND status='Inprogress'")
    ->fetch_assoc()['total'];

$inst_completed = $conn->query("SELECT COUNT(*) as total FROM installations $baseWhereInstall AND status='Completed'")
    ->fetch_assoc()['total'];

$inst_cancelled = $conn->query("SELECT COUNT(*) as total FROM installations $baseWhereInstall AND status='Drop'")
    ->fetch_assoc()['total'];

// Service Status Counts
$baseWhereService = (!empty($from) && !empty($to)) 
    ? "WHERE service_date BETWEEN '$from' AND '$to'" 
    : "WHERE 1";

$service_pending = $conn->query("SELECT COUNT(*) as total FROM services $baseWhereService AND status='Open'")
    ->fetch_assoc()['total'];

$service_inprogress = $conn->query("SELECT COUNT(*) as total FROM services $baseWhereService AND status='Inprogress'")
    ->fetch_assoc()['total'];

$service_completed = $conn->query("SELECT COUNT(*) as total FROM services $baseWhereService AND status='Completed'")
    ->fetch_assoc()['total'];

$service_cancelled = $conn->query("SELECT COUNT(*) as total FROM services $baseWhereService AND status='Drop'")
    ->fetch_assoc()['total'];

    // Complaint Status Counts
$baseWhereComplaint = (!empty($from) && !empty($to)) 
    ? "WHERE complaint_date BETWEEN '$from' AND '$to'" 
    : "WHERE 1";

$comp_open = $conn->query("SELECT COUNT(*) as total FROM complaints $baseWhereComplaint AND status='Open'")
    ->fetch_assoc()['total'];

$comp_inprogress = $conn->query("SELECT COUNT(*) as total FROM complaints $baseWhereComplaint AND status='Inprogress'")
    ->fetch_assoc()['total'];

$comp_resolved = $conn->query("SELECT COUNT(*) as total FROM complaints $baseWhereComplaint AND status='Completed'")
    ->fetch_assoc()['total'];

$comp_closed = $conn->query("SELECT COUNT(*) as total FROM complaints $baseWhereComplaint AND status='Drop'")
    ->fetch_assoc()['total'];

$engineerData = [];

$where = "";
if (!empty($from) && !empty($to)) {
    $where = "AND e.enquiry_date BETWEEN '$from' AND '$to'";
}

$sql = "
SELECT 
    se.id,
    se.name,
    COUNT(DISTINCT CASE WHEN e.status='Open' THEN e.id END) as open_count,
    COUNT(DISTINCT CASE WHEN e.status='Inprogress' THEN e.id END) as inprogress_count,
    COUNT(DISTINCT CASE WHEN e.status='Completed' THEN e.id END) as completed_count,
    COUNT(DISTINCT CASE WHEN e.status='Drop' THEN e.id END) as drop_count
FROM service_engineers se
LEFT JOIN enquiries e ON e.assigned_to = se.id $where
GROUP BY se.id
ORDER BY se.name ASC
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $engineerData[] = $row;
}

$salesEngineerData = [];

$whereSales = "";
if (!empty($from) && !empty($to)) {
    $whereSales = "AND s.sale_date BETWEEN '$from' AND '$to'";
}

$sqlSales = "
SELECT 
    se.id,
    se.name,
    COUNT(DISTINCT CASE WHEN s.status='Open' THEN s.id END) as open_count,
    COUNT(DISTINCT CASE WHEN s.status='Inprogress' THEN s.id END) as inprogress_count,
    COUNT(DISTINCT CASE WHEN s.status='Completed' THEN s.id END) as completed_count,
    COUNT(DISTINCT CASE WHEN s.status='Drop' THEN s.id END) as drop_count
FROM service_engineers se
LEFT JOIN sales s ON s.assigned_to = se.id $whereSales
GROUP BY se.id
ORDER BY se.name ASC
";

$resultSales = $conn->query($sqlSales);

while ($row = $resultSales->fetch_assoc()) {
    $salesEngineerData[] = $row;
}

$installationEngineerData = [];

$whereInstall = "";
if (!empty($from) && !empty($to)) {
    $whereInstall = "AND i.installation_date BETWEEN '$from' AND '$to'";
}

$sqlInstall = "
SELECT 
    se.id,
    se.name,
    COUNT(DISTINCT CASE WHEN i.status='Open' THEN i.id END) as open_count,
    COUNT(DISTINCT CASE WHEN i.status='Inprogress' THEN i.id END) as inprogress_count,
    COUNT(DISTINCT CASE WHEN i.status='Completed' THEN i.id END) as completed_count,
    COUNT(DISTINCT CASE WHEN i.status='Drop' THEN i.id END) as drop_count
FROM service_engineers se
LEFT JOIN installations i ON i.assigned_to = se.id $whereInstall
GROUP BY se.id
ORDER BY se.name ASC
";

$resultInstall = $conn->query($sqlInstall);

while ($row = $resultInstall->fetch_assoc()) {
    $installationEngineerData[] = $row;
}

$serviceEngineerData = [];

$whereService = "";
if (!empty($from) && !empty($to)) {
    $whereService = "AND s.service_date BETWEEN '$from' AND '$to'";
}

$sqlService = "
SELECT 
    se.id,
    se.name,
    COUNT(DISTINCT CASE WHEN s.status='Open' THEN s.id END) as open_count,
    COUNT(DISTINCT CASE WHEN s.status='Inprogress' THEN s.id END) as inprogress_count,
    COUNT(DISTINCT CASE WHEN s.status='Completed' THEN s.id END) as completed_count,
    COUNT(DISTINCT CASE WHEN s.status='Drop' THEN s.id END) as drop_count
FROM service_engineers se
LEFT JOIN services s ON s.assigned_to = se.id $whereService
GROUP BY se.id
ORDER BY se.name ASC
";

$resultService = $conn->query($sqlService);

while ($row = $resultService->fetch_assoc()) {
    $serviceEngineerData[] = $row;
}

$complaintEngineerData = [];

$whereComplaint = "";
if (!empty($from) && !empty($to)) {
    $whereComplaint = "AND c.complaint_date BETWEEN '$from' AND '$to'";
}

$sqlComplaint = "
SELECT 
    se.id,
    se.name,
    COUNT(DISTINCT CASE WHEN c.status='Open' THEN c.id END) as open_count,
    COUNT(DISTINCT CASE WHEN c.status='Inprogress' THEN c.id END) as inprogress_count,
    COUNT(DISTINCT CASE WHEN c.status='Completed' THEN c.id END) as completed_count,
    COUNT(DISTINCT CASE WHEN c.status='Drop' THEN c.id END) as drop_count
FROM service_engineers se
LEFT JOIN complaints c ON c.assigned_to = se.id $whereComplaint
GROUP BY se.id
ORDER BY se.name ASC
";

$resultComplaint = $conn->query($sqlComplaint);

while ($row = $resultComplaint->fetch_assoc()) {
    $complaintEngineerData[] = $row;
}

// Chart Data (Last 7 Days)
$labels = [];
$salesData = [];
$enquiryData = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));

    $labels[] = $date;

    $salesData[] = $conn->query("SELECT COUNT(*) as total FROM sales WHERE sale_date='$date'")
        ->fetch_assoc()['total'];

    $enquiryData[] = $conn->query("SELECT COUNT(*) as total FROM enquiries WHERE enquiry_date='$date'")
        ->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Tracking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .card:hover {
            transform: scale(1.05);
            transition: 0.2s;
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-4">

    <h4 class="mb-3">Customer Tracking System</h4>

    <!-- FILTER -->
    <form method="GET" class="row mb-3">

    <div class="col-md-3">
        <label>From Date</label>
        <input type="date" name="from" value="<?= $from ?>" class="form-control">
    </div>

    <div class="col-md-3">
        <label>To Date</label>
       <input type="date" name="to" value="<?= $to ?>" class="form-control">
    </div>

    <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-primary w-100">Filter</button>
</div>

<div class="col-md-2 d-flex align-items-end">
    <a href="index.php" class="btn btn-secondary w-100">Clear</a>
</div>

</form>

    <!-- CARDS -->
    <div class="row">

        <div class="col-6 col-md-3 mb-3">
            <a href="customers.php" class="text-decoration-none">
                <div class="card bg-primary text-white text-center p-3 shadow">
                    <h6>Total Customers</h6>
                    <h3><?= $totalCustomers ?></h3>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <a href="list-enquiry.php?from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-info text-white text-center p-3 shadow">
                    <h6>Enquiry</h6>
                    <h3><?= $enq ?></h3>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <a href="list-sales.php?from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-success text-white text-center p-3 shadow">
                    <h6>Sales</h6>
                    <h3><?= $sales ?></h3>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <a href="list-installation.php?from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-warning text-dark text-center p-3 shadow">
                    <h6>Installation</h6>
                    <h3><?= $install ?></h3>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <a href="list-service.php?from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-secondary text-white text-center p-3 shadow">
                    <h6>Service</h6>
                    <h3><?= $service ?></h3>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <a href="list-complaint.php?from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-danger text-white text-center p-3 shadow">
                    <h6>Complaint</h6>
                    <h3><?= $complaint ?></h3>
                </div>
            </a>
        </div>

    </div>

    <div class="card p-3 mb-4">
    <h5>Enquiry Status</h5>

    <div class="row text-center">

        <div class="col-6 col-md-3 mb-2">
            <a href="list-enquiry.php?status=Open&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-primary text-white p-2">
                    <h6>Open</h6>
                    <h4><?= $open ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-enquiry.php?status=Inprogress&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-warning text-dark p-2">
                    <h6>In Progress</h6>
                    <h4><?= $inprogress ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-enquiry.php?status=Completed&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-success text-white p-2">
                    <h6>Completed</h6>
                    <h4><?= $completed ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-enquiry.php?status=Drop&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-danger text-white p-2">
                    <h6>Dropped</h6>
                    <h4><?= $dropped ?></h4>
                </div>
            </a>
        </div>

    </div>
</div>

<div class="card p-3 mb-4">
    <h5>Sales Status</h5>

    <div class="row text-center">

        <div class="col-6 col-md-3 mb-2">
            <a href="list-sales.php?status=Open&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-primary text-white p-2">
                    <h6>Open</h6>
                    <h4><?= $sale_new ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-sales.php?status=Inprogress&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-warning text-dark p-2">
                    <h6>In Progress</h6>
                    <h4><?= $sale_partial ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-sales.php?status=Completed&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-success text-white p-2">
                    <h6>Completed</h6>
                    <h4><?= $sale_completed ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-sales.php?status=Drop&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-danger text-white p-2">
                    <h6>Dropped</h6>
                    <h4><?= $sale_cancelled ?></h4>
                </div>
            </a>
        </div>

    </div>
</div>

<div class="card p-3 mb-4">
    <h5>Installation Status</h5>

    <div class="row text-center">

        <div class="col-6 col-md-3 mb-2">
            <a href="list-installation.php?status=Open&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-secondary text-white p-2">
                    <h6>Open</h6>
                    <h4><?= $inst_pending ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-installation.php?status=Inprogress&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-warning text-dark p-2">
                    <h6>In Progress</h6>
                    <h4><?= $inst_inprogress ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-installation.php?status=Completed&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-success text-white p-2">
                    <h6>Completed</h6>
                    <h4><?= $inst_completed ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-installation.php?status=Drop&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-danger text-white p-2">
                    <h6>Dropped</h6>
                    <h4><?= $inst_cancelled ?></h4>
                </div>
            </a>
        </div>

    </div>
</div>

<div class="card p-3 mb-4">
    <h5>Service Status</h5>

    <div class="row text-center">

        <div class="col-6 col-md-3 mb-2">
            <a href="list-service.php?status=Open&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-secondary text-white p-2">
                    <h6>Open</h6>
                    <h4><?= $service_pending ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-service.php?status=Inprogress&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-warning text-dark p-2">
                    <h6>In Progress</h6>
                    <h4><?= $service_inprogress ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-service.php?status=Completed&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-success text-white p-2">
                    <h6>Completed</h6>
                    <h4><?= $service_completed ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-service.php?status=Drop&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-danger text-white p-2">
                    <h6>Dropped</h6>
                    <h4><?= $service_cancelled ?></h4>
                </div>
            </a>
        </div>

    </div>
</div>

<div class="card p-3 mb-4">
    <h5>Complaint Status</h5>

    <div class="row text-center">

        <div class="col-6 col-md-3 mb-2">
            <a href="list-complaint.php?status=Open&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-primary text-white p-2">
                    <h6>Open</h6>
                    <h4><?= $comp_open ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-complaint.php?status=Inprogress&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-warning text-dark p-2">
                    <h6>In Progress</h6>
                    <h4><?= $comp_inprogress ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-complaint.php?status=Completed&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-success text-white p-2">
                    <h6>Completed</h6>
                    <h4><?= $comp_resolved ?></h4>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-2">
            <a href="list-complaint.php?status=Drop&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                <div class="card bg-danger text-white p-2">
                    <h6>Dropped</h6>
                    <h4><?= $comp_closed ?></h4>
                </div>
            </a>
        </div>

    </div>
</div>

<div class="card p-3 mb-4">
    <h5>Enquiry Engineer Performance</h5>

    <?php foreach ($engineerData as $eng): ?>
        <div class="mb-3 border rounded p-2">

            <h6 class="mb-2"><?= $eng['name'] ?></h6>

            <div class="row text-center">

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-enquiry.php?engineer=<?= urlencode($eng['name']) ?>&status=Open&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-primary text-white p-2">
                            <h6>Open</h6>
                            <h4><?= $eng['open_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-enquiry.php?engineer=<?= urlencode($eng['name']) ?>&status=Inprogress&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-warning text-dark p-2">
                            <h6>In Progress</h6>
                            <h4><?= $eng['inprogress_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-enquiry.php?engineer=<?= urlencode($eng['name']) ?>&status=Completed&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-success text-white p-2">
                            <h6>Completed</h6>
                            <h4><?= $eng['completed_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-enquiry.php?engineer=<?= urlencode($eng['name']) ?>&status=Drop&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-danger text-white p-2">
                            <h6>Dropped</h6>
                            <h4><?= $eng['drop_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    <?php endforeach; ?>

</div>

<div class="card p-3 mb-4">
    <h5>Sales Engineer Performance</h5>

    <?php foreach ($salesEngineerData as $eng): ?>
        <div class="mb-3 border rounded p-2">

            <h6 class="mb-2"><?= $eng['name'] ?></h6>

            <div class="row text-center">

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-sales.php?engineer=<?= $eng['id'] ?>&status=Open&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-primary text-white p-2">
                            <h6>Open</h6>
                            <h4><?= $eng['open_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-sales.php?engineer=<?= $eng['id'] ?>&status=Inprogress&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-warning text-dark p-2">
                            <h6>In Progress</h6>
                            <h4><?= $eng['inprogress_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-sales.php?engineer=<?= $eng['id'] ?>&status=Completed&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-success text-white p-2">
                            <h6>Completed</h6>
                            <h4><?= $eng['completed_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-sales.php?engineer=<?= $eng['id'] ?>&status=Drop&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-danger text-white p-2">
                            <h6>Dropped</h6>
                            <h4><?= $eng['drop_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    <?php endforeach; ?>

</div>

<div class="card p-3 mb-4">
    <h5>Installation Engineer Performance</h5>

    <?php foreach ($installationEngineerData as $eng): ?>
        <div class="mb-3 border rounded p-2">

            <h6 class="mb-2"><?= $eng['name'] ?></h6>

            <div class="row text-center">

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-installation.php?engineer=<?= $eng['id'] ?>&status=Open&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-primary text-white p-2">
                            <h6>Open</h6>
                            <h4><?= $eng['open_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-installation.php?engineer=<?= $eng['id'] ?>&status=Inprogress&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-warning text-dark p-2">
                            <h6>In Progress</h6>
                            <h4><?= $eng['inprogress_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-installation.php?engineer=<?= $eng['id'] ?>&status=Completed&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-success text-white p-2">
                            <h6>Completed</h6>
                            <h4><?= $eng['completed_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-installation.php?engineer=<?= $eng['id'] ?>&status=Drop&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-danger text-white p-2">
                            <h6>Dropped</h6>
                            <h4><?= $eng['drop_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    <?php endforeach; ?>
</div>

<div class="card p-3 mb-4">
    <h5>Service Engineer Performance</h5>

    <?php foreach ($serviceEngineerData as $eng): ?>
        <div class="mb-3 border rounded p-2">

            <h6 class="mb-2"><?= $eng['name'] ?></h6>

            <div class="row text-center">

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-service.php?engineer=<?= $eng['id'] ?>&status=Open&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-primary text-white p-2">
                            <h6>Open</h6>
                            <h4><?= $eng['open_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-service.php?engineer=<?= $eng['id'] ?>&status=Inprogress&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-warning text-dark p-2">
                            <h6>In Progress</h6>
                            <h4><?= $eng['inprogress_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-service.php?engineer=<?= $eng['id'] ?>&status=Completed&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-success text-white p-2">
                            <h6>Completed</h6>
                            <h4><?= $eng['completed_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-service.php?engineer=<?= $eng['id'] ?>&status=Drop&from=<?= $from ?>&to=<?= $to ?>" class="text-decoration-none">
                        <div class="card bg-danger text-white p-2">
                            <h6>Dropped</h6>
                            <h4><?= $eng['drop_count'] ?? 0 ?></h4>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    <?php endforeach; ?>

</div>

<div class="card p-3 mb-4">
    <h5>Complaint Engineer Performance</h5>

    <?php foreach ($complaintEngineerData as $eng): ?>
        <div class="mb-3 border rounded p-2">

            <h6 class="mb-2"><?= $eng['name'] ?></h6>

            <div class="row text-center">

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-complaint.php?engineer=<?= $eng['id'] ?>&status=Open&from=<?= $from ?>&to=<?= $to ?>">
                        <div class="card bg-primary text-white p-2">
                            <h6>Open</h6>
                            <h4><?= $eng['open_count'] ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-complaint.php?engineer=<?= $eng['id'] ?>&status=Inprogress&from=<?= $from ?>&to=<?= $to ?>">
                        <div class="card bg-warning text-dark p-2">
                            <h6>In Progress</h6>
                            <h4><?= $eng['inprogress_count'] ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-complaint.php?engineer=<?= $eng['id'] ?>&status=Completed&from=<?= $from ?>&to=<?= $to ?>">
                        <div class="card bg-success text-white p-2">
                            <h6>Completed</h6>
                            <h4><?= $eng['completed_count'] ?></h4>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <a href="list-complaint.php?engineer=<?= $eng['id'] ?>&status=Drop&from=<?= $from ?>&to=<?= $to ?>">
                        <div class="card bg-danger text-white p-2">
                            <h6>Dropped</h6>
                            <h4><?= $eng['drop_count'] ?></h4>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    <?php endforeach; ?>

</div>

    <!-- SALES CHART -->
    <div class="card p-3 mb-4">
        <h6>Sales Trend (Last 7 Days)</h6>
        <canvas id="salesChart"></canvas>
    </div>

    <!-- CONVERSION CHART -->
    <div class="card p-3">
        <h6>Enquiry vs Sales</h6>
        <canvas id="conversionChart"></canvas>
    </div>

</div>

<script>
const labels = <?= json_encode($labels) ?>;
const salesData = <?= json_encode($salesData) ?>;
const enquiryData = <?= json_encode($enquiryData) ?>;

// Sales Chart
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Sales',
            data: salesData,
            borderWidth: 2
        }]
    }
});

// Conversion Chart
new Chart(document.getElementById('conversionChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            { label: 'Enquiries', data: enquiryData },
            { label: 'Sales', data: salesData }
        ]
    }
});
</script>

</body>
</html>