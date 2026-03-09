<?php include 'config/db.php'; ?>

<?php

$open=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM tasks WHERE status='Open'"));

$progress=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM tasks WHERE status='Inprogress'"));

$complete=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM tasks WHERE status='Completed'"));

$technicians=mysqli_query($conn,"
SELECT 
assigned_to,
SUM(CASE WHEN status='Open' THEN 1 ELSE 0 END) as open_tasks,
SUM(CASE WHEN status='Inprogress' THEN 1 ELSE 0 END) as progress_tasks,
SUM(CASE WHEN status='Completed' THEN 1 ELSE 0 END) as completed_tasks
FROM tasks
GROUP BY assigned_to
");

?>

<!DOCTYPE html>
<html>

<head>

<title>Service Tracking System</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-3">

<h4 class="text-center">Service Tracking System</h4>

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

<div class="mt-4 d-grid gap-2">

<a href="add_task.php" class="btn btn-primary">Add New Task</a>

<a href="view_tasks.php" class="btn btn-dark">View Tasks</a>

</div>

<hr>

<h5 class="mt-3">Technician Performance</h5>

<?php while($tech=mysqli_fetch_assoc($technicians)){ ?>

<div class="card mb-2">

<div class="card-body">

<h6>👨‍🔧 <?php echo $tech['assigned_to']; ?></h6>

<div class="row text-center">

<div class="col-4">

<a href="view_tasks.php?engineer=<?php echo $tech['assigned_to']; ?>&status=Open">

<span class="badge bg-warning w-100">

Open <br>

<?php echo $tech['open_tasks']; ?>

</span>

</a>

</div>

<div class="col-4">

<a href="view_tasks.php?engineer=<?php echo $tech['assigned_to']; ?>&status=Inprogress">

<span class="badge bg-info w-100">

Inprogress <br>

<?php echo $tech['progress_tasks']; ?>

</span>

</a>

</div>

<div class="col-4">

<a href="view_tasks.php?engineer=<?php echo $tech['assigned_to']; ?>&status=Completed">

<span class="badge bg-success w-100">

Completed <br>

<?php echo $tech['completed_tasks']; ?>

</span>

</a>

</div>

</div>

</div>

</div>

<?php } ?>

</div>
<script src="pbb.js"></script>
</body>
</html>