<?php
include '../config/db.php';

$query = "SELECT * FROM service_records ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$data = [];

while($row = mysqli_fetch_assoc($result)){

    $status_badge = $row['status'] == "Closed"
    ? "<span class='badge bg-success'>Closed</span>"
    : "<span class='badge bg-danger'>Open</span>";

    $action = "
        <button class='btn btn-sm btn-primary editBtn' data-id='{$row['id']}'>Edit</button>
        <button class='btn btn-sm btn-danger deleteBtn ms-1 d-none' data-id='{$row['id']}'>Delete</button>
    ";

    $data[] = [
        $row['service_date'],
        $row['customer_name'],
        $row['customer_mobile'],
        $row['customer_address'],
        $row['service_done'],
        $row['attend_by'],
        $row['total_amount'],
        $row['paid_amount'],
        $row['pending_amount'],
        $row['pending_paid_datetime'],
        $row['payment_mode'],
        $row['payment_collected_by'],
        $status_badge,
        $row['status_notes'],
        $action   // 🔥 VERY IMPORTANT
    ];
}

echo json_encode(["data"=>$data]);
?>