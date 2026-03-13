<?php
include "../config/db.php";

$alerts = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM book_service
WHERE status='Pending'
"));

$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM book_service"))['total'];

$open = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM book_service WHERE status='Pending'"))['total'];

$progress = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM book_service WHERE status='In Progress'"))['total'];

$completed = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM book_service WHERE status='Completed'"))['total'];

$result=mysqli_query($conn,"SELECT * FROM book_service ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Service Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-4">

<h3 class="mb-4">Hydrosphere Service Dashboard</h3>

<div class="row text-center mb-4">

<div class="col-6 col-md-3 mb-4">
<div class="card p-3 bg-primary text-white">
<h4><?php echo $total; ?></h4>
Total Requests
</div>
</div>

<div class="col-6 col-md-3 mb-4">
<div class="card p-3 bg-warning">
<h4><?php echo $open; ?></h4>
Open
</div>
</div>

<div class="col-6 col-md-3">
<div class="card p-3 bg-info text-white">
<h4><?php echo $progress; ?></h4>
In Progress
</div>
</div>

<div class="col-6 col-md-3">
<div class="card p-3 bg-success text-white">
<h4><?php echo $completed; ?></h4>
Completed
</div>
</div>

</div>

<h4 class="mt-5 mb-3">Worker Performance</h4>

<div class="row">

<?php

$workers=mysqli_query($conn,"
SELECT worker,
SUM(status='Pending') as open_jobs,
SUM(status='In Progress') as progress_jobs,
SUM(status='Completed') as completed_jobs
FROM book_service
WHERE worker IS NOT NULL AND worker!=''
GROUP BY worker
");

while($w=mysqli_fetch_assoc($workers)){
?>

<div class="col-md-3 col-12 mb-3">

<div class="card shadow-sm">

<div class="card-body">

<h6 class="text-center mb-3">
👨‍🔧 <?php echo $w['worker']; ?>
</h6>

<div class="row text-center">

<div class="col-4">

<a href="view_tasks.php?engineer=<?php echo urlencode($w['worker']); ?>&status=Pending">

<span class="badge bg-warning w-100 p-2">

Open <br>

<?php echo $w['open_jobs']; ?>

</span>

</a>

</div>

<div class="col-4">

<a href="view_tasks.php?engineer=<?php echo urlencode($w['worker']); ?>&status=In Progress">

<span class="badge bg-info w-100 p-2">

In Progress <br>

<?php echo $w['progress_jobs']; ?>

</span>

</a>

</div>

<div class="col-4">

<a href="view_tasks.php?engineer=<?php echo urlencode($w['worker']); ?>&status=Completed">

<span class="badge bg-success w-100 p-2">

Completed <br>

<?php echo $w['completed_jobs']; ?>

</span>

</a>

</div>

</div>

</div>

</div>

</div>

<?php } ?>

</div>

<h4>Service Requests</h4>

<div class="mb-3">

<input type="text" id="searchBox" class="form-control"
placeholder="🔍 Search Customer / Mobile / Not Assigned">

</div>

<div class="row">

<?php
mysqli_data_seek($result,0);
while($row=mysqli_fetch_assoc($result)){
?>

<div class="col-md-4 col-sm-6 mb-3 service-card">

<div class="card shadow-sm h-100 service-item"
data-name="<?php echo strtolower($row['customer_name']); ?>"
data-mobile="<?php echo $row['mobile']; ?>"
data-worker="<?php echo strtolower($row['worker'] ? $row['worker'] : 'not assigned'); ?>">

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

<?php
if($row['service_date'] == date('Y-m-d')){
echo '<span class="badge bg-success ms-2">Today</span>';
}
?>
</p>

<p class="mb-1">
⏰ <?php echo date("h:i A", strtotime($row['service_time'])); ?>
</p>

<p class="mb-2">
Status:

<?php if($row['status']=="Pending"){ ?>

<span class="badge bg-warning">Open</span>

<?php } elseif($row['status']=="In Progress"){ ?>

<span class="badge bg-info">In Progress</span>

<?php } else { ?>

<span class="badge bg-success">Completed</span>

<?php } ?>

</p>

<p class="mb-2">
👨‍🔧 <?php echo $row['worker'] ? $row['worker'] : "Not Assigned"; ?>
</p>

<div class="d-flex gap-2 mb-2">

<a class="btn btn-success btn-sm flex-fill"
href="tel:<?php echo $row['mobile']; ?>">
📞 Call
</a>

<a class="btn btn-success btn-sm flex-fill"
href="https://wa.me/91<?php echo $row['mobile']; ?>?text=Hello <?php echo urlencode($row['customer_name']); ?>, regarding your Hydrosphere <?php echo urlencode($row['service_type']); ?> service request."
target="_blank">
💬 WhatsApp
</a>

</div>

<a class="btn btn-primary btn-sm w-100"
href="assign-worker.php?id=<?php echo $row['id']; ?>">
Assign Worker
</a>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

<audio id="alertSound" src="../assets/sounds/notification.mp3" preload="auto"></audio>

<script>

let previousCount = <?php echo $alerts['total']; ?>;

setInterval(function(){

fetch("check_new_requests.php")
.then(response => response.text())
.then(data => {

let newCount = parseInt(data);

if(newCount > previousCount){

document.getElementById("alertSound").play();

alert("🔔 New Service Request Received!");

}

previousCount = newCount;

});

},10000);


document.getElementById("searchBox").addEventListener("keyup", function(){

let search = this.value.toLowerCase();

document.querySelectorAll(".service-item").forEach(function(card){

let name = card.dataset.name;
let mobile = card.dataset.mobile;
let worker = card.dataset.worker;

if(name.includes(search) || mobile.includes(search) || worker.includes(search)){

card.closest(".col-md-4").style.display="block";

}else{

card.closest(".col-md-4").style.display="none";

}

});

});

</script>
</body>
</html>