<?php


use Task\Task;
use Task\TaskRouter;
class Router
{
    public function __construct()
    {
    }
    public static function route(PDO $conn)
    {
        $getVars = $_GET;

        $cookieMonster = new CookieMonster();

        $cookieMonster->setDatabase($conn);

        $cookieMonster->lookForCookies();

        $page = isset($getVars['location']) ? $getVars['location'] : 'empty';

        switch ($page) {
            case "indexPage":
                $controller = "IndexController";
                break;
            case "login":
                $controller = "LoginController";
                break;
            case "accountPage":
                $controller = "AccountController";
                break;
            case "navPage":
                $controller = "NavController";
                break;
            case "adminPage":
            	$package = "Admin";
                $controller = "AdminRouter";
                break;
            case "timesheetPage":
                $package = "Task";
               	$controller = "TaskRouter";
                break;
            case "logout":
                $controller = "IndexController";
                break;
            case "config":
            	$controller = "ConfigController";
            	break;
            default:
                $controller = "IndexController";
                break;
        }//end switch
		
        if(isset($package))
        {	
        	require_once '\Package\Task\TaskRouter.php';
        	$test = new TaskRouter();
            $namespacedclass = $package.'\\'.$controller;
            //$router = new $namespacedclass();
            $namespacedclass::route($conn);
        }else{
            $controller = new $controller();
            $controller->setDatabase($conn);
            $controller->invoke();
        }
    }// end route
    //end class
}
