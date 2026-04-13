<?php
include 'db.php';

$id = $_POST['id'];

$conn->query("DELETE FROM work_entries WHERE id='$id'");
?>