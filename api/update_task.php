<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "status" => false,
        "message" => "No data received"
    ]);
    exit;
}

$id = (int)$data["id"];
$date = $data["task_date"];
$name = $data["task_name"];
$description = $data["description"];
$task_income = (float)$data["task_income"];
$task_expense = (float)$data["task_expense"];
$engineer = $data["engineer"];
$status = $data["status"];

$sql = "UPDATE tasks SET
task_date=?,
task_name=?,
description=?,
task_income=?,
task_expense=?,
engineer=?,
status=?
WHERE id=?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param(
    "sssddssi",
    $date,
    $name,
    $description,
    $task_income,
    $task_expense,
    $engineer,
    $status,
    $id
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Task Updated Successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Update Failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();

?>