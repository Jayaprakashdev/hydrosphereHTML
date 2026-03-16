<?php include 'config/db.php'; ?>

<?php

$where="";

$today=date("Y-m-d");

/* FOLLOWUP FILTER */

if(isset($_GET['followup'])){

if($_GET['followup']=="overdue"){
$where="WHERE followup_date < '$today' AND status!='Completed'";
}

if($_GET['followup']=="today"){
$where="WHERE followup_date='$today' AND status!='Completed'";
}

if($_GET['followup']=="upcoming"){
$where="WHERE followup_date > '$today' AND status!='Completed'";
}

}

/* ENGINEER FILTER */

if(isset($_GET['engineer']) && isset($_GET['status'])){

$engineer=$_GET['engineer'];
$status=$_GET['status'];

$where="WHERE assigned_to='$engineer' AND status='$status'";

}

$query="SELECT * FROM tasks $where ORDER BY id DESC";

$result=mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>

<head>

<title>Sales Tasks</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script>

function searchTask() {

let search = document.getElementById("searchInput").value.toLowerCase();
let engineer = document.getElementById("engineerFilter").value.toLowerCase();

let cards = document.getElementsByClassName("task-card");

for (let i = 0; i < cards.length; i++) {

let text = cards[i].innerText.toLowerCase();

let showSearch = text.includes(search);
let showEngineer = engineer === "" || text.includes(engineer);

if (showSearch && showEngineer) {
cards[i].style.display = "";
} else {
cards[i].style.display = "none";
}

}

}

</script>

</head>

<body>

<div class="container mt-3">
<div class="mb-4 d-flex justify-content-between">
<h4 class="mb-0">Sales Tasks</h4>
<a href="index.php" class="btn btn-dark btn-sm">Dashboard</a>
</div>

<!-- SEARCH BOX -->

<input
type="text"
id="searchInput"
class="form-control mb-2"
placeholder="Search customer, mobile, location..."
onkeyup="searchTask()"
>

<!-- ENGINEER FILTER -->

<select
id="engineerFilter"
class="form-control mb-3"
onchange="searchTask()"
>

<option value="">All Engineers</option>
<option>Dinesh</option>
<option>Danvanth</option>
<option>Vicky</option>
<option>Karthik</option>
<option>Jayaprakash</option>

</select>

<?php

while($row=mysqli_fetch_assoc($result)){

$message = "Hello ".$row['customer_name'].", Hydrosphere service engineer ".$row['assigned_to']." will visit for ".$row['task_type']." today. Thank you.";

$encoded_message = urlencode($message);

$whatsapp_link = "https://wa.me/91".$row['mobile']."?text=".$encoded_message;

$call_link = "tel:".$row['mobile'];

$message="Hello ".$row['customer_name']."
Hydrosphere following up regarding your ".$row['task_type']." enquiry.
Please let us know a convenient time.";

$encoded_message=urlencode($message);

$whatsapp_link="https://wa.me/91".$row['mobile']."?text=".$encoded_message;

?>

<?php
$today = date("Y-m-d");
$isTodayFollowup = ($row['followup_date'] == $today);
?>

<div class="card mb-2 task-card">

<div class="card-body">
    

<h6>
<?php echo $row['customer_name']; ?>

<?php if($isTodayFollowup){ ?>
<span class="badge bg-danger">Today Followup</span>
<?php } ?>

</h6>
<p>

📅 Enquiry: <?php echo $row['task_date']; ?><br>

<?php if($row['followup_date']!=''){ ?>
🔔 Followup: <?php echo $row['followup_date']; ?><br>
<?php } ?>

<?php if($row['appointment_date']!=''){ ?>
📍 Appointment: <?php echo $row['appointment_date']; ?><br>
<?php } ?>

📞 <?php echo $row['mobile']; ?><br>

📍 <?php echo $row['location']; ?><br>

⚙ <?php echo $row['task_type']; ?><br>

👨‍🔧 <?php echo $row['assigned_to']; ?><br>

💰 ₹<?php echo $row['amount']; ?>

</p>
<a href="<?php echo $whatsapp_link; ?>" target="_blank" class="btn btn-success btn-sm">
💬 Send Followup
</a>

<span class="badge bg-primary">
<?php echo $row['status']; ?>
</span>

<div class="mt-2 d-flex gap-2 flex-wrap">

<a href="<?php echo $call_link; ?>" class="btn btn-primary btn-sm">
📞 Call
</a>

<a href="<?php echo $whatsapp_link; ?>" class="btn btn-success btn-sm" target="_blank">
💬 WhatsApp
</a>

<a href="edit_task.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
Edit
</a>

<a href="update_status.php?id=<?php echo $row['id']; ?>&status=Completed" class="btn btn-dark btn-sm">
Complete
</a>

</div>

</div>

</div>

<?php } ?>

</div>

<script src="pbb.js"></script>
</body>

</html>