<?php 
namespace Admin;
// __DIR__ return the directory of the current file
// This will be something like 
//  /Applications/MAMP/htdocs/hexagon/app/Config/
//  or
//  C:\\Program Files\xampp\htdocs\hexagon\app\Config\
//  dirname(__DIR__) climbs one more so we get:
//  */hexagon/app/
$test = 'test';

const AppBaseSTRIPPED = "App/";
const Base = "Package/Admin";


function autoloadModel($className) {
    //Sub string the package name off the class in this case "Task\"
    $className = substr($className, 5);

    $filename = AppBase.'/'.Base.'/Model/'.$className.'.php';
            
    if (is_readable($filename)) {
        require $filename;
    }
}
 
function autoloadController($className) {
    //Sub string the package name off the class in this case "Task\"
    $className = substr($className, 5);
	
    $filename = AppBase.'/'.Base."/Controller/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }else{
      $filename = AppBase.'/'.Base."/Controller/" . $className . ".php";
      if (is_readable($filename)) {
          require $filename;
      }
    }
}

function autoloadService($className) {
    //Sub string the package name off the class in this case "Task\"
    $className = substr($className, 5);

    $filename = AppBase.'/'.Base."/Service/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}
        
function autoloadView($className) {
    //Sub string the package name off the class in this case "Task\"
    $className = substr($className, 5);

    $filename = AppBase.'/'.Base."/View/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}

function autoloadRouter($className) {
	//Sub string the package name off the class in this case "Task\"
	$className = substr($className, 5);

	$filename = AppBase.'/'.Base."/". $className . ".php";
	if (is_readable($filename)) {
		require $filename;
	}
}

spl_autoload_register("Admin\autoloadModel");
spl_autoload_register("Admin\autoloadController");
spl_autoload_register("Admin\autoloadService");
spl_autoload_register("Admin\autoloadView");
spl_autoload_register("Admin\autoloadRouter");

// require_once('DataBase.php');

/**
 * Define any other config option you may want to use
*/
