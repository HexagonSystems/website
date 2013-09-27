<?php

require_once '../../config/config.php';
require_once '../../model/User.php';

$user = new User($conn);

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    if ($user->checkUsername($username) == "Username found") {
        echo "This username is taken";
    } else {
        echo "false";
    }
}
