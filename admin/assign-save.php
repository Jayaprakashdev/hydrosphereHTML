<?php
include "../config/db.php";

$service_id = $_POST['service_id'];
$worker_id  = $_POST['worker_id'];

$worker = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM workers WHERE id='$worker_id'"));

$worker_name = $worker['name'];
$worker_mobile = $worker['mobile'];

mysqli_query($conn,"UPDATE book_service SET worker='$worker_name', status='In Progress' WHERE id='$service_id'");

$service = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM book_service WHERE id='$service_id'"));

$customer = $service['customer_name'];
$mobile   = $service['mobile'];
$service_type = $service['service_type'];
$address = $service['address'];
$service_date = $service['service_date'];
$service_time = $service['service_time'];

$message = "New Service Assigned

Customer: $customer
Mobile: $mobile
Service: $service_type
Address: $address
Date: $service_date
Time: $service_time

Please contact customer.";

$whatsapp = "https://wa.me/91".$worker_mobile."?text=".urlencode($message);

header("Location: $whatsapp");
exit;
?>