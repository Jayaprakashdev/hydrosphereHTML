<?php include 'config/db.php'; ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
// CREATE
if(isset($_POST['submit'])){

$customer_type = $_POST['customer_type'];
$priority = $_POST['priority'];
$enquiry_source = $_POST['enquiry_source'];
$other_source = $_POST['other_source'];
$customer_name = $_POST['customer_name'];
$mobile = $_POST['mobile'];
$address = $_POST['address'];
$requirement = $_POST['requirement'];
$product_name = $_POST['product_name'];
$service_done = $_POST['service_done'];

$enquiry_data_and_time = !empty($_POST['enquiry_data_and_time']) 
    ? date("Y-m-d H:i:s", strtotime($_POST['enquiry_data_and_time'])) 
    : NULL;

$appointment_datetime = !empty($_POST['appointment_datetime']) 
    ? date("Y-m-d H:i:s", strtotime($_POST['appointment_datetime'])) 
    : NULL;

$price = $_POST['price'];
$followed_by = $_POST['followed_by'];
$cancel_reason = $_POST['cancel_reason'];

$last_followup = !empty($_POST['last_followup']) 
    ? date("Y-m-d H:i:s", strtotime($_POST['last_followup'])) 
    : NULL;

$last_followed_by = $_POST['last_followed_by'];
$payment_mode = $_POST['payment_mode'];
$status = $_POST['status'];

$sql = "INSERT INTO customers
(customer_type,priority,enquiry_source,other_source,enquiry_data_and_time,customer_name,mobile,address,requirement,product_name,service_done,appointment_datetime,price,followed_by,cancel_reason,last_followup,last_followed_by,payment_mode,status)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
"ssssssssssssdssssss",
$customer_type,
$priority,
$enquiry_source,
$other_source,
$enquiry_data_and_time,
$customer_name,
$mobile,
$address,
$requirement,
$product_name,
$service_done,
$appointment_datetime,
$price,
$followed_by,
$cancel_reason,
$last_followup,
$last_followed_by,
$payment_mode,
$status
);

$stmt->execute();

