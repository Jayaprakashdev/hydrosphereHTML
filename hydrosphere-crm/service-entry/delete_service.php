<?php
include '../config/db.php';

$id = $_POST['id'];

mysqli_query($conn,"DELETE FROM service_records WHERE id='$id'");

echo "Deleted Successfully";
?>