<?php
include 'config/db.php';

$mobile = $_POST['mobile'] ?? '';
$name = $_POST['customer_name'] ?? '';
$type = $_POST['customer_type'] ?? '';
$status = $_POST['status'] ?? '';
$followed_by = $_POST['followed_by'] ?? '';

$sql = "SELECT * FROM customers WHERE 1=1";

$params = [];
$types = "";

/* Dynamic Conditions */

if(!empty($mobile)){
    $sql .= " AND mobile LIKE ?";
    $params[] = "%$mobile%";
    $types .= "s";
}

if(!empty($name)){
    $sql .= " AND customer_name LIKE ?";
    $params[] = "%$name%";
    $types .= "s";
}

if(!empty($type)){
    $sql .= " AND customer_type = ?";
    $params[] = $type;
    $types .= "s";
}

if(!empty($status)){
    $sql .= " AND status = ?";
    $params[] = $status;
    $types .= "s";
}

if(!empty($followed_by)){
    $sql .= " AND followed_by = ?";
    $params[] = $followed_by;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['customer_type']) ?></td>
<td><?= htmlspecialchars($row['priority']) ?></td>
<td><?= htmlspecialchars($row['enquiry_source']) ?></td>
<td><?= htmlspecialchars($row['other_source']) ?></td>
<td><?= $row['enquiry_data_and_time'] ?></td>
<td><?= htmlspecialchars($row['customer_name']) ?></td>
<td><?= htmlspecialchars($row['mobile']) ?></td>
<td><?= htmlspecialchars($row['address']) ?></td>
<td><?= htmlspecialchars($row['requirement']) ?></td>
<td><?= htmlspecialchars($row['product_name']) ?></td>
<td><?= htmlspecialchars($row['service_done']) ?></td>
<td><?= $row['appointment_datetime'] ?></td>
<td>₹<?= $row['price'] ?></td>
<td><?= htmlspecialchars($row['followed_by']) ?></td>
<td><?= htmlspecialchars($row['cancel_reason']) ?></td>
<td><?= $row['last_followup'] ?></td>
<td><?= htmlspecialchars($row['last_followed_by']) ?></td>
<td><?= htmlspecialchars($row['payment_mode']) ?></td>
<td>
<?php
if($row['status']=="Open"){
    echo "<span class='badge bg-primary'>Open</span>";
}elseif($row['status']=="Closed"){
    echo "<span class='badge bg-success'>Closed</span>";
}else{
    echo "<span class='badge bg-warning text-dark'>Inprocess</span>";
}
?>
</td>
<td><?= $row['created_at'] ?></td>
<td>
<a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm d-none">Delete</a>
</td>
</tr>
<?php } ?>