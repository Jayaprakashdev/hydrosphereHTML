<?php
include 'db.php';

$where = "WHERE 1";

if(!empty($_GET['from']) && !empty($_GET['to'])){
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where .= " AND work_date BETWEEN '$from' AND '$to'";
}

$query = $conn->query("
    SELECT engineer, SUM(amount) as total 
    FROM work_entries 
    $where 
    GROUP BY engineer
");

$labels = [];
$data = [];

while($row = $query->fetch_assoc()){
    $labels[] = $row['engineer'];
    $data[] = $row['total'];
}

echo json_encode([
    "labels"=>$labels,
    "data"=>$data
]);
?>