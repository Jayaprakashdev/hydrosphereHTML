<?php
include 'db.php';

$id = $_POST['id'] ?? '';
$date = $_POST['date'] ?? '';
$type = $_POST['work_type'] ?? '';
$desc = $_POST['description'] ?? '';
$amount = $_POST['amount'] ?? '';
$expense = $_POST['expense'] ?? 0; // ✅ NEW
$engineer = $_POST['engineer'] ?? '';

// Validation
if(empty($id) || empty($date) || empty($type) || empty($desc) || empty($amount) || empty($engineer)){
    echo "All fields are required";
    exit;
}

// ✅ Use prepared statement (SAFE)
$stmt = $conn->prepare("UPDATE work_entries SET 
    work_date=?, 
    work_type=?, 
    description=?, 
    amount=?, 
    expense=?, 
    engineer=? 
WHERE id=?");

// sssddsi → (string, string, string, double, double, string, int)
$stmt->bind_param("sssddsi", $date, $type, $desc, $amount, $expense, $engineer, $id);

if($stmt->execute()){
    echo "Updated";
} else {
    echo "Error: " . $stmt->error;
}
?>