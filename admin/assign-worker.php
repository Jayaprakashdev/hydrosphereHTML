<?php
include "../config/db.php";

$id = $_GET['id'];

$workers = mysqli_query($conn,"SELECT * FROM workers");
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Assign Worker</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-4">

<h4>Assign Worker</h4>

<form action="assign-save.php" method="POST">

<input type="hidden" name="service_id" value="<?php echo $id; ?>">

<select name="worker_id" class="form-control mb-3">

<option value="">Select Worker</option>

<?php
while($w=mysqli_fetch_assoc($workers)){
?>

<option value="<?php echo $w['id']; ?>">
<?php echo $w['name']; ?>
</option>

<?php } ?>

</select>

<button class="btn btn-primary w-100">
Assign Worker
</button>

</form>

</div>

</body>
</html>