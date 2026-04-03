<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id              = $_POST['id'] ?? '';
    $customer_id     = $_POST['customer_id'];
    $sale_date       = $_POST['sale_date'];
    $product         = $_POST['product'];
    $description     = $_POST['description'];
    $total_amount    = $_POST['total_amount'];
    $advance_amount  = $_POST['advance_amount'] ?? 0;
    $pending_amount  = $_POST['pending_amount'] ?? 0;
    $assigned_to     = $_POST['assigned_to'];
    $note            = $_POST['note'];
    $status          = $_POST['status'];

    // =========================
    // UPDATE
    // =========================
    if (!empty($id)) {

        $stmt = $conn->prepare("
            UPDATE sales SET
                sale_date=?,
                product=?,
                description=?,
                total_amount=?,
                advance_amount=?,
                pending_amount=?,
                assigned_to=?,
                note=?,
                status=?
            WHERE id=?
        ");

        // ✅ FIXED (IMPORTANT)
        $stmt->bind_param(
            "sssdddissi",
            $sale_date,
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

        $msg = "Sale Updated Successfully";

    } else {

        // INSERT
        $stmt = $conn->prepare("
            INSERT INTO sales 
            (customer_id, sale_date, product, description, total_amount, advance_amount, pending_amount, assigned_to, note, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssdddiss",
            $customer_id,
            $sale_date,
            $product,
            $description,
            $total_amount,
            $advance_amount,
            $pending_amount,
            $assigned_to,
            $note,
            $status
        );

        $msg = "Sale Saved Successfully";
    }

    if ($stmt->execute()) {
        echo "<script>
            alert('$msg');
            window.location.href='customer-view.php?id=$customer_id';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>