header("Location: index.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hydrosphere Task Manager</title>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Buttons Extension CSS (Export) -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link href="assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
<div class="container mt-4">

<h3>💧 Hydrosphere CRM</h3>

<form method="POST" class="card p-3 shadow">

<div class="row">

<!-- Customer Type -->
<div class="col-md-4 mb-3">
<label>Customer Type</label>
<select name="customer_type" class="form-control">
<option>Water Softener & Bathroom Water Softener</option>
<option>Industrial RO Plants</option>
<option>DMF & IRON Removal</option>
<option>Alkaline Ionizer</option>
<option>Alkaline Water Purifiers</option>
<option>Domestic RO</option>
<option>Commercial RO</option>
</select>
</div>

<!-- Priority -->
<div class="col-md-2 mb-3">
<label>Priority</label>
<select name="priority" class="form-control">
<option>High</option>
<option>Medium</option>
<option>Low</option>
</select>
</div>

<!-- Enquiry Source -->
<div class="col-md-3 mb-3">
<label>Enquiry Source</label>
<select name="enquiry_source" id="enquiry_source" class="form-control">
<option>Showroom</option>
<option>Stall</option>
<option>Online</option>
<option>Call</option>
<option>Other</option>
</select>
</div>

<div class="col-md-3 mb-3" id="other_box">
<label>Other Source</label>
<input type="text" name="other_source" class="form-control">
</div>

<div class="col-md-3 mb-3">
<label>Enquiry Date and Time</label>
<input type="datetime-local" name="enquiry_data_and_time" class="form-control">
</div>

<!-- Basic Info -->
<div class="col-md-4 mb-3">
<label>Customer Name</label>
<input type="text" name="customer_name" class="form-control">
</div>

<div class="col-md-4 mb-3">
<label>Mobile</label>
<input type="text" name="mobile" class="form-control">
</div>

<div class="col-md-4 mb-3">
<label>Address</label>
<input type="text" name="address" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Requirement</label>
<input type="text" name="requirement" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Product Name</label>
<input type="text" name="product_name" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Instalation Status</label>
<input type="text" name="service_done" class="form-control">
</div>

<div class="col-md-3 mb-3">
<label>Appointment Date & Time</label>
<input type="datetime-local" name="appointment_datetime" class="form-control">
</div>

<div class="col-md-3 mb-3">
<label>Price</label>
<input type="number" name="price" class="form-control">
</div>

<div class="col-md-3 mb-3">
<label>Followed By</label>
<select name="followed_by" class="form-control">
<option>select follow by<option>     
<option>Dinesh</option>
<option>Karthick</option>
<option>Vicky</option>
<option>Thanvath</option>
<option>Jayaprakash</option>
</select>
</div>

<div class="col-md-3 mb-3">
<label>Payment Mode</label>
<select name="payment_mode" class="form-control">
<option>PhonePe</option>
<option>Google Pay</option>
<option>Cash</option>
<option>UPI</option>
<option>Card</option>
<option>EMI</option>
<option>Still No</option>
</select>
</div>

<div class="col-md-6 mb-3">
<label>Cancel Reason</label>
<input type="text" name="cancel_reason" class="form-control">
</div>

<div class="col-md-3 mb-3">
<label>Last Followup</label>
<input type="datetime-local" name="last_followup" class="form-control">
</div>

<div class="col-md-3 mb-3">
<label>Last Followed By</label>
<select name="last_followed_by" class="form-control">
<option>Select Last follow by<option>    
<option>Dinesh</option>
<option>Karthick</option>
<option>Vicky</option>
<option>Thanvath</option>
<option>Jayaprakash</option>
</select>
</div>

<div class="col-md-3 mb-3">
<label>Status</label>
<select name="status" class="form-control">
<option>Open</option>
<option>Closed</option>
<option>Inprocess</option>
</select>
</div>

</div>

<button name="submit" class="btn btn-primary">Save</button>
</form>

<hr>

<!-- FILTER -->
<div class="row mb-3">

<div class="col-md-3">
<input type="text" id="search_mobile" placeholder="Search Mobile" class="form-control">
</div>

<div class="col-md-3">
<input type="text" id="search_name" placeholder="Search Customer Name" class="form-control">
</div>

<div class="col-md-3">
<select id="search_type" class="form-control">
<option value="">All Types</option>
<option>Water Softener & Bathroom Water Softener</option>
<option>Industrial RO Plants</option>
<option>DMF & IRON Removal</option>
<option>Alkaline Ionizer</option>
<option>Alkaline Water Purifiers</option>
<option>Domestic RO</option>
<option>Commercial RO</option>
</select>
</div>

<div class="col-md-3">
<select id="search_status" class="form-control">
<option value="">All Status</option>
<option>Open</option>
<option>Closed</option>
<option>Inprocess</option>
</select>
</div>

<div class="col-md-3 mb-3">
<select id="search_followed_by" class="form-control">
<option value="">All</option>
<option value="Dinesh">Dinesh</option>
<option value="Karthick">Karthick</option>
<option value="Vicky">Vicky</option>
<option value="Thanvath">Thanvath</option>
<option value="Jayaprakash">Jayaprakash</option>
</select>
</div>

</div>

<br>

<!-- TABLE -->
<div class="table-responsive">
<table id="customerTable" class="table table-bordered table-striped table-sm bg-white">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Customer Type</th>
<th>Priority</th>
<th>Enquiry Source</th>
<th>Other Source</th>
<th>Enquiry Date</th>
<th>Customer Name</th>
<th>Mobile</th>
<th>Address</th>
<th>Requirement</th>
<th>Product</th>
<th>Service Done</th>
<th>Appointment</th>
<th>Price</th>
<th>Followed By</th>
<th>Cancel Reason</th>
<th>Last Followup</th>
<th>Last Followed By</th>
<th>Payment Mode</th>
<th>Status</th>
<th>Created</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$result = mysqli_query($conn,"SELECT * FROM customers ORDER BY id DESC");

while($row = mysqli_fetch_assoc($result)){
?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['customer_type'] ?></td>
<td><?= $row['priority'] ?></td>
<td><?= $row['enquiry_source'] ?></td>
<td><?= $row['other_source'] ?></td>
<td><?= $row['enquiry_data_and_time'] ?></td>
<td><?= htmlspecialchars($row['customer_name']) ?></td>
<td><?= $row['mobile'] ?></td>
<td><?= $row['address'] ?></td>
<td><?= $row['requirement'] ?></td>
<td><?= $row['product_name'] ?></td>
<td><?= $row['service_done'] ?></td>
<td><?= $row['appointment_datetime'] ?></td>
<td>₹<?= $row['price'] ?></td>
<td><?= $row['followed_by'] ?></td>
<td><?= $row['cancel_reason'] ?></td>
<td><?= $row['last_followup'] ?></td>
<td><?= $row['last_followed_by'] ?></td>
<td><?= $row['payment_mode'] ?></td>
<td>
<?php
if($row['status']=="Open"){
    echo "<span class='badge bg-primary'>Open</span>";
}elseif($row['status']=="Closed"){
    echo "<span class='badge bg-success'>Closed</span>";
}else{
    echo "<span class='badge bg-warning text-dark'>Inprocess</span>";
}
?>
</td>
<td><?= $row['created_at'] ?></td>

<td>
<a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm d-none"
onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>

<?php } ?>

</tbody>
</table>
</div>

</div>

<script>
$(document).ready(function(){

    var table = $('#customerTable').DataTable({
        pageLength: 10,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy',
            'excel',
            'print'
        ]
    });

    // Custom Filters (Optional)
    $("#search_mobile").on("keyup", function(){
        table.column(7).search(this.value).draw();
    });

    $("#search_name").on("keyup", function(){
        table.column(6).search(this.value).draw();
    });

    $("#search_type").on("change", function(){
        table.column(1).search(this.value).draw();
    });

    $("#search_status").on("change", function(){
        table.column(19).search(this.value).draw();
    });

    $("#search_followed_by").on("change", function(){
        table.column(14).search(this.value).draw();
    });

});
</script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Export Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Required for Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

</body>
</html>