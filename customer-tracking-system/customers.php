<?php
include 'config/db.php';

// ✅ Optimized Query (latest records)
$sql = "
SELECT c.*,
       i.product AS installation_product,
       e.product AS enquiry_product,
       s.product AS service_product

FROM customers c

LEFT JOIN installations i 
    ON i.id = (
        SELECT MAX(id) FROM installations WHERE customer_id = c.id
    )

LEFT JOIN enquiries e 
    ON e.id = (
        SELECT MAX(id) FROM enquiries WHERE customer_id = c.id
    )

LEFT JOIN services s 
    ON s.id = (
        SELECT MAX(id) FROM services WHERE customer_id = c.id
    )

ORDER BY c.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customers List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body class="bg-light">

<div class="container mt-3 mb-5">

    <div class="d-flex justify-content-between mb-3">
        <h5>Customers</h5>
        <a href="customer-profile.php" class="btn btn-success btn-sm">+ Add Customer</a>
    </div>

    <!-- FILTERS -->
    <div class="row mb-3">
        <div class="col-12 col-md-3 mb-2">
            <input type="text" id="nameFilter" class="form-control" placeholder="Search Name">
        </div>
        <div class="col-12 col-md-3 mb-2">
            <input type="text" id="mobileFilter" class="form-control" placeholder="Search Mobile">
        </div>
        <div class="col-12 col-md-3 mb-2">
            <input type="text" id="areaFilter" class="form-control" placeholder="Search Area">
        </div>
        <div class="col-12 col-md-3 mb-2">
            <select id="productFilter" class="form-control">
                <option value="">All Products</option>
                <option>Water Softener</option>
                <option>Industrial RO Plants</option>
                <option>DMF & IRON Removal</option>
                <option>Alkaline Ionizer</option>
                <option>Alkaline Water Purifiers</option>
                <option>Domestic RO</option>
                <option>Commercial RO</option>
            </select>
        </div>
        <div class="col-12 col-md-3 mb-2">
            <input type="text" id="pincodeFilter" class="form-control" placeholder="Search Pincode">
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table id="customerTable" class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Area</th>
                        <th>Pincode</th>
                        <th>Enquiry Product</th>
                        <th>Installation Product</th>
                        <th>Service Product</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['mobile'] ?></td>
                            <td><?= $row['area'] ?></td>
                            <td><?= $row['pincode'] ?></td>
                            <td><?= $row['enquiry_product'] ?? '-' ?></td>
                            <td><?= $row['installation_product'] ?? '-' ?></td>
                            <td><?= $row['service_product'] ?? '-' ?></td>
                            <td>
                                <a href="customer-view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">View</a>
                                <a href="edit-customer.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No customers found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {

    let table = $('#customerTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true
    });

    // ✅ Column Filters
    $('#nameFilter').on('keyup', function() {
        table.column(1).search(this.value).draw();
    });

    $('#mobileFilter').on('keyup', function() {
        table.column(2).search(this.value).draw();
    });

    $('#areaFilter').on('keyup', function() {
        table.column(3).search(this.value).draw();
    });

    $('#pincodeFilter').on('keyup', function() {
        table.column(4).search(this.value).draw();
    });

    // ✅ Multi-column Product Filter
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

        let filter = $('#productFilter').val().toLowerCase();

        let enquiry = (data[5] || '').toLowerCase();
        let install = (data[6] || '').toLowerCase();
        let service = (data[7] || '').toLowerCase();

        if (!filter) return true;

        return (
            enquiry.includes(filter) ||
            install.includes(filter) ||
            service.includes(filter)
        );
    });

    $('#productFilter').on('change', function () {
        table.draw();
    });

});
</script>

</body>
</html>