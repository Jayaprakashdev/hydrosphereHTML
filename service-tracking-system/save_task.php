<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

$date = $_POST['task_date'] ?? '';
$name = $_POST['customer_name'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$location = $_POST['location'] ?? '';
$type = $_POST['task_type'] ?? '';
$desc = $_POST['description'] ?? '';
$amount = $_POST['amount'] ?? 0;
$assign = $_POST['assigned_to'] ?? '';
$note = $_POST['note'] ?? '';
$status = $_POST['status'] ?? '';

// Fix for empty amount
if ($amount == '' || !is_numeric($amount)) {
    $amount = 0;
}

$sql = "INSERT INTO tasks
(task_date,customer_name,mobile,location,task_type,description,amount,assigned_to,note,status)
VALUES
('$date','$name','$mobile','$location','$type','$desc','$amount','$assign','$note','$status')";

if(mysqli_query($conn,$sql)){
    header("Location: index.php");
    exit();
}else{
    echo "Error: " . mysqli_error($conn);
}
?>