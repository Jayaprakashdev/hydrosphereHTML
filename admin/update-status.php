<?php
include "../config/db.php";

$id=$_GET['id'];

if(isset($_POST['status'])){

$status=$_POST['status'];

mysqli_query($conn,
"UPDATE book_service SET status='$status' WHERE id='$id'");

header("Location:worker-dashboard.php");
exit;

}

$service=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM book_service WHERE id='$id'"));
?>

<!DOCTYPE html>
<html>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Update Job Status</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-4">

<div class="card shadow-sm">

<div class="card-body">

<h5 class="mb-3">🔧 Update Job Status</h5>

<p class="mb-1"><b>Customer:</b> <?php echo $service['customer_name']; ?></p>

<p class="mb-1">📞 <?php echo $service['mobile']; ?></p>

<p class="mb-1">⚙ <?php echo $service['service_type']; ?></p>

<p class="mb-3">📅 <?php echo date("d M Y", strtotime($service['service_date'])); ?> | ⏰ <?php echo $service['service_time']; ?></p>

<form method="post">

<div class="mb-3">

<label class="form-label">Select Status</label>

<select name="status" class="form-select">

<option value="Pending" <?php if($service['status']=="Pending") echo "selected"; ?>>Pending</option>

<option value="In Progress" <?php if($service['status']=="In Progress") echo "selected"; ?>>In Progress</option>

<option value="Completed" <?php if($service['status']=="Completed") echo "selected"; ?>>Completed</option>

</select>

</div>

<button type="submit" class="btn btn-primary w-100">
Update Status
</button>

<a href="worker-dashboard.php" class="btn btn-secondary w-100 mt-2">
Back
</a>

</form>

</div>

</div>

</div>

</body>

</html>