<?php
include 'db.php';

$where = "WHERE 1";

if(!empty($_GET['from']) && !empty($_GET['to'])){
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where .= " AND work_date BETWEEN '$from' AND '$to'";
}

$result = $conn->query("SELECT * FROM work_entries $where ORDER BY id DESC");

while($row = $result->fetch_assoc()){
    echo "<tr>
        <td>{$row['work_date']}</td>
        <td>{$row['work_type']}</td>
        <td>{$row['description']}</td>
        <td>{$row['amount']}</td>
        <td>{$row['engineer']}</td>
        <td>
            <button class='btn btn-sm btn-warning editBtn' data-id='{$row['id']}'>Edit</button>
            <button class='btn btn-sm btn-danger deleteBtn' data-id='{$row['id']}'>Delete</button>
        </td>
    </tr>";
}
?>