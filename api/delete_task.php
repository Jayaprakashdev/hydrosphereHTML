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

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid request"
    ]);
    exit;
}

$id = (int)$data["id"];

$stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Task Deleted Successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Delete Failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();

?>