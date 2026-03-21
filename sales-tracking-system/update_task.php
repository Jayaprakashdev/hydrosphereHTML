<?php

include 'config/db.php';

$id = $_POST['id'] ?? '';

$date = $_POST['task_date'] ?? '';
$followup = $_POST['followup_date'] ?? '';
$appointment = $_POST['appointment_date'] ?? '';

$name = $_POST['customer_name'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$location = $_POST['location'] ?? '';
$type = $_POST['task_type'] ?? '';
$desc = $_POST['description'] ?? '';
$amount = $_POST['amount'] ?? 0;
$engineer = $_POST['assigned_to'] ?? '';
$note = $_POST['note'] ?? '';
$status = $_POST['status'] ?? '';

/* FIX EMPTY DATES */
$followup = ($followup == '') ? NULL : $followup;
$appointment = ($appointment == '') ? NULL : $appointment;

/* FIX AMOUNT */
if ($amount == '' || !is_numeric($amount)) {
    $amount = 0;
}

$sql = "UPDATE tasks SET

task_date='$date',
followup_date=" . ($followup ? "'$followup'" : "NULL") . ",
appointment_date=" . ($appointment ? "'$appointment'" : "NULL") . ",
customer_name='$name',
mobile='$mobile',
location='$location',
task_type='$type',
description='$desc',
amount='$amount',
assigned_to='$engineer',
note='$note',
status='$status'

WHERE id='$id'";

mysqli_query($conn,$sql);

header("Location:index.php");

?>