<?php

include 'config/db.php';

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Customer ID missing!");
}

$id = $_GET['id'];

// Fetch customer
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Customer not found!");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer View</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/custom-style.css?v1" rel="stylesheet">
    <link href="assets/css/table.css" rel="stylesheet">
    <link href="assets/css/tab.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container my-3">

<a href="index.php" class="btn btn-primary btn-sm mb-2">Go to Dashboard</a>

    <!-- CUSTOMER DETAILS -->
    <div class="card shadow rounded-4 mb-3">
        <div class="card-header bg-success text-white text-center">
            <h5 class="mb-0">Customer Details</h5>
        </div>

        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4"><b>Name:</b><br><?= $row['name'] ?></div>
                <div class="col-md-4">
    <b>Mobile:</b><br>
    <?= $row['mobile'] ?><br>

    <!-- Call -->
    <a href="tel:<?= $row['mobile'] ?>" class="btn btn-sm btn-success mt-1">
        📞 Call
    </a>

    <!-- WhatsApp -->
    <a href="https://wa.me/91<?= $row['mobile'] ?>" target="_blank" class="btn btn-sm btn-success mt-1">
        💬 WhatsApp
    </a>
</div>
                <div class="col-md-4"><b>Area:</b><br><?= $row['area'] ?></div>
                <div class="col-md-4"><b>District:</b><br><?= $row['district'] ?></div>
                <div class="col-md-4"><b>State:</b><br><?= $row['state'] ?></div>
                <div class="col-md-4"><b>Pincode:</b><br><?= $row['pincode'] ?></div>
            </div>
        </div>
    </div>

    <!-- TABS -->
    <div class="card shadow rounded-4">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">
                <h6>Customer ID: <?= $row['id'] ?></h6>
                <!-- <a href="customer-edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit Customer</a> -->
            </div>

            <!-- Nav Tabs -->
            <ul class="nav nav-tabs jp-tab">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#enquiry">Enquiry</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#sales">Sales</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#installation">Installation</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#service">Service</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#complaint">Complaint</button></li>
            </ul>

            <div class="tab-content mt-3">

                <!-- ENQUIRY -->
                <div class="tab-pane fade show active" id="enquiry">
                    <?php
                    $enq = $conn->prepare("
                        SELECT e.*, se.name AS engineer_name, se.mobile AS engineer_mobile
                        FROM enquiries e
                        LEFT JOIN service_engineers se ON e.assigned_to = se.id
                        WHERE e.customer_id=? 
                        ORDER BY e.id DESC
                    ");
                    $enq->bind_param("i", $id);
                    $enq->execute();
                    $res = $enq->get_result();
                    ?>
                    <a href="enquiry-form.php?customer_id=<?= $id ?>" class="btn btn-primary btn-sm mb-2">+ Add Enquiry </a>
                    <div class="table-responsive">
                    <table class="table table-bordered table-sm table-white-space">
                        <tr><th>Date</th><th>Follow-up</th><th>Product</th><th>Status</th><th>Amount</th><th>Engineer</th><th>Action</th></tr>
                        <?php while($e = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= $e['enquiry_date'] ?></td>
                            <td style="color: <?= (strtotime($e['followup_date']) < time()) ? 'red' : 'green' ?>">
                                <?= $e['followup_date'] ?>
                            </td>
                            <td><?= $e['product'] ?></td>
                            <td><?= $e['status'] ?></td>
                            <td>₹<?= $e['amount'] ?? 0 ?></td>
                            <td>
                                <?= $e['engineer_name'] ?? 'Not Assigned' ?>

                                <?php if (!empty($e['engineer_mobile'])): ?>
                                    <a href="tel:<?= $e['engineer_mobile'] ?>" class="btn btn-sm btn-outline-success mt-1">
                                        📞
                                    </a>

                                    <a href="https://wa.me/91<?= $e['engineer_mobile'] ?>?text=<?= urlencode(
                                        "Hello " . $e['engineer_name'] . ",\n\n" .
                                        "You have a new Enquiry task assigned.\n\n" .
                                        "Date: " . $e['enquiry_date'] . "\n" .
                                        "Customer Name: " . $row['name'] . "\n" .
                                        "Mobile: " . $row['mobile'] . "\n" .
                                        "Product: " . $e['product'] . "\n" .
                                        "Work and Time: " . $e['description'] . "\n" .
                                        "Stage: " . $e['status'] . "\n\n" .
                                        "Please take action accordingly.\n\n- Hydrosphere CRM"
                                        ) ?>" 
                                        target="_blank" class="btn btn-sm btn-outline-success">
                                        💬
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="enquiry-form.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                    </div>
                </div>

                <!-- SALES -->
                <div class="tab-pane fade" id="sales">
                    <?php
                    $sales = $conn->prepare("
                        SELECT s.*, se.name AS engineer_name, se.mobile AS engineer_mobile
                        FROM sales s
                        LEFT JOIN service_engineers se ON s.assigned_to = se.id
                        WHERE s.customer_id=? 
                        ORDER BY s.id DESC
                    ");
                    $sales->bind_param("i", $id);
                    $sales->execute();
                    $res = $sales->get_result();
                    ?>
                    <a href="sales-form.php?customer_id=<?= $id ?>" class="btn btn-success btn-sm mb-2">+ Add Sales</a>
                   <div class="table-responsive">                 
                    <table class="table table-bordered table-sm table-white-space">
                        <tr><th>Date</th><th>Product</th><th>Total</th><th>Advance</th><th>Pending</th><th>Status</th><th>Engineer</th><th>Action</th></tr>
                        <?php while($s = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= $s['sale_date'] ?></td>
                            <td><?= $s['product'] ?></td>
                            <td>₹<?= $s['total_amount'] ?></td>
                            <td>₹<?= $s['advance_amount'] ?></td>
                            <td>₹<?= $s['pending_amount'] ?></td>
                            <td><?= $s['status'] ?></td>
                            <td>
                                <?= $s['engineer_name'] ?? 'Not Assigned' ?>

                                <?php if (!empty($s['engineer_mobile'])): ?>
                                    <a href="tel:<?= $s['engineer_mobile'] ?>" class="btn btn-sm btn-outline-success mt-1">
                                        📞
                                    </a>

                                    <a href="https://wa.me/91<?= $s['engineer_mobile'] ?>?text=<?= urlencode(
                                        "Hello " . $s['engineer_name'] . ",\n\n" .
                                        "You have a new Sale task assigned.\n\n" .
                                        "Date: " . $s['sale_date'] . "\n" .
                                        "Customer Name: " . $row['name'] . "\n" .
                                        "Mobile: " . $row['mobile'] . "\n" .
                                        "Product: " . $s['product'] . "\n" .
                                        "Work and Time: " . $s['description'] . "\n" .
                                        "Stage: " . $s['status'] . "\n\n" .
                                        "Please take action accordingly.\n\n- Hydrosphere CRM"
                                        ) ?>" 
                                        target="_blank" class="btn btn-sm btn-outline-success">
                                        💬
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="sales-form.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                    </div>
                </div>

                <!-- INSTALLATION -->
                <div class="tab-pane fade" id="installation">
                    <?php
                    $inst = $conn->prepare("
                        SELECT i.*, se.name AS engineer_name, se.mobile AS engineer_mobile
                        FROM installations i
                        LEFT JOIN service_engineers se ON i.assigned_to = se.id
                        WHERE i.customer_id=? 
                        ORDER BY i.id DESC
                    ");
                    $inst->bind_param("i", $id);
                    $inst->execute();
                    $res = $inst->get_result();
                    ?>
                    <a href="installation-form.php?customer_id=<?= $id ?>" class="btn btn-warning btn-sm mb-2">+ Add Installation</a>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-white-space">
                            <tr><th>Date</th><th>Product</th><th>Status</th><th>Engineer</th><th>Action</th></tr>
                            <?php while($i = $res->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i['installation_date'] ?></td>
                                <td><?= $i['product'] ?></td>
                                <td><?= $i['status'] ?></td>
                                <td>
                                    <?= $i['engineer_name'] ?? 'Not Assigned' ?>

                                    <?php if (!empty($i['engineer_mobile'])): ?>
                                        <a href="tel:<?= $i['engineer_mobile'] ?>" class="btn btn-sm btn-outline-success mt-1">
                                            📞
                                        </a>
                                        <a href="https://wa.me/91<?= $i['engineer_mobile'] ?>?text=<?= urlencode(
                                            "Hello " . $i['engineer_name'] . ",\n\n" .
                                            "You have a new Installation task assigned.\n\n" .
                                            "Date: " . $i['installation_date'] . "\n" .
                                            "Customer Name: " . $row['name'] . "\n" .
                                            "Mobile: " . $row['mobile'] . "\n" .
                                            "Product: " . $i['product'] . "\n" .
                                            "Work and Time: " . $i['description'] . "\n" .
                                            "Stage: " . $i['status'] . "\n\n" .
                                            "Please take action accordingly.\n\n- Hydrosphere CRM"
                                            ) ?>" 
                                            target="_blank" class="btn btn-sm btn-outline-success">
                                            💬
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="installation-form.php?id=<?= $i['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </table>
                    </div>
                </div>

                <!-- SERVICE -->
                <div class="tab-pane fade" id="service">
                    <?php
                    $srv = $conn->prepare("
                        SELECT s.*, se.name AS engineer_name, se.mobile AS engineer_mobile
                        FROM services s
                        LEFT JOIN service_engineers se ON s.assigned_to = se.id
                        WHERE s.customer_id=? 
                        ORDER BY s.id DESC
                    ");
                    $srv->bind_param("i", $id);
                    $srv->execute();
                    $res = $srv->get_result();
                    ?>
                    <a href="service-form.php?customer_id=<?= $id ?>" class="btn btn-info btn-sm mb-2">+ Add Service</a>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-white-space">
                            <tr><th>Date</th><th>Product</th><th>Total</th><th>Advance</th><th>Pending</th><th>Status</th><th>Engineer</th><th>Action</th></tr>
                            <?php while($s = $res->fetch_assoc()): ?>
                            <tr>
                                <td><?= $s['service_date'] ?></td>
                                <td><?= $s['product'] ?></td>
                                <td>₹<?= $s['total_amount'] ?></td>
                                <td>₹<?= $s['advance_amount'] ?></td>
                                <td>₹<?= $s['pending_amount'] ?></td>
                                <td><?= $s['status'] ?></td>
                                <td>
                                    <?= $s['engineer_name'] ?? 'Not Assigned' ?>

                                    <?php if (!empty($s['engineer_mobile'])): ?>
                                        <a href="tel:<?= $s['engineer_mobile'] ?>" class="btn btn-sm btn-outline-success mt-1">
                                            📞
                                        </a>

                                        <a href="https://wa.me/91<?= $s['engineer_mobile'] ?>?text=<?= urlencode(
                                            "Hello " . $s['engineer_name'] . ",\n\n" .
                                            "You have a new Service task assigned.\n\n" .
                                            "Date: " . $s['service_date'] . "\n" .
                                            "Customer Name: " . $row['name'] . "\n" .
                                            "Mobile: " . $row['mobile'] . "\n" .
                                            "Product: " . $s['product'] . "\n" .
                                            "Work and Time: " . $s['description'] . "\n" .
                                            "Stage: " . $s['status'] . "\n\n" .
                                            "Please take action accordingly.\n\n- Hydrosphere CRM"
                                            ) ?>" 
                                            target="_blank" class="btn btn-sm btn-outline-success">
                                            💬
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="service-form.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </table>
                    </div>  
                </div>

                <!-- COMPLAINT -->
                <div class="tab-pane fade" id="complaint">
    <?php
    $cmp = $conn->prepare("
        SELECT c.*, se.name AS engineer_name, se.mobile AS engineer_mobile
        FROM complaints c
        LEFT JOIN service_engineers se ON c.assigned_to = se.id
        WHERE c.customer_id=?
        ORDER BY c.id DESC
    ");

    $cmp->bind_param("i", $id);
    $cmp->execute();
    $res = $cmp->get_result();
    ?>

    <a href="complaint-form.php?customer_id=<?= $id ?>" class="btn btn-danger btn-sm mb-2">
        + Add Complaint
    </a>
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-white-space">
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Description</th>
                <th>Status</th>
                <th>Total</th>
                <th>Advance</th>
                <th>Pending</th>
                <th>Engineer</th>
                <th>Action</th>
            </tr>

            <?php while($c = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $c['complaint_date'] ?></td>
                <td><?= $c['product'] ?></td>
                <td><?= $c['description'] ?></td>
                <td><?= $c['status'] ?></td>

                <td>₹<?= $c['total_amount'] ?? 0 ?></td>
                <td>₹<?= $c['advance_amount'] ?? 0 ?></td>
                <td>₹<?= $c['pending_amount'] ?? 0 ?></td>

                <td>
                    <?= $c['engineer_name'] ?? 'Not Assigned' ?>

                    <?php if (!empty($c['engineer_mobile'])): ?>
                        <a href="tel:<?= $c['engineer_mobile'] ?>" class="btn btn-sm btn-outline-success mt-1">
                            📞
                        </a>

                        <a href="https://wa.me/91<?= $c['engineer_mobile'] ?>?text=<?= urlencode(
                            "Hello " . $c['engineer_name'] . ",\n\n" .
                            "You have a new Complaint task assigned.\n\n" .
                            "Date: " . $c['complaint_date'] . "\n" .
                            "Customer Name: " . $row['name'] . "\n" .
                            "Mobile: " . $row['mobile'] . "\n" .
                            "Product: " . $c['product'] . "\n" .
                            "Work and Time: " . $c['description'] . "\n" .
                            "Stage: " . $c['status'] . "\n\n" .
                            "Please take action accordingly.\n\n- Hydrosphere CRM"
                            ) ?>" 
                            target="_blank" class="btn btn-sm btn-outline-success">
                            💬
                        </a>
                    <?php endif; ?>
                </td>

                <td>
                    <a href="complaint-form.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">
                        Edit
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
   </div>
</div>

            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
        // ==========================
// Prevent browser back navigation
// ==========================
// (function() {
//     if (window.history && window.history.pushState) {
//         window.history.pushState('forward', null, window.location.href);
//         window.onpopstate = function() {
//             window.history.go(1); // forces forward if back button pressed
//         };
//     }
// })();
</script>
</body>
</html>