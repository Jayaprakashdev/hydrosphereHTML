<?php
include 'config/db.php';

$mobile = $_POST['mobile'] ?? '';
$name = $_POST['customer_name'] ?? '';
$type = $_POST['customer_type'] ?? '';
$status = $_POST['status'] ?? '';

$sql = "SELECT * FROM customers WHERE 1=1";

if(!empty($mobile)){
    $sql .= " AND mobile LIKE '%$mobile%'";
}

if(!empty($name)){
    $sql .= " AND customer_name LIKE '%$name%'";
}

if(!empty($type)){
    $sql .= " AND customer_type='$type'";
}

if(!empty($status)){
    $sql .= " AND status='$status'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result)){
?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['customer_type'] ?></td>
<td><?= $row['priority'] ?></td>
<td><?= $row['enquiry_source'] ?></td>
<td><?= $row['other_source'] ?></td>
<td><?= $row['customer_name'] ?></td>
<td><?= $row['mobile'] ?></td>
<td><?= $row['address'] ?></td>
<td><?= $row['requirement'] ?></td>
<td><?= $row['product_name'] ?></td>
<td><?= $row['service_done'] ?></td>
<td><?= $row['appointment_datetime'] ?></td>
<td>₹<?= $row['price'] ?></td>
<td><?= $row['followed_by'] ?></td>
<td><?= $row['cancel_reason'] ?></td>
<td><?= $row['last_followup'] ?></td>
<td><?= $row['last_followed_by'] ?></td>
<td><?= $row['payment_mode'] ?></td>
<td>
<?php
if($row['status']=="Open"){
    echo "<span class='badge bg-primary'>Open</span>";
}elseif($row['status']=="Closed"){
    echo "<span class='badge bg-success'>Closed</span>";
}else{
    echo "<span class='badge bg-warning text-dark'>Pending</span>";
}
?>
</td>
<td><?= $row['created_at'] ?></td>

<td>
<a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
</td>
</tr>
<?php } ?>