<?php
include 'config/db.php';

$id = $_POST['id'] ?? '';
$customer_id = $_POST['customer_id'];

$installation_date = $_POST['installation_date'];
$product = $_POST['product'];
$description = $_POST['description'];
$assigned_to = $_POST['assigned_to'];
$note = $_POST['note'];
$status = $_POST['status'];

// UPDATE
if ($id) {
    $stmt = $conn->prepare("UPDATE installations SET 
        installation_date=?, product=?, description=?, assigned_to=?, note=?, status=? 
        WHERE id=?");

    $stmt->bind_param("sssissi", 
        $installation_date, $product, $description, $assigned_to, $note, $status, $id
    );
}
// INSERT
else {
    $stmt = $conn->prepare("INSERT INTO installations 
        (customer_id, installation_date, product, description, assigned_to, note, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("isssiss", 
        $customer_id, $installation_date, $product, $description, $assigned_to, $note, $status
    );
}

$stmt->execute();

// Redirect back
header("Location: customer-view.php?id=" . $customer_id);
exit;