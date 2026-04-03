<?php
include 'config/db.php';

// ✅ Check Edit Mode
$id = $_GET['id'] ?? '';
$data = [];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM sales WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
}

$customer_id = $data['customer_id'] ?? ($_GET['customer_id'] ?? '');
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $id ? 'Edit Sale' : 'New Sale' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-3 mb-5">

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3 text-center"><?= $id ? 'Edit Sale' : 'New Sale' ?></h5>

            <form method="POST" action="save_sales.php">

                <!-- Hidden -->
                <input type="hidden" name="id" value="<?= $data['id'] ?? '' ?>">
                <input type="hidden" name="customer_id" value="<?= $customer_id ?>">

                <!-- Date -->
                <div class="mb-2">
                    <label>Date *</label>
                    <input type="date" name="sale_date" 
                           value="<?= $data['sale_date'] ?? '' ?>" 
                           class="form-control" required>
                </div>

                <!-- Product -->
                <div class="mb-2">
                    <label>Product *</label>
                    <select name="product" class="form-control" required>
                        <option value="">Select product</option>

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
                            echo "<option $selected>$p</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-2">
                    <label>Description</label>
                    <textarea name="description" class="form-control"><?= $data['description'] ?? '' ?></textarea>
                </div>

                <!-- Amounts -->
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
                    <label>Assign Service Engineer *</label>
                    <select name="assigned_to" class="form-control" required>
                        <option value="">Select Engineer</option>

                        <?php
                        $eng = $conn->query("SELECT id, name FROM service_engineers");
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
                        <option value="">Select Status</option>

                        <?php
                        $statuses = [
                            "open" => "Open",
                            "inprogress" => "Inprogress",
                            "completed" => "Completed",
                            "drop" => "Sale Drop"
                        ];

                        foreach($statuses as $key => $label){
                            $selected = (($data['status'] ?? '') == $key) ? 'selected' : '';
                            echo "<option value='$key' $selected>$label</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Submit -->
                <button class="btn btn-success">
                    <?= $id ? 'Update Sale' : 'Save Sale' ?>
                </button>

                </form>

        </div>
    </div>

</div>

<!-- JS -->
<script>
const total = document.getElementById("total_amount");
const advance = document.getElementById("advance_amount");
const pending = document.getElementById("pending_amount");

function calculatePending() {
    let t = parseFloat(total.value) || 0;
    let a = parseFloat(advance.value) || 0;
    pending.value = t - a;
}

// Run on load (for edit mode)
window.onload = calculatePending;

total.addEventListener("input", calculatePending);
advance.addEventListener("input", calculatePending);
</script>

</body>
</html>