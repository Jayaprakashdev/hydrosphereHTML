<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include "config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"];
$date = $data["task_date"];
$name = $data["task_name"];
$description = $data["description"];
$task_income = $data['task_income'];
$task_expense = $data['task_expense'];
$engineer = $data["engineer"];
$status = $data["status"];

$sql = "UPDATE tasks SET
task_date=?,
task_name=?,
description=?,
task_income=?,
task_expense=?,
engineer=?,
status=?
WHERE id=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssi",
    $date,
    $name,
    $description,
    $engineer,
    $status,
    $id
);

if($stmt->execute()){
    echo json_encode([
        "status"=>true,
        "message"=>"Task Updated Successfully"
    ]);
}else{
    echo json_encode([
        "status"=>false,
        "message"=>"Update Failed"
    ]);
}