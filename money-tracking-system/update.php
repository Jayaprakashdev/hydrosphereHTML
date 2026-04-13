<?php
include 'db.php';

$id = $_POST['id'];
$date = $_POST['date'];
$type = $_POST['work_type'];
$desc = $_POST['description'];
$amount = $_POST['amount'];
$engineer = $_POST['engineer'];

$conn->query("UPDATE work_entries SET 
work_date='$date',
work_type='$type',
description='$desc',
amount='$amount',
engineer='$engineer'
WHERE id='$id'");
?>