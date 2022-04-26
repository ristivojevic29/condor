<?php

header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../models/visit.php';


$database = new Database();

$db = $database->connect();

$visits = new Visit($db);

$data = file_get_contents("php://input");

if($data)
{
    $res = json_decode($data);

    if($visits->update($res->id,$res))
    {
        echo json_encode(['message' => 'Visit updated']);
    }
    else
    {
        echo json_encode(['message' => 'Not updated']);
    }
}
else
{
    echo json_encode(['message' => 'Data not found']);
}

