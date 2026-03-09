<!DOCTYPE html>
<html>

<head>

<title>Add Task</title>

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
let charCode=event.which ? event.which : event.keyCode;

if(charCode>31 && (charCode<48 || charCode>57)){
return false;
}

return true;
}

</script>

</head>

<body>

<div class="container mt-3">

<h4>Add Service Task</h4>

<form action="save_task.php" method="post" onsubmit="return validateForm()">

<input type="date" name="task_date" class="form-control mb-2" required>

<input type="text" name="customer_name" placeholder="Customer Name" class="form-control mb-2" required>

<input 
type="text"
id="mobile"
name="mobile"
placeholder="Mobile"
maxlength="10"
class="form-control mb-2"
required
onkeypress="return onlyNumber(event)"
>

<input type="text" name="location" placeholder="Location" class="form-control mb-2" required>

<select name="task_type" class="form-control mb-2" required>

<option value="">Select Type</option>
<option>Installation</option>
<option>Service</option>
<option>Complaint</option>

</select>

<textarea name="description" placeholder="Description" class="form-control mb-2"></textarea>

<input type="number" name="amount" placeholder="Amount" class="form-control mb-2">

<select name="assigned_to" class="form-control mb-2" required>

<option value="">Select Service Engineer</option>
<option>Dinesh</option>
<option>Danvanth</option>
<option>Vicky</option>
<option>Karthik</option>
<option>Jayaprakash</option>

</select>

<textarea name="note" placeholder="Note" class="form-control mb-2"></textarea>

<select name="status" class="form-control mb-2" required>

<option value="">Select Status</option>
<option>Open</option>
<option>Inprogress</option>
<option>Completed</option>

</select>

<button type="submit" class="btn btn-success w-100">Save Task</button>

</form>

</div>

</body>
</html>