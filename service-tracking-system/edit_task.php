<?php

include 'config/db.php';

$id=$_GET['id'];

$result=mysqli_query($conn,"SELECT * FROM tasks WHERE id='$id'");

$row=mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Task</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script>

function validateForm(){

let mobile=document.getElementById("mobile").value;

if(mobile.length != 10){
alert("Mobile number must be 10 digits");
return false;
}

return true;

}

function onlyNumber(event){
let charCode = event.which ? event.which : event.keyCode;

if(charCode>31 && (charCode<48 || charCode>57)){
return false;
}

return true;
}

</script>

</head>

<body>

<div class="container mt-3">

<h4>Edit Task</h4>

<form action="update_task.php" method="post" onsubmit="return validateForm()">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<input 
type="date"
name="task_date"
value="<?php echo $row['task_date']; ?>"
class="form-control mb-2"
required
>

<input 
type="text"
name="customer_name"
value="<?php echo $row['customer_name']; ?>"
class="form-control mb-2"
placeholder="Customer Name"
required
>

<input 
type="text"
id="mobile"
name="mobile"
value="<?php echo $row['mobile']; ?>"
class="form-control mb-2"
maxlength="10"
placeholder="Mobile"
required
onkeypress="return onlyNumber(event)"
>

<input 
type="text"
name="location"
value="<?php echo $row['location']; ?>"
class="form-control mb-2"
placeholder="Location"
required
>

<select name="task_type" class="form-control mb-2" required>

<option value="">Select Type</option>
<option <?php if($row['task_type']=="Installation") echo "selected"; ?>>Installation</option>
<option <?php if($row['task_type']=="Service") echo "selected"; ?>>Service</option>
<option <?php if($row['task_type']=="Complaint") echo "selected"; ?>>Complaint</option>

</select>

<textarea 
name="description"
class="form-control mb-2"
placeholder="Description"><?php echo $row['description']; ?></textarea>

<input 
type="number"
name="amount"
value="<?php echo $row['amount']; ?>"
class="form-control mb-2"
placeholder="Amount"
>

<select name="assigned_to" class="form-control mb-2" required>

<option value="">Select Service Engineer</option>

<option <?php if($row['assigned_to']=="Dinesh") echo "selected"; ?>>Dinesh</option>
<option <?php if($row['assigned_to']=="Danvanth") echo "selected"; ?>>Danvanth</option>
<option <?php if($row['assigned_to']=="Vicky") echo "selected"; ?>>Vicky</option>
<option <?php if($row['assigned_to']=="Karthik") echo "selected"; ?>>Karthik</option>
<option <?php if($row['assigned_to']=="Jayaprakash") echo "selected"; ?>>Jayaprakash</option>

</select>

<textarea 
name="note"
class="form-control mb-2"
placeholder="Note"><?php echo $row['note']; ?></textarea>

<select name="status" class="form-control mb-2" required>

<option value="">Select Status</option>

<option <?php if($row['status']=="Open") echo "selected"; ?>>Open</option>
<option <?php if($row['status']=="Inprogress") echo "selected"; ?>>Inprogress</option>
<option <?php if($row['status']=="Completed") echo "selected"; ?>>Completed</option>

</select>

<button class="btn btn-success w-100">Update Task</button>

</form>

</div>

</body>

</html>