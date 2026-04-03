<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

$id = $_POST['id'] ?? '';
$customer_id = $_POST['customer_id'] ?? '';

$complaint_date = $_POST['complaint_date'] ?? '';
$product        = $_POST['product'] ?? '';
$description    = $_POST['description'] ?? '';

$total_amount   = $_POST['total_amount'] ?? 0;
$advance_amount = $_POST['advance_amount'] ?? 0;
$pending_amount = $_POST['pending_amount'] ?? 0;

$assigned_to    = $_POST['assigned_to'] ?? '';
$note           = $_POST['note'] ?? '';
$status         = $_POST['status'] ?? '';

// ✅ Validation
if(empty($customer_id) || empty($complaint_date) || empty($assigned_to) || empty($status)){
    die("Required fields missing!");
}

// ======================
// UPDATE
// ======================
if($id){

    $stmt = $conn->prepare("UPDATE complaints SET
        complaint_date=?,
        product=?,
        description=?,
        total_amount=?,
        advance_amount=?,
        pending_amount=?,
        assigned_to=?,
        note=?,
        status=?
        WHERE id=?");

    $stmt->bind_param("sssdddissi",
        $complaint_date,
        $product,
        $description,
        $total_amount,
        $advance_amount,
        $pending_amount,
        $assigned_to,
        $note,
        $status,
        $id
    );
}

// ======================
// INSERT
// ======================
else{

    $stmt = $conn->prepare("INSERT INTO complaints
        (customer_id, complaint_date, product, description,
         total_amount, advance_amount, pending_amount,
         assigned_to, note, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("isssdddiss",
        $customer_id,
        $complaint_date,
        $product,
        $description,
        $total_amount,
        $advance_amount,
        $pending_amount,
        $assigned_to,
        $note,
        $status
    );
}

$stmt->execute();

// Redirect
header("Location: customer-view.php?id=".$customer_id);
exit;