<?php
include 'config/db.php';

// ✅ Edit Mode
$id = $_GET['id'] ?? '';
$data = [];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM complaints WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
}

// Customer ID
$customer_id = $data['customer_id'] ?? ($_GET['customer_id'] ?? '');
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $id ? 'Edit Complaint' : 'New Complaint' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-3 mb-5">
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="text-center mb-3">
                <?= $id ? 'Edit Complaint' : 'New Complaint' ?>
            </h5>

            <form method="POST" action="save_complaint.php">

                <!-- Hidden -->
                <input type="hidden" name="id" value="<?= $data['id'] ?? '' ?>">
                <input type="hidden" name="customer_id" value="<?= $customer_id ?>">

                <?php if(empty($customer_id)): ?>
                    <div class="alert alert-danger text-center">
                        Customer not selected!
                    </div>
                <?php endif; ?>

                <!-- Date -->
                <div class="mb-2">
                    <label>Complaint Date *</label>
                    <input type="date" name="complaint_date"
                           value="<?= $data['complaint_date'] ?? '' ?>"
                           class="form-control" required>
                </div>

                <!-- Product -->
                <div class="mb-2">
                    <label>Product *</label>
                    <select name="product" class="form-control" required>
                        <option value="">Select Product</option>

                        <?php
                        $products = [
                            "Water Softener",
                            "Industrial RO Plants",
                            "DMF & IRON Removal",
                            "Alkaline Ionizer",
                            "Alkaline Water Purifiers",
                            "Domestic RO",
                            "Commercial RO"
                        ];

                        foreach($products as $p){
                            $selected = (($data['product'] ?? '') == $p) ? 'selected' : '';
                            echo "<option value='$p' $selected>$p</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-2">
                    <label>Complaint Description *</label>
                    <textarea name="description" class="form-control" required><?= $data['description'] ?? '' ?></textarea>
                </div>

                <!-- Amount -->
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label>Total Amount *</label>
                        <input type="number" name="total_amount" id="total_amount"
                               value="<?= $data['total_amount'] ?? '' ?>"
                               class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Advance Amount</label>
                        <input type="number" name="advance_amount" id="advance_amount"
                               value="<?= $data['advance_amount'] ?? '' ?>"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Pending Amount</label>
                        <input type="number" name="pending_amount" id="pending_amount"
                               value="<?= $data['pending_amount'] ?? '' ?>"
                               class="form-control" readonly>
                    </div>
                </div>

                <!-- Engineer -->
                <div class="mb-2">
                    <label>Assign Engineer *</label>
                    <select name="assigned_to" class="form-control" required>
                        <option value="">Select Engineer</option>

                        <?php
                        $eng = $conn->query("SELECT id, name FROM service_engineers ORDER BY name ASC");
                        while($e = $eng->fetch_assoc()){
                            $selected = (($data['assigned_to'] ?? '') == $e['id']) ? 'selected' : '';
                            echo "<option value='{$e['id']}' $selected>{$e['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Note -->
                <div class="mb-2">
                    <label>Note</label>
                    <textarea name="note" class="form-control"><?= $data['note'] ?? '' ?></textarea>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label>Status *</label>
                    <select name="status" class="form-control" required>
                        <?php
                        $statuses = [
                            "open" => "Open",
                            "inprogress" => "Inprogress",
                            "completed" => "Completed",
                            "drop" => "Complaint Drop"
                        ];

                        foreach($statuses as $key => $label){
                            $selected = (($data['status'] ?? '') == $key) ? 'selected' : '';
                            echo "<option value='$key' $selected>$label</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Submit -->
                <button type="submit" id="submitBtn" class="btn btn-danger w-100">
                    <?= $id ? 'Update Complaint' : 'Save Complaint' ?>
                </button>

            </form>

        </div>
    </div>
</div>
<script>
    // Amount calc
const total = document.getElementById("total_amount");
const advance = document.getElementById("advance_amount");
const pending = document.getElementById("pending_amount");

function calc(){
    let t = parseFloat(total.value)||0;
    let a = parseFloat(advance.value)||0;
    pending.value = t - a;
}

window.onload = calc;
total.addEventListener("input", calc);
advance.addEventListener("input", calc);
</script>
<script src="assets/js/one-time-submit.js"></script>
</body>
</html>