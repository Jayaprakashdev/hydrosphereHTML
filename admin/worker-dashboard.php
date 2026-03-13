<?php
session_start();
include "../config/db.php";

$worker=$_SESSION['worker_name'];

$result=mysqli_query($conn,"
SELECT * FROM book_service 
WHERE worker='$worker' 
ORDER BY service_date ASC
");
?>

<!DOCTYPE html>
<html>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Worker Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-3">

<h4 class="mb-3">👨‍🔧 My Service Jobs</h4>

<div class="row">

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="col-12 mb-3">

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

<p class="mb-2">

Status:

<?php if($row['status']=="Pending"){ ?>

<span class="badge bg-warning">Pending</span>

<?php } elseif($row['status']=="In Progress"){ ?>

<span class="badge bg-info">In Progress</span>

<?php } else { ?>

<span class="badge bg-success">Completed</span>

<?php } ?>

</p>

<div class="d-flex gap-2">

<a class="btn btn-success btn-sm flex-fill"
href="tel:<?php echo $row['mobile']; ?>">
📞 Call
</a>

<a class="btn btn-success btn-sm flex-fill"
href="https://wa.me/91<?php echo $row['mobile']; ?>?text=Hello <?php echo urlencode($row['customer_name']); ?>, I am from Hydrosphere service team regarding your <?php echo urlencode($row['service_type']); ?> request."
target="_blank">
💬 WhatsApp
</a>

</div>

<a class="btn btn-primary btn-sm w-100 mt-2"
href="update-status.php?id=<?php echo $row['id']; ?>">
Update Status
</a>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</body>

</html>