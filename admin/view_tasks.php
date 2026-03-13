<?php
include "../config/db.php";

$worker = $_GET['engineer'];
$status = $_GET['status'];

$result = mysqli_query($conn,"
SELECT * FROM book_service
WHERE worker='$worker' AND status='$status'
ORDER BY service_date ASC
");
?>

<!DOCTYPE html>
<html>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Worker Tasks</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-4">

<h4 class="mb-3">
👨‍🔧 <?php echo $worker; ?> - <?php echo $status; ?> Jobs
</h4>

<div class="row">

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="col-md-4 col-sm-6 mb-3">

<div class="card shadow-sm">

<div class="card-body">

<h6 class="fw-bold">
<?php echo $row['customer_name']; ?>
</h6>

<p class="mb-1">
📞 <?php echo $row['mobile']; ?>
</p>

<p class="mb-1">
⚙ <?php echo $row['service_type']; ?>
</p>

<p class="mb-1">
📍 <?php echo $row['address']; ?>
</p>

<p class="mb-1">
📅 <?php echo date("d M Y", strtotime($row['service_date'])); ?>
</p>

<p class="mb-2">
⏰ <?php echo $row['service_time']; ?>
</p>

<div class="d-flex gap-2">

<a class="btn btn-success btn-sm flex-fill"
href="tel:<?php echo $row['mobile']; ?>">
📞 Call
</a>

<a class="btn btn-success btn-sm flex-fill"
href="https://wa.me/91<?php echo $row['mobile']; ?>?text=Hello <?php echo urlencode($row['customer_name']); ?> regarding your Hydrosphere service request."
target="_blank">
💬 WhatsApp
</a>

</div>

</div>

</div>

</div>

<?php } ?>

</div>

<a href="dashboard.php" class="btn btn-secondary mt-3">
← Back to Dashboard
</a>

</div>

</body>
</html>