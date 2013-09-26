<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config_steve.php';

$user = new User($conn);

if (isset($_GET['data'])) {
    if (isset($_GET['type'])) {
        $data = $_GET['data'];
        $type = $_GET['type'];
        if (!empty($data)) {
            if (checkExists($data, $type, $user) == "true") {
                echo "true";
            } else {
                echo "This $type is already in use";
            }
        } else {
            echo "null";
        }
    }
}

function checkExists($data, $type, $user)
{
    if ($user->checkExists($data, $type) == "Data found") {
        return "This $type is taken";
    } else {
        return "true";
    }
}
