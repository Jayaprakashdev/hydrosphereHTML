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

if (!$data || !isset($data['id'])) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid request"
    ]);
    exit;
}

$id = (int)$data["id"];
$status = "Completed";

// Prepare SQL
$stmt = $conn->prepare("UPDATE tasks SET status=? WHERE id=?");

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

// Bind parameters
$stmt->bind_param("si", $status, $id);

// Execute
if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Task Completed Successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();

?>