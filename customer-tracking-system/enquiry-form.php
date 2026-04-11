<?php
include 'config/db.php';

// ✅ Check Edit Mode
$id = $_GET['id'] ?? '';
$data = [];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM enquiries WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
}
?>
<?php
$eng = $conn->query("SELECT id, name FROM service_engineers ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $id ? 'Edit Enquiry' : 'New Enquiry' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/custom-style.css" rel="stylesheet">
</head>

<body>

<div class="container mt-3 mb-5">

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3"><?= $id ? 'Edit Enquiry' : 'New Enquiry' ?></h5>

            <form method="POST" action="save_enquiry.php">

                <!-- Hidden IDs -->
                <input type="hidden" name="id" value="<?= $data['id'] ?? '' ?>">
                <input type="hidden" name="customer_id" 
                       value="<?= $data['customer_id'] ?? ($_GET['customer_id'] ?? '') ?>">

                <!-- Dates -->
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label>Enquiry Date *</label>
                        <input type="date" name="enquiry_date" 
                               value="<?= $data['enquiry_date'] ?? '' ?>" 
                               class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Followup Date</label>
                        <input type="date" name="followup_date" 
                               value="<?= $data['followup_date'] ?? '' ?>" 
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Appointment Date</label>
                        <input type="date" name="appointment_date" 
                               value="<?= $data['appointment_date'] ?? '' ?>" 
                               class="form-control">
                    </div>
                </div>

                <!-- Source -->
                <div class="mb-2">
                    <label>Source *</label>
                    <select name="source" class="form-control" required>
                        <option value="">Select Source</option>
                        <option value="call" <?= ($data['source'] ?? '')=='call'?'selected':'' ?>>Call</option>
                        <option value="website" <?= ($data['source'] ?? '')=='website'?'selected':'' ?>>Website</option>
                        <option value="referral" <?= ($data['source'] ?? '')=='referral'?'selected':'' ?>>Referral</option>
                        <option value="showroom" <?= ($data['source'] ?? '')=='showroom'?'selected':'' ?>>Showroom</option>
                        <option value="stall" <?= ($data['source'] ?? '')=='stall'?'selected':'' ?>>Stall</option>
                    </select>
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

                <!-- Amount -->
                <div class="mb-2">
                    <label>Amount</label>
                    <input type="number" name="amount" step="0.01"
                           value="<?= $data['amount'] ?? '' ?>" 
                           class="form-control">
                </div>

                <!-- Engineer -->
                <div class="mb-2">
                    <label>Assign Engineer *</label>
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
                        <option value="open" <?= ($data['status'] ?? '')=='open'?'selected':'' ?>>Open</option>
                        <option value="inprogress" <?= ($data['status'] ?? '')=='inprogress'?'selected':'' ?>>Inprogress</option>
                        <option value="completed" <?= ($data['status'] ?? '')=='completed'?'selected':'' ?>>Completed</option>
                        <option value="drop" <?= ($data['status'] ?? '')=='drop'?'selected':'' ?>>Enquiry Drop</option>
                    </select>
                </div>

                <!-- Submit -->
                <button type="submit" id="submitBtn" class="btn btn-primary w-100">
                    <span id="btnText"><?= $id ? 'Update Enquiry' : 'Save Enquiry' ?></span>
                    <span id="loader" style="display:none;">⏳</span>
                </button>

            </form>

        </div>
    </div>

</div>
<script src="assets/js/enquiry-form.js"></script>
<script src="assets/js/one-time-submit.js"></script>
</body>
</html>