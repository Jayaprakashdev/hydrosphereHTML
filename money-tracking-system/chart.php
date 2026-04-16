<?php
include 'db.php';

$where = "WHERE 1";

if(!empty($_GET['from']) && !empty($_GET['to'])){
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where .= " AND work_date BETWEEN '$from' AND '$to'";
}

// ✅ Use profit instead of amount
$query = $conn->query("
    SELECT engineer, SUM(amount - expense) as total 
    FROM work_entries 
    $where 
    GROUP BY engineer
");

$labels = [];
$data = [];

while($row = $query->fetch_assoc()){
    $labels[] = $row['engineer'];
    $data[] = $row['total'] ?? 0;
}

// ✅ return clean JSON
header('Content-Type: application/json');

echo json_encode([
    "labels"=>$labels,
    "data"=>$data
]);
?>