<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include "config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "status" => false,
        "message" => "No data received"
    ]);
    exit;
}

if (
    empty($data['task_date']) ||
    empty($data['task_name']) ||
    empty($data['description']) ||
    empty($data['engineer']) ||
    empty($data['status'])
) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

$date = $data['task_date'];
$name = $data['task_name'];
$description = $data['description'];
$task_income = $data['task_income'];
$task_expense = $data['task_expense'];
$engineer = $data['engineer'];
$status = $data['status'];

$sql = "INSERT INTO tasks
(task_date, task_name, description, task_income, task_expense, engineer, status)
VALUES
(?,?,?,?,?,?,?)

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $date, $name, $description, $engineer, $status);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Task Saved Successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to Save"
    ]);
}