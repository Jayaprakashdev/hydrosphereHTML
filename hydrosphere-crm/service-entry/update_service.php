<?php
include '../config/db.php';

$id = $_POST['id'];
$total = $_POST['total_amount'];
$paid = $_POST['paid_amount'];
$status = $_POST['status'];

$pending = $total - $paid;

$sql = "UPDATE service_records SET 
total_amount='$total',
paid_amount='$paid',
pending_amount='$pending',
status='$status'
WHERE id='$id'";

if(mysqli_query($conn,$sql)){
    echo "Updated Successfully";
}else{
    echo "Error";
}
?>