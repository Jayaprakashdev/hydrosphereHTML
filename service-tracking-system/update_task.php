<?php

include 'config/db.php';

$id=$_POST['id'];
$date=$_POST['task_date'];
$name=$_POST['customer_name'];
$mobile=$_POST['mobile'];
$location=$_POST['location'];
$type=$_POST['task_type'];
$desc=$_POST['description'];
$amount=$_POST['amount'];
$engineer=$_POST['assigned_to'];
$note=$_POST['note'];
$status=$_POST['status'];

mysqli_query($conn,"UPDATE tasks SET

task_date='$date',
customer_name='$name',
mobile='$mobile',
location='$location',
task_type='$type',
description='$desc',
amount='$amount',
assigned_to='$engineer',
note='$note',
status='$status'

WHERE id='$id'");

header("Location:view_tasks.php");

?>