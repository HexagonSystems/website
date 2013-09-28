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
            case "registerPage":
                $controller = "RegisterController";
                break;
            case "accountPage":
                $controller = "AccountController";
                break;
            case "verify":
                $controller = "VerifyController";
                break;
            case "navPage":
                $controller = "NavController";
                break;
            case "adminPage":
                $controller = "AdminController";
                break;
            case "viewBlog":
                $controller = "BlogController";
                break;
            case "postPage":
                $controller = "EditPostController";
                break;
            case "logout":
                $controller = "IndexController";
                break;
            default:
                $controller = "IndexController";
                break;
        }//end switch

        include_once( AppBase.'/Controller/'.$controller.'.php');
        $controller = new $controller();
        $controller->setDatabase($conn);
        $controller->invoke();
    }// end route
    //end class
}