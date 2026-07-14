<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "config/db.php";

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "status" => false,
        "message" => "No data received"
    ]);
    exit;
}

// Validate required fields
if (
    empty($data['task_date']) ||
    empty($data['task_name']) ||
    empty($data['description']) ||
    empty($data['task_income']) ||
    empty($data['task_expense']) ||
    empty($data['engineer']) ||
    empty($data['status'])
) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

// Assign values
$date = $data['task_date'];
$name = $data['task_name'];
$description = $data['description'];
$task_income = (float)$data['task_income'];
$task_expense = (float)$data['task_expense'];
$engineer = $data['engineer'];
$status = $data['status'];

// Prepare SQL
$sql = "INSERT INTO tasks
(task_date, task_name, description, task_income, task_expense, engineer, status)
VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

// Bind parameters
$stmt->bind_param(
    "sssddss",
    $date,
    $name,
    $description,
    $task_income,
    $task_expense,
    $engineer,
    $status
);

// Execute
if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Task Saved Successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to Save: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();

?>