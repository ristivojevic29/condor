<?php

    include_once '../../config/database.php';
    include_once '../../models/visit.php';

    $database = new Database();

    $db = $database->connect();

    $visits = new Visit($db);

    $visits->getData('json');
