<?php
include 'config/db.php';

// ✅ Check Edit Mode
$id = $_GET['id'] ?? '';
$data = [];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM installations WHERE id=?");
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
    <title><?= $id ? 'Edit Installation' : 'New Installation' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-3 mb-5">

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3 text-center">
                <?= $id ? 'Edit Installation' : 'New Installation' ?>
            </h5>

            <form method="POST" action="save_installation.php">

                <!-- Hidden Fields -->
                <input type="hidden" name="id" value="<?= $data['id'] ?? '' ?>">
                <input type="hidden" name="customer_id" value="<?= $customer_id ?>">

                <?php if(empty($customer_id)): ?>
                    <div class="alert alert-danger text-center">
                        Customer not selected!
                    </div>
                <?php endif; ?>

                <!-- Date -->
                <div class="mb-2">
                    <label>Date *</label>
                    <input type="date" name="installation_date" 
                           value="<?= $data['installation_date'] ?? '' ?>" 
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
                    <label>Description</label>
                    <textarea name="description" class="form-control"><?= $data['description'] ?? '' ?></textarea>
                </div>

                <!-- Assign Engineer -->
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
                        <option value="">Select Status</option>

                        <?php
                        $statuses = [
                            "open" => "Open",
                            "inprogress" => "Inprogress",
                            "completed" => "Completed",
                            "drop" => "Installation Drop"
                        ];

                        foreach($statuses as $key => $label){
                            $selected = (($data['status'] ?? '') == $key) ? 'selected' : '';
                            echo "<option value='$key' $selected>$label</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Submit -->
                <button class="btn btn-warning w-100">
                    <?= $id ? 'Update Installation' : 'Save Installation' ?>
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>