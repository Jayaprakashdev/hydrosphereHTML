<?php
session_start();
include "../config/db.php";

if(isset($_POST['login'])){

$username=$_POST['username'];
$password=$_POST['password'];

$result=mysqli_query($conn,"SELECT * FROM workers 
WHERE username='$username' AND password='$password'");

$row=mysqli_fetch_assoc($result);

if($row){

$_SESSION['worker_name']=$row['name'];

header("Location:worker-dashboard.php");
exit;

}else{

$error="Invalid Username or Password";

}

}
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Worker Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f6f9;
display:flex;
align-items:center;
justify-content:center;
height:100vh;
}

.login-card{
max-width:350px;
width:100%;
border-radius:12px;
}

</style>

</head>

<body>

<div class="card shadow login-card p-4">

<h4 class="text-center mb-3">👨‍🔧 Worker Login</h4>

<?php if(isset($error)){ ?>
<div class="alert alert-danger text-center p-2">
<?php echo $error; ?>
</div>
<?php } ?>

<form method="post" autocomplete="off">

<div class="mb-3">
<label class="form-label">Username</label>
<input type="text" name="username" class="form-control" autocomplete="off" required>
</div>

<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" autocomplete="new-password" required>
</div>

<button class="btn btn-primary w-100" name="login">
Login
</button>

</form>

</div>

</body>
</html>