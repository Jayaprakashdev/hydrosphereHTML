<?php 
include 'config/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = $_GET['id'] ?? 0;
if(!$id){ die("Invalid ID"); }

$result = mysqli_query($conn,"SELECT * FROM customers WHERE id='$id'");
$data = mysqli_fetch_assoc($result);
if(!$data){ die("Record Not Found"); }

if(isset($_POST['update'])){

$id = $_POST['id'];

$sql = "UPDATE customers SET
customer_type=?,
priority=?,
enquiry_source=?,
other_source=?,
customer_name=?,
mobile=?,
address=?,
requirement=?,
product_name=?,
service_done=?,
appointment_datetime=?,
price=?,
followed_by=?,
cancel_reason=?,
last_followup=?,
last_followed_by=?,
payment_mode=?,
status=?
WHERE id=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
"sssssssssssdssssssi",
$_POST['customer_type'],
$_POST['priority'],
$_POST['enquiry_source'],
$_POST['other_source'],
$_POST['customer_name'],
$_POST['mobile'],
$_POST['address'],
$_POST['requirement'],
$_POST['product_name'],
$_POST['service_done'],
$_POST['appointment_datetime'],
$_POST['price'],
$_POST['followed_by'],
$_POST['cancel_reason'],
$_POST['last_followup'],
$_POST['last_followed_by'],
$_POST['payment_mode'],
$_POST['status'],
$id
);

$stmt->execute();

header("Location: index.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Customer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
<div class="container mt-4">
<h3>✏ Edit Customer</h3>

<form method="POST" class="card p-4 shadow">
<div class="row">

<!-- Customer Type -->
<div class="col-md-4 mb-3">
<label>Customer Type</label>
<select name="customer_type" class="form-control">
<?php
$types = [
"Water Softener & Bathroom Water Softener",
"Industrial RO Plants",
"DMF & IRON Removal",
"Alkaline Ionizer",
"Alkaline Water Purifiers",
"Domestic RO",
"Commercial RO"
];
foreach($types as $type){
    $selected = ($data['customer_type']==$type) ? "selected" : "";
    echo "<option $selected>$type</option>";
}
?>
</select>
</div>

<!-- Priority -->
<div class="col-md-2 mb-3">
<label>Priority</label>
<select name="priority" class="form-control">
<?php
$priorities = ["High","Medium","Low"];
foreach($priorities as $p){
    $selected = ($data['priority']==$p) ? "selected" : "";
    echo "<option $selected>$p</option>";
}
?>
</select>
</div>

<!-- Enquiry Source -->
<div class="col-md-3 mb-3">
<label>Enquiry Source</label>
<select name="enquiry_source" class="form-control">
<?php
$sources = ["Showroom","Stall","Online","Call","Other"];
foreach($sources as $s){
    $selected = ($data['enquiry_source']==$s) ? "selected" : "";
    echo "<option $selected>$s</option>";
}
?>
</select>
</div>

<div class="col-md-3 mb-3">
<label>Other Source</label>
<input type="text" name="other_source" class="form-control" value="<?= $data['other_source'] ?>">
</div>

<!-- Basic Fields -->
<div class="col-md-4 mb-3">
<label>Customer Name</label>
<input type="text" name="customer_name" class="form-control" value="<?= $data['customer_name'] ?>">
</div>

<div class="col-md-4 mb-3">
<label>Mobile</label>
<input type="text" name="mobile" class="form-control" value="<?= $data['mobile'] ?>">
</div>

<div class="col-md-4 mb-3">
<label>Address</label>
<input type="text" name="address" class="form-control" value="<?= $data['address'] ?>">
</div>

<div class="col-md-6 mb-3">
<label>Requirement</label>
<input type="text" name="requirement" class="form-control" value="<?= $data['requirement'] ?>">
</div>

<div class="col-md-6 mb-3">
<label>Product Name</label>
<input type="text" name="product_name" class="form-control" value="<?= $data['product_name'] ?>">
</div>

<div class="col-md-6 mb-3">
<label>Service Done</label>
<input type="text" name="service_done" class="form-control" value="<?= $data['service_done'] ?>">
</div>

<div class="col-md-3 mb-3">
<label>Appointment</label>
<input type="datetime-local" name="appointment_datetime" class="form-control"
value="<?= $data['appointment_datetime'] ? date('Y-m-d\TH:i', strtotime($data['appointment_datetime'])) : '' ?>">
</div>

<div class="col-md-3 mb-3">
<label>Price</label>
<input type="number" name="price" class="form-control" value="<?= $data['price'] ?>">
</div>

<div class="col-md-3 mb-3">
<label>Followed By</label>
<select name="followed_by" class="form-control">
<?php
$persons = ["Dinesh","Karthick","Vicky","Jayaprakash"];
foreach($persons as $person){
    $selected = ($data['followed_by']==$person) ? "selected" : "";
    echo "<option $selected>$person</option>";
}
?>
</select>
</div>

<div class="col-md-3 mb-3">
<label>Payment Mode</label>
<select name="payment_mode" class="form-control">
<?php
$modes = ["PhonePe","Google Pay","Cash","UPI","Card","EMI","Still No"];
foreach($modes as $mode){
    $selected = ($data['payment_mode']==$mode) ? "selected" : "";
    echo "<option $selected>$mode</option>";
}
?>
</select>
</div>

<div class="col-md-6 mb-3">
<label>Cancel Reason</label>
<input type="text" name="cancel_reason" class="form-control" value="<?= $data['cancel_reason'] ?>">
</div>

<div class="col-md-3 mb-3">
<label>Last Followup</label>
<input type="datetime-local" name="last_followup" class="form-control"
value="<?= $data['last_followup'] ? date('Y-m-d\TH:i', strtotime($data['last_followup'])) : '' ?>">
</div>

<div class="col-md-3 mb-3">
<label>Last Followed By</label>
<select name="last_followed_by" class="form-control">
<?php
foreach($persons as $person){
    $selected = ($data['last_followed_by']==$person) ? "selected" : "";
    echo "<option $selected>$person</option>";
}
?>
</select>
</div>

<div class="col-md-3 mb-3">
<label>Status</label>
<select name="status" class="form-control">
<?php
$statuses = ["Open","Closed"];
foreach($statuses as $st){
    $selected = ($data['status']==$st) ? "selected" : "";
    echo "<option $selected>$st</option>";
}
?>
</select>
</div>

</div>

<button name="update" class="btn btn-success">Update</button>
<a href="index.php" class="btn btn-secondary">Back</a>

</form>
</div>
</body>
</html>