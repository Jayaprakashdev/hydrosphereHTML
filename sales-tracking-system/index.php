<?php include 'config/db.php'; ?>

<?php

$today = date("Y-m-d");

/* STATUS COUNTS */

$open=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM tasks WHERE status='Open'"));
$progress=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM tasks WHERE status='Inprogress'"));
$complete=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM tasks WHERE status='Completed'"));

/* FOLLOWUP COUNTS */

$overdue=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM tasks 
WHERE followup_date < '$today' AND status!='Completed'
"));

$today_followup=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM tasks 
WHERE followup_date='$today' AND status!='Completed'
"));

$upcoming=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM tasks 
WHERE followup_date > '$today' AND status!='Completed'
"));

/* TECHNICIAN PERFORMANCE */

$technicians=mysqli_query($conn,"
SELECT 
assigned_to,
SUM(CASE WHEN status='Open' THEN 1 ELSE 0 END) as open_tasks,
SUM(CASE WHEN status='Inprogress' THEN 1 ELSE 0 END) as progress_tasks,
SUM(CASE WHEN status='Completed' THEN 1 ELSE 0 END) as completed_tasks,
SUM(CASE WHEN status='Cancel' THEN 1 ELSE 0 END) as cancel_tasks
FROM tasks
GROUP BY assigned_to
");

?>

<!DOCTYPE html>
<html>

<head>

<title>Sales Tracking System</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-3">

<h4 class="text-center">Sales Tracking System</h4>

<!-- STATUS DASHBOARD -->

<div class="row text-center mt-4">

<div class="col-4">
<div class="card p-2 bg-warning">
<h6>Open</h6>
<h4><?php echo $open['total']; ?></h4>
</div>
</div>

<div class="col-4">
<div class="card p-2 bg-info">
<h6>Inprogress</h6>
<h4><?php echo $progress['total']; ?></h4>
</div>
</div>

<div class="col-4">
<div class="card p-2 bg-success">
<h6>Completed</h6>
<h4><?php echo $complete['total']; ?></h4>
</div>
</div>

</div>

<!-- FOLLOWUP DASHBOARD -->

<h5 class="text-center mt-4">Followup Dashboard</h5>

<div class="row text-center mt-3">

<div class="col-4">
<a href="view_tasks.php?followup=overdue">
<div class="card p-2 bg-danger text-white">
<h6>Overdue</h6>
<h4><?php echo $overdue['total']; ?></h4>
</div>
</a>
</div>

<div class="col-4">
<a href="view_tasks.php?followup=today">
<div class="card p-2 bg-warning">
<h6>Today</h6>
<h4><?php echo $today_followup['total']; ?></h4>
</div>
</a>
</div>

<div class="col-4">
<a href="view_tasks.php?followup=upcoming">
<div class="card p-2 bg-success text-white">
<h6>Upcoming</h6>
<h4><?php echo $upcoming['total']; ?></h4>
</div>
</a>
</div>

</div>

<!-- MAIN ACTION BUTTONS -->

<div class="mt-4 d-grid gap-2">

<a href="add_task.php" class="btn btn-primary">Add New Task</a>

<a href="view_tasks.php" class="btn btn-dark">View Tasks</a>

</div>

<hr>

<!-- TECHNICIAN PERFORMANCE -->

<h5 class="mt-3">Technician Performance</h5>

<?php while($tech=mysqli_fetch_assoc($technicians)){ ?>

<div class="card mb-2">

<div class="card-body">

<h6>👨‍🔧 <?php echo $tech['assigned_to']; ?></h6>

<div class="row text-center">

<div class="col-3">
<a href="view_tasks.php?engineer=<?php echo $tech['assigned_to']; ?>&status=Open">
<span class="badge bg-warning w-100">
Open <br>
<?php echo $tech['open_tasks']; ?>
</span>
</a>
</div>

<div class="col-3">
<a href="view_tasks.php?engineer=<?php echo $tech['assigned_to']; ?>&status=Inprogress">
<span class="badge bg-info w-100">
Inprogress <br>
<?php echo $tech['progress_tasks']; ?>
</span>
</a>
</div>

<div class="col-3">
<a href="view_tasks.php?engineer=<?php echo $tech['assigned_to']; ?>&status=Completed">
<span class="badge bg-success w-100">
Completed <br>
<?php echo $tech['completed_tasks']; ?>
</span>
</a>
</div>

<div class="col-3">
<a href="view_tasks.php?engineer=<?php echo $tech['assigned_to']; ?>&status=Cancel">
<span class="badge bg-danger w-100">
Cancel <br>
<?php echo $tech['cancel_tasks']; ?>
</span>
</a>
</div>

</div>

</div>

</div>

<?php } ?>

</div>

</body>

</html>