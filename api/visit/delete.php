<?php

include_once '../../config/database.php';
include_once '../../models/visit.php';

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $database = new Database();

    $db = $database->connect();

    $visits = new Visit($db);

    $visits->deleteOne($id);
}