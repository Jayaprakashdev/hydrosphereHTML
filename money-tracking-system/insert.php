<?php
include 'db.php';

$date = $_POST['date'] ?? '';
$type = $_POST['work_type'] ?? '';
$desc = $_POST['description'] ?? '';
$amount = $_POST['amount'] ?? '';
$engineer = $_POST['engineer'] ?? '';

// Validation
if(empty($date) || empty($type) || empty($desc) || empty($amount) || empty($engineer)){
    echo "All fields are required";
    exit;
}

// Prepare statement (SAFE)
$stmt = $conn->prepare("INSERT INTO work_entries 
(work_date, work_type, description, amount, engineer) 
VALUES (?, ?, ?, ?, ?)");

$stmt->bind_param("sssds", $date, $type, $desc, $amount, $engineer);

if($stmt->execute()){
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}
?>