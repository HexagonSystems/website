<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config_steve.php';

$user = new User($conn);

if (isset($_GET['data'])) {
        $data = $_GET['data'];
        if (!empty($data)) {
            if (checkExists($data, $user) == "true") {
                echo "true";
            } else {
                echo "This username is already in use";
            }
        } else {
            echo "null";
        }
}

function checkExists($data, $user)
{
    if ($user->checkUsername($data) == "Username found") {
        return "This username is taken";
    } else {
        return "true";
    }
}
