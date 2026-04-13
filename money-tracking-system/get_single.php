<?php
include 'db.php';

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM work_entries WHERE id='$id'");
echo json_encode($result->fetch_assoc());
?>