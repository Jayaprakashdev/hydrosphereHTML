<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ✅ Get Data (safe fallback)
    $id               = $_POST['id'] ?? '';
    $customer_id      = $_POST['customer_id'] ?? '';

    $installation_date= $_POST['installation_date'] ?? '';
    $service_date     = $_POST['service_date'] ?? '';
    $product          = $_POST['product'] ?? '';
    $description      = $_POST['description'] ?? '';

    $total_amount     = $_POST['total_amount'] ?? 0;
    $advance_amount   = $_POST['advance_amount'] ?? 0;
    $pending_amount   = $_POST['pending_amount'] ?? 0;

    $assigned_to      = $_POST['assigned_to'] ?? '';
    $note             = $_POST['note'] ?? '';
    $status           = $_POST['status'] ?? '';

    // ✅ Validation
    if (empty($customer_id) || empty($service_date) || empty($assigned_to) || empty($status)) {
        die("Required fields missing!");
    }

    // =========================
    // ✅ UPDATE (EDIT)
    // =========================
    if ($id) {

        $stmt = $conn->prepare("UPDATE services SET
            installation_date=?,
            service_date=?,
            product=?,
            description=?,
            total_amount=?,
            advance_amount=?,
            pending_amount=?,
            assigned_to=?,
            note=?,
            status=?
            WHERE id=?");

        $stmt->bind_param("ssssdddissi",
            $installation_date,
            $service_date,
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

        $stmt->execute();
    }

    // =========================
    // ✅ INSERT (NEW)
    // =========================
    else {

        $stmt = $conn->prepare("INSERT INTO services 
        (customer_id, installation_date, service_date, product, description,
         total_amount, advance_amount, pending_amount,
         assigned_to, note, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("issssdddiss",
            $customer_id,
            $installation_date,
            $service_date,
            $product,
            $description,
            $total_amount,
            $advance_amount,
            $pending_amount,
            $assigned_to,
            $note,
            $status
        );

        $stmt->execute();

        // =========================
        // 🔥 AUTO NEXT SERVICE (120 days)
        // =========================
        if ($status == "completed") {

            $next_date = date('Y-m-d', strtotime($service_date . ' +120 days'));

            // ✅ Prevent duplicate next service
            $check = $conn->prepare("
                SELECT id FROM services 
                WHERE customer_id=? AND service_date=? AND status='open'
            ");
            $check->bind_param("is", $customer_id, $next_date);
            $check->execute();

            if ($check->get_result()->num_rows == 0) {

                $stmt2 = $conn->prepare("INSERT INTO services 
                (customer_id, installation_date, service_date, product, assigned_to, status)
                VALUES (?, ?, ?, ?, ?, 'open')");

                $stmt2->bind_param("isssi",
                    $customer_id,
                    $installation_date,
                    $next_date,
                    $product,
                    $assigned_to
                );

                $stmt2->execute();
            }
        }
    }

    // ✅ Redirect back
    header("Location: customer-view.php?id=" . $customer_id);
    exit;
}
?>