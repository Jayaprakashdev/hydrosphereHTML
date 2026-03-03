<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/db.php';

$service_date = $_POST['service_date'] ?? '';
$customer_name = $_POST['customer_name'] ?? '';
$customer_mobile = $_POST['customer_mobile'] ?? '';
$customer_address = $_POST['customer_address'] ?? '';
$service_done = $_POST['service_done'] ?? '';
$attend_by = $_POST['attend_by'] ?? '';

$total_amount = floatval($_POST['total_amount']);
$paid_amount = floatval($_POST['paid_amount']);
$pending_amount = floatval($_POST['pending_amount']);

$pending_paid_datetime = !empty($_POST['pending_paid_datetime']) 
    ? $_POST['pending_paid_datetime'] 
    : NULL;

$payment_mode = $_POST['payment_mode'] ?? '';
$payment_collected_by = $_POST['payment_collected_by'] ?? '';
$status = $_POST['status'] ?? 'Open';
$status_notes = $_POST['status_notes'] ?? '';

$id = $_POST['service_id'] ?? '';

if($id == ''){

    // INSERT
    $sql = "INSERT INTO service_records 
    (service_date, customer_name, customer_mobile, customer_address, service_done, attend_by, total_amount, paid_amount, pending_amount, pending_paid_datetime, payment_mode, payment_collected_by, status, status_notes)
    VALUES
    ('$service_date',
     '$customer_name',
     '$customer_mobile',
     '$customer_address',
     '$service_done',
     '$attend_by',
     $total_amount,
     $paid_amount,
     $pending_amount,
     " . ($pending_paid_datetime ? "'$pending_paid_datetime'" : "NULL") . ",
     '$payment_mode',
     '$payment_collected_by',
     '$status',
     '$status_notes')";

}else{

    // UPDATE
    $sql = "UPDATE service_records SET
    service_date='$service_date',
    customer_name='$customer_name',
    customer_mobile='$customer_mobile',
    customer_address='$customer_address',
    service_done='$service_done',
    attend_by='$attend_by',
    total_amount=$total_amount,
    paid_amount=$paid_amount,
    pending_amount=$pending_amount,
    pending_paid_datetime=" . ($pending_paid_datetime ? "'$pending_paid_datetime'" : "NULL") . ",
    payment_mode='$payment_mode',
    payment_collected_by='$payment_collected_by',
    status='$status',
    status_notes='$status_notes'
    WHERE id='$id'";
}

if(mysqli_query($conn,$sql)){
    echo "Saved Successfully";
}else{
    die("SQL ERROR: " . mysqli_error($conn));
}
?>