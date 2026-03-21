<?php
include 'config/db.php';

$mobile = $_POST['mobile'];

// Check existing active tasks (NOT completed)
$check = mysqli_query($conn, "SELECT * FROM tasks WHERE mobile='$mobile' AND status!='Completed'");

if(mysqli_num_rows($check) > 0){
    echo "<script>alert('This customer already has an active task (Open/Inprogress)'); window.history.back();</script>";
    exit;
}

// Insert new task
$query = "INSERT INTO tasks (task_date, customer_name, mobile, location, task_type, description, amount, assigned_to, note, status)
VALUES (
'".$_POST['task_date']."',
'".$_POST['customer_name']."',
'".$_POST['mobile']."',
'".$_POST['location']."',
'".$_POST['task_type']."',
'".$_POST['description']."',
'".$_POST['amount']."',
'".$_POST['assigned_to']."',
'".$_POST['note']."',
'".$_POST['status']."'
)";

mysqli_query($conn, $query);

echo "<script>alert('Task Added Successfully'); window.location='index.php';</script>";
?>