<?php
include '../config/db.php';

$id = $_POST['id'];

$result = mysqli_query($conn,"SELECT * FROM service_records WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

echo json_encode($row);
?>