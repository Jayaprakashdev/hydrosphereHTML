<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $name     = $_POST['name'];
    $mobile   = $_POST['mobile'];
    $gender   = $_POST['gender'];
    $dob      = !empty($_POST['dob']) ? $_POST['dob'] : NULL;
    $dom      = !empty($_POST['dom']) ? $_POST['dom'] : NULL;
    $door_no  = $_POST['door_no'];
    $street   = $_POST['street'];
    $area     = $_POST['area'];
    $district = $_POST['district'];
    $state    = $_POST['state'];
    $pincode  = $_POST['pincode'];
    $country  = $_POST['country'];

    // Validation
    if (empty($name) || empty($mobile) || empty($area) || empty($pincode)) {
        echo "Required fields missing!";
        exit;
    }

    // 🔴 DUPLICATE CHECK START HERE
    $check = $conn->prepare("SELECT id FROM customers WHERE mobile = ?");
    $check->bind_param("s", $mobile);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Mobile number already exists!'); window.history.back();</script>";
        exit;
    }
    // 🔴 DUPLICATE CHECK END HERE

    // Prepare query
    $stmt = $conn->prepare("INSERT INTO customers 
    (name, mobile, gender, dob, dom, door_no, street, area, district, state, pincode, country) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssssss",
        $name,
        $mobile,
        $gender,
        $dob,
        $dom,
        $door_no,
        $street,
        $area,
        $district,
        $state,
        $pincode,
        $country
    );

    // Execute
    if ($stmt->execute()) {
        $last_id = $conn->insert_id;

        echo "<script>
        alert('Customer Saved Successfully');
        window.location.href='customer-view.php?id=$last_id';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>