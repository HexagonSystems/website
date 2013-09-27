<?php 
/**
 * Define document paths
 */
define('AppBase' , $_SERVER["DOCUMENT_ROOT"].'hexagon/App');
define('SITE_ROOT' , 'localhost:8888/hexagon/');

function autoloadModel($className) {
    $filename = AppBase."/Model/" . $className . ".php";
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

/**
 * Define any other config option you may want to use
*/
