<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "config/db.php";

$sql = "SELECT * FROM tasks ORDER BY id DESC";

$result = $conn->query($sql);

$tasks = [];

while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

echo json_encode($tasks);

$conn->close();