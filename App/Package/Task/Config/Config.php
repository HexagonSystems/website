<?php 
namespace Task;

// __DIR__ return the directory of the current file
// This will be something like 
//  /Applications/MAMP/htdocs/hexagon/app/Config/
//  or
//  C:\\Program Files\xampp\htdocs\hexagon\app\Config\
//  dirname(__DIR__) climbs one more so we get:
//  */hexagon/app/
$test = 'test';

const AppBaseSTRIPPED = "App/";
const Base = "App/Package/Task";


function autoloadModel($className) {
    //Sub string the package name off the class in this case "Task\"
    $className = substr($className, 5);

    $filename = $_SERVER["DOCUMENT_ROOT"].'/'.Base.'/Model/'.$className.'.php';
            
    if (is_readable($filename)) {
        require $filename;
    }
}
 
function autoloadController($className) {
    //Sub string the package name off the class in this case "Task\"
    $className = substr($className, 5);

    $filename = $_SERVER["DOCUMENT_ROOT"].'/'.Base."/Controller/" . $className . "Controller.php";
    if (is_readable($filename)) {
        require $filename;
    }else{
      $filename = $_SERVER["DOCUMENT_ROOT"].'/'.Base."/Controller/" . $className . ".php";
      if (is_readable($filename)) {
          require $filename;
      }
    }
}

function autoloadService($className) {
    //Sub string the package name off the class in this case "Task\"
    $className = substr($className, 5);

    $filename = $_SERVER["DOCUMENT_ROOT"].'/'.Base."/Service/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}
        
function autoloadView($className) {
    //Sub string the package name off the class in this case "Task\"
    $className = substr($className, 5);

    $filename = $_SERVER["DOCUMENT_ROOT"].'/'.Base."/View/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}

spl_autoload_register("Task\autoloadModel");
spl_autoload_register("Task\autoloadController");
spl_autoload_register("Task\autoloadService");
spl_autoload_register("Task\autoloadView");

require_once('Database.php');

/**
 * Define any other config option you may want to use
*/
