<?php
include 'db.php';

$where = "WHERE 1";

if(!empty($_GET['from']) && !empty($_GET['to'])){
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where .= " AND work_date BETWEEN '$from' AND '$to'";
}

// Total Amount
$total = $conn->query("SELECT SUM(amount) as total FROM work_entries $where")->fetch_assoc()['total'] ?? 0;

// Total Entries
$count = $conn->query("SELECT COUNT(*) as cnt FROM work_entries $where")->fetch_assoc()['cnt'];

// ALL Engineers
$engineers = [];

$query = $conn->query("
    SELECT engineer, SUM(amount) as total 
    FROM work_entries 
    $where 
    GROUP BY engineer 
    ORDER BY total DESC
");

while($row = $query->fetch_assoc()){
    $engineers[] = [
        "name" => $row['engineer'],
        "total" => $row['total']
    ];
}

echo json_encode([
    "totalAmount" => $total,
    "totalEntries" => $count,
    "engineers" => $engineers
]);
?>