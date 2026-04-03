<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id              = $_POST['id'] ?? '';
    $customer_id     = $_POST['customer_id'];
    $enquiry_date    = $_POST['enquiry_date'];
    $followup_date   = !empty($_POST['followup_date']) ? $_POST['followup_date'] : NULL;
    $appointment_date= !empty($_POST['appointment_date']) ? $_POST['appointment_date'] : NULL;
    $source          = $_POST['source'];
    $product         = $_POST['product'];
    $description     = $_POST['description'];
    $amount          = !empty($_POST['amount']) ? $_POST['amount'] : NULL;
    $assigned_to     = $_POST['assigned_to'];
    $note            = $_POST['note'];
    $status          = $_POST['status'];

    // ✅ Validation
    if (empty($customer_id) || empty($enquiry_date) || empty($source) || empty($product) || empty($assigned_to) || empty($status)) {
        die("Required fields missing!");
    }

    // =========================
    // ✅ UPDATE (EDIT MODE)
    // =========================
    if (!empty($id)) {

        $stmt = $conn->prepare("
            UPDATE enquiries SET
                enquiry_date=?,
                followup_date=?,
                appointment_date=?,
                source=?,
                product=?,
                description=?,
                amount=?,
                assigned_to=?,
                note=?,
                status=?
            WHERE id=?
        ");

        $stmt->bind_param("ssssssdissi",
            $enquiry_date,
            $followup_date,
            $appointment_date,
            $source,
            $product,
            $description,
            $amount,
            $assigned_to,
            $note,
            $status,
            $id
        );

        $msg = "Enquiry Updated Successfully";

    } else {

        // =========================
        // ✅ INSERT (NEW)
        // =========================
        $stmt = $conn->prepare("
            INSERT INTO enquiries 
            (customer_id, enquiry_date, followup_date, appointment_date, source, product, description, amount, assigned_to, note, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("issssssdsis",
            $customer_id,
            $enquiry_date,
            $followup_date,
            $appointment_date,
            $source,
            $product,
            $description,
            $amount,
            $assigned_to,
            $note,
            $status
        );

        $msg = "Enquiry Saved Successfully";
    }

    // Execute
    if ($stmt->execute()) {
        echo "<script>
            alert('$msg');
            window.location.href='customer-view.php?id=$customer_id';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>