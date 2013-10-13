
<?php
require 'password.php';

$password = "password";
echo $password .'<br />';

$hash = password_hash($password, PASSWORD_DEFAULT);

echo $hash .'<br />';

$verify = password_verify($password, $hash);

echo $verify .'<br />';

$hash = password_hash($password, PASSWORD_DEFAULT);

echo $hash .'<br />';

$verify = password_verify($password, $hash);

echo $verify .'<br />';

?>
