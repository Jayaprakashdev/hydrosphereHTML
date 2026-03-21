<?php

include 'config/db.php';

$id=$_GET['id'];
$status=$_GET['status'];

mysqli_query($conn,"UPDATE tasks SET status='$status' WHERE id='$id'");

header("Location:index.php");

?>