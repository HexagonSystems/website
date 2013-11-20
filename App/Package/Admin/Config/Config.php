<?php 
namespace Admin;
// __DIR__ return the directory of the current file
// This will be something like 
//  /Applications/MAMP/htdocs/hexagon/app/Config/
//  or
//  C:\\Program Files\xampp\htdocs\hexagon\app\Config\
//  dirname(__DIR__) climbs one more so we get:
//  */hexagon/app/

const AppBaseSTRIPPED = "App/";
const Base = "Package/Admin/";

require_once(AppBase.'/'.Base.'Service/Autoloader.php');

// require_once('DataBase.php');

/**
 * Define any other config option you may want to use
*/

// require_once('DataBase.php');

/**
 * Define any other config option you may want to use
*/
