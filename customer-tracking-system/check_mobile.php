<?php
include 'config/db.php';

if (isset($_GET['mobile'])) {

    $mobile = $_GET['mobile'];

    $stmt = $conn->prepare("SELECT id FROM customers WHERE mobile = ?");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "exists";
    } else {
        echo "available";
    }

    $stmt->close();
    $conn->close();
}
?>