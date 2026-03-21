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

/* ✅ DUPLICATE CHECK (BLOCK if active task exists) */
$check = mysqli_query($conn, "SELECT id FROM tasks WHERE mobile='$mobile' AND status!='Completed'");

if(mysqli_num_rows($check) > 0){
    echo "<script>
        alert('Customer already has an active task (Open/Inprogress)');
        window.location='add_task.php';
    </script>";
    exit();
}

/* INSERT QUERY */
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