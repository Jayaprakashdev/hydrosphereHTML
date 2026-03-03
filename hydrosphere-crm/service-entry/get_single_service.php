<?php
include '../config/db.php';

$id = $_POST['id'];

$query = mysqli_query($conn,"SELECT * FROM service_records WHERE id='$id'");
echo json_encode(mysqli_fetch_assoc($query));
?>