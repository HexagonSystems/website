<?php 

define('SITE_ROOT', 'hexagon.dev/');

// __DIR__ return the directory of the current file
// This will be something like 
//  /Applications/MAMP/htdocs/hexagon/app/Config/
//  or
//  C:\\Program Files\xampp\htdocs\hexagon\app\Config\
//  dirname(__DIR__) climbs one more so we get:
//  */hexagon/app/

define('AppBaseSTRIPPED', "App/");
define('AppBase', dirname(__DIR__));


function autoloadModel($className) {
    $filename = AppBase.'/Model/'.$className.'.php';
    if (is_readable($filename)) {
        require $filename;
    }
}
 
function autoloadController($className) {
    $filename = AppBase."/Controller/" . $className . "Controller.php";
    if (is_readable($filename)) {
        require $filename;
    }else{
      $filename = AppBase."/Controller/" . $className . ".php";
      if (is_readable($filename)) {
          require $filename;
      }
    }
}

function autoloadService($className) {
    $filename = AppBase."/Service/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}

//THis is a HACK
//Change the View names
function autoloadView($className) {
    $filename = AppBase."/View/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}

spl_autoload_register("autoloadModel");
spl_autoload_register("autoloadController");
spl_autoload_register("autoloadService");
spl_autoload_register("autoloadView");

require_once(AppBase.'/Config/Database.php');

require_once(AppBase.'/Package/Task/Config/Config.php');


/**
 * Define any other config option you may want to use
*/
