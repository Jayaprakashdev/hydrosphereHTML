<?php
include 'config/db.php';

if (!isset($_GET['id'])) {
    die("Customer ID missing!");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM customers WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Customer not found!");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-3 mb-5">

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="text-center mb-3">Edit Customer</h5>

            <form method="POST" action="update_customer.php">

                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                <div class="mb-2">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>" required>
                </div>

                <div class="mb-2">
                    <label>Mobile *</label>
                    <input type="text" name="mobile" class="form-control" value="<?= $row['mobile'] ?>" required>
                </div>

                <div class="mb-2">
                    <label>Area *</label>
                    <input type="text" name="area" class="form-control" value="<?= $row['area'] ?>" required>
                </div>

                <div class="mb-2">
                    <label>Pincode *</label>
                    <input type="text" name="pincode" class="form-control" value="<?= $row['pincode'] ?>" required>
                </div>

                <div class="mb-2">
                    <label>District</label>
                    <input type="text" name="district" class="form-control" value="<?= $row['district'] ?>">
                </div>

                <div class="mb-2">
                    <label>State</label>
                    <input type="text" name="state" class="form-control" value="<?= $row['state'] ?>">
                </div>

                <div class="mb-2">
                    <label>Country</label>
                    <input type="text" name="country" class="form-control" value="<?= $row['country'] ?>">
                </div>

                <button class="btn btn-success w-100">Update Customer</button>

            </form>

        </div>
    </div>

</div>

<script>
document.querySelector("form").addEventListener("submit", function(e) {
    let mobile = document.querySelector("input[name='mobile']").value;

    if(!/^[0-9]{10}$/.test(mobile)){
        alert("Enter valid 10-digit mobile number");
        e.preventDefault();
    }
});
</script>
</body>
</html>