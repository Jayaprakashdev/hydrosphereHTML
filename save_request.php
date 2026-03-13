<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$name    = $_POST['name'] ?? '';
$mobile  = $_POST['mobile'] ?? '';
$address = $_POST['address'] ?? '';
$service = $_POST['service'] ?? '';
$problem = $_POST['problem'] ?? '';
$date    = !empty($_POST['date']) ? $_POST['date'] : NULL;
$time    = $_POST['time'] ?? '';

$sql = "INSERT INTO book_service
(customer_name,mobile,address,service_type,problem,service_date,service_time)
VALUES
('$name','$mobile','$address','$service','$problem','$date','$time')";

if(mysqli_query($conn,$sql)){
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">

<link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
<title>Service Request Submitted</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="/assets/css/style.css">

</head>

<body class="d-flex flex-column min-vh-100">

<div id="header"></div>

<main class="flex-fill">

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow text-center p-4">

<h3 class="text-success">
<i class="fa-solid fa-circle-check"></i> Service Request Submitted
</h3>

<p class="mt-3">
Thank you <b><?php echo htmlspecialchars($name); ?></b>.  
Our Hydrosphere service team will contact you soon.
</p>

<a href="/" class="btn btn-primary mt-3">
Back to Home
</a>

</div>

</div>

</div>

</div>

</main>

<script>
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

history.pushState(null, null, location.href);

window.onpopstate = function () {
    history.go(1);
};
</script>
</body>
</html>

<?php
}else{

echo "Error: " . mysqli_error($conn);

}

}
?>