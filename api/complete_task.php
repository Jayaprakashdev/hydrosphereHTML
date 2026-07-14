<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include "config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"];

$status = "Completed";

$stmt = $conn->prepare("UPDATE tasks SET status=? WHERE id=?");

$stmt->bind_param("si",$status,$id);

if($stmt->execute()){
    echo json_encode([
        "status"=>true,
        "message"=>"Task Completed Successfully"
    ]);
}else{
    echo json_encode([
        "status"=>false,
        "message"=>"Failed"
    ]);
}