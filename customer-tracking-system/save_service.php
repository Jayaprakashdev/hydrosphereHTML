<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ✅ Get Data
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
    if (!empty($id)) {

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

        if (!$stmt) {
            die("Prepare Error: " . $conn->error);
        }

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

        if (!$stmt->execute()) {
            die("Update Error: " . $stmt->error);
        }
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

        if (!$stmt) {
            die("Prepare Error: " . $conn->error);
        }

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

        if (!$stmt->execute()) {
            die("Insert Error: " . $stmt->error);
        }
    }

    // =========================
    // 🔥 AUTO NEXT SERVICE (120 days)
    // =========================
    if (!empty($service_date) && trim(strtolower($status)) === "completed") {

        $next_date = date('Y-m-d', strtotime($service_date . ' +120 days'));

        // ✅ Prevent duplicate
        $check = $conn->prepare("
            SELECT id FROM services 
            WHERE customer_id=? AND service_date=? 
        ");
        $check->bind_param("is", $customer_id, $next_date);
        $check->execute();

        if ($check->get_result()->num_rows == 0) {

            $stmt2 = $conn->prepare("INSERT INTO services 
            (customer_id, service_date, product, assigned_to, status, installation_date)
            VALUES (?, ?, ?, ?, 'open', ?)");

            if (!$stmt2) {
                die("Prepare Error (Auto): " . $conn->error);
            }

            // fallback if installation_date empty
            $install_date = !empty($installation_date) ? $installation_date : $service_date;

            $stmt2->bind_param("issis",
                $customer_id,
                $next_date,
                $product,
                $assigned_to,
                $install_date
            );

            if (!$stmt2->execute()) {
                die("Auto Insert Error: " . $stmt2->error);
            }
        }
    }

    // ✅ Redirect
    header("Location: customer-view.php?id=" . $customer_id);
    exit;
}
?>