<?php

include_once '../../config/database.php';
include_once '../../models/visit.php';

if(isset($_GET['date_start']) && isset($_GET['date_end']))
{
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];

    $database = new Database();

    $db = $database->connect();

    $visits = new Visit($db);

    $visits->search($date_start,$date_end);
}