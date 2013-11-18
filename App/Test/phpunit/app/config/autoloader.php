<?php

define('AppBase', "/Applications/MAMP/htdocs/hexagon/App");
//echo AppBase;

require AppBase."/Service/password.php";

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