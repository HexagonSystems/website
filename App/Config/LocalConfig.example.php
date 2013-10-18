<?php
$site_root = "/"; //$_SERVER["SERVER_NAME"].dirname($_SERVER["SCRIPT_NAME"]);

define('SITE_ROOT', $site_root);
//http://mercury.ict.swin.edu.au/ccpmg501a/ccpmg501a_18/includes/
// __DIR__ return the directory of the current file
// This will be something like 
//  /Applications/MAMP/htdocs/hexagon/app/Config/
//  or
//  C:\\Program Files\xampp\htdocs\hexagon\app\Config\
//  dirname(__DIR__) climbs one more so we get:
//  */hexagon/app/

define('AppBaseSTRIPPED', "App/");
define('AppBase', dirname(__DIR__));
