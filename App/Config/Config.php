<?php 
$filename = "LocalConfig.php";
if (file_exists($filename)) {
    require_once($filename);
} else {
	
    $site_base = "HEXAGONwebsite/";
    $site_root = "localhost/SmartGitHg/$site_base"; //$_SERVER["SERVER_NAME"].dirname($_SERVER["SCRIPT_NAME"]);
    
    
    define('SITE_ROOT', $site_root);
    define('SITE_BASE', $site_base);
    
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
}

require_once(AppBase.'/Service/Autoloader.php');
require_once(AppBase.'/Config/Database.example.php');
require_once(AppBase.'/Service/password.php');

require_once(AppBase.'/Package/Task/Config/Config.php');
require_once(AppBase.'/Package/Admin/Config/Config.php');

/**
 * Define any other config option you may want to use
*/
