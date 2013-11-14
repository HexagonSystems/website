<?php
// use Task\Task;
// use Task\TaskRouter;
class Router
{
    private function __construct()
    {
    }
    public static function route(PDO $database)
    {
        $get = $_GET;

        $post = $_POST;

        $cookieMonster = new CookieMonster();

        $cookieMonster->setDatabase($database);

        $cookieMonster->lookForCookies();

        $page = isset($get['location']) ? $get['location'] : 'empty';

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
            $subRouter = $package.'\\'.$controller;
            //$router = new $subRouter();
            $subRouter::route($database);
        }else{
            $controller = new $controller($get, $post);
            $controller->setDatabase($database);
            $controller->invoke();
        }
    }// end route
    //end class
}
