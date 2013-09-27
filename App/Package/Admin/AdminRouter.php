<?php
class AdminRouter
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
            case "editPage":
                $controller = "EditPageController";
                break;
            case "timesheetsPage":
                $controller = "TimeSheetsController";
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
        include_once( AppBase.'/Package/'.$package.'/'.$controller.'.php');
        $router = new $controller();
        $router->route($conn);
        }
        else
        {

        include_once( AppBase.'/Controller/'.$controller.'.php');
        $controller = new $controller();
        $controller->setDatabase($conn);
        $controller->invoke();
        }

    }// end route
    //end class
}

?>