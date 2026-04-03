<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- custom css -->
    <link href="./assets/css/custom-style.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container my-4">
        <div class="card shadow rounded-4">

            <div class="card-header bg-primary text-white text-center">
                <h5>Customer Profile</h5>
            </div>

            <div class="card-body">

                <form id="customerForm" action="save_customer.php" method="POST">

                    <!-- BASIC INFO -->
                    <h6 class="text-primary mb-3">Basic Info</h6>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Name *</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                            <div class="error" id="nameError"></div>
                        </div>

                        <div class="col-md-6">
                            <label>Mobile *</label>
                            <input type="tel" name="mobile" id="mobile" class="form-control" maxlength="10" required>
                            <div class="error" id="mobileError"></div>
                        </div>

                        <div class="col-md-6">
                            <label>Gender *</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Date of Marriage</label>
                            <input type="date" name="dom" class="form-control">
                        </div>

                    </div>

                    <!-- ADDRESS -->
                    <h6 class="text-primary mt-4 mb-3">Address</h6>
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label>Door No</label>
                            <input type="text" name="door_no" class="form-control">
                        </div>

                        <div class="col-md-8">
                            <label>Street Name</label>
                            <input type="text" name="street" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Area *</label>
                            <input type="text" name="area" id="area" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>District</label>
                            <input type="text" name="district" id="district" class="form-control" readonly>
                        </div>

                        <div class="col-md-4">
                            <label>State</label>
                            <input type="text" name="state" id="state" class="form-control" readonly>
                        </div>

                        <div class="col-md-4">
                            <label>Pincode *</label>
                            <input type="text" name="pincode" id="pincode" class="form-control" maxlength="6" required>
                            <div class="error" id="pincodeError"></div>
                        </div>

                        <div class="col-md-4">
                            <label>Country</label>
                            <input type="text" name="country" class="form-control" value="India">
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-4">Save Customer</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- js -->
    <script src="./assets/js/customer-profile.js"></script>
</body>

</html>