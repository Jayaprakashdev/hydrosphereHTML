<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id       = $_POST['id'];
    $name     = $_POST['name'];
    $mobile   = $_POST['mobile'];
    $area     = $_POST['area'];
    $pincode  = $_POST['pincode'];
    $district = $_POST['district'];
    $state    = $_POST['state'];
    $country  = $_POST['country'];

    // ✅ Basic validation
    if(empty($name) || empty($mobile) || empty($area) || empty($pincode)){
        die("Required fields missing!");
    }

    // ✅ Duplicate Mobile Check (Exclude current ID)
    $check = $conn->prepare("SELECT id FROM customers WHERE mobile = ? AND id != ?");
    $check->bind_param("si", $mobile, $id);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        echo "<script>alert('Mobile number already exists!'); window.history.back();</script>";
        exit;
    }

    // ✅ Update query
    $stmt = $conn->prepare("
        UPDATE customers SET 
        name=?, mobile=?, area=?, pincode=?, district=?, state=?, country=?
        WHERE id=?
    ");

    $stmt->bind_param("sssssssi",
        $name,
        $mobile,
        $area,
        $pincode,
        $district,
        $state,
        $country,
        $id
    );

    if($stmt->execute()){
        echo "<script>alert('Customer Updated Successfully'); window.location.href='customers.php';</script>";
    } else {
        echo $stmt->error;
    }
}
?>