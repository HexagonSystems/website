<?php

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
            case "loginPage":
                $controller = "LoginController";
                break;
            case "accountPage":
                $controller = "AccountController";
                break;
            case "navPage":
                $controller = "NavController";
                break;
            case "adminPage":
                $controller = "AdminController";
                break;
            case "timesheetPage":
                $package = "Task";
               	$controller = "TaskRouter";
                break;
            case "logout":
                $controller = "IndexController";
                break;
            default:
                $controller = "IndexController";
                break;
        }//end switch

        if(isset($package))
        {
            include_once(AppBase.'/Package/'.$package.'/'.$controller.'.php');
            $namespacedclass = $package.'\\'.$controller;
            //$router = new $namespacedclass();
            $namespacedclass::route($conn);
        }else{
            include_once(AppBase.'/Controller/'.$controller.'.php');
            $controller = new $controller();
            $controller->setDatabase($conn);
            $controller->invoke();
        }
    }// end route
    //end class
}
