<?php

include 'config/db.php';

$date=$_POST['task_date'];
$name=$_POST['customer_name'];
$mobile=$_POST['mobile'];
$location=$_POST['location'];
$type=$_POST['task_type'];
$desc=$_POST['description'];
$amount=$_POST['amount'];
$assign=$_POST['assigned_to'];
$note=$_POST['note'];
$status=$_POST['status'];

mysqli_query($conn,"INSERT INTO tasks
(task_date,customer_name,mobile,location,task_type,description,amount,assigned_to,note,status)

VALUES
('$date','$name','$mobile','$location','$type','$desc','$amount','$assign','$note','$status')");

header("Location:add_task.php");

?>