<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

$date = $_POST['task_date'] ?? '';
$followup = $_POST['followup_date'] ?? '';
$appointment = $_POST['appointment_date'] ?? '';
$name = $_POST['customer_name'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$location = $_POST['location'] ?? '';
$type = $_POST['task_type'] ?? '';
$desc = $_POST['description'] ?? '';
$amount = $_POST['amount'] ?? 0;
$assign = $_POST['assigned_to'] ?? '';
$note = $_POST['note'] ?? '';
$status = $_POST['status'] ?? '';

/* FIX EMPTY DATES */
$followup = ($followup == '') ? NULL : $followup;
$appointment = ($appointment == '') ? NULL : $appointment;

/* FIX AMOUNT */
if ($amount == '' || !is_numeric($amount)) {
    $amount = 0;
}

/* BUILD QUERY */

$sql = "INSERT INTO tasks
(task_date, followup_date, appointment_date, customer_name, mobile, location, task_type, description, amount, assigned_to, note, status)
VALUES
(
'$date',
".($followup ? "'$followup'" : "NULL").",
".($appointment ? "'$appointment'" : "NULL").",
'$name',
'$mobile',
'$location',
'$type',
'$desc',
'$amount',
'$assign',
'$note',
'$status'
)";

/* EXECUTE */

if(mysqli_query($conn,$sql)){
    header("Location: index.php");
    exit();
}else{
    echo "Error: " . mysqli_error($conn);
}

?>