<?php
include 'db.php';

$where = "WHERE 1";

if(!empty($_GET['from']) && !empty($_GET['to'])){
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where .= " AND work_date BETWEEN '$from' AND '$to'";
}

// ✅ Total Profit
$total = $conn->query("SELECT SUM(amount - expense) as total FROM work_entries $where")->fetch_assoc();

// ✅ Total Entries
$count = $conn->query("SELECT COUNT(*) as total FROM work_entries $where")->fetch_assoc();

// ✅ Engineer-wise Profit
$eng = $conn->query("
    SELECT engineer, SUM(amount - expense) as total 
    FROM work_entries 
    $where 
    GROUP BY engineer 
    ORDER BY total DESC
");

$engineers = [];
while($row = $eng->fetch_assoc()){
    $engineers[] = [
        "name" => $row['engineer'],
        "total" => $row['total']
    ];
}

echo json_encode([
    "totalAmount" => $total['total'] ?? 0,
    "totalEntries" => $count['total'],
    "engineers" => $engineers
]);
?>