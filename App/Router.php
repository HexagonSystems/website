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

        $package = "App";

        $sth = $database->prepare("SELECT * FROM menu");
        $sth->execute();
        $pages = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pages as $key => $value) {
            $value['link'] = '/index.php?location='.$value['link'];
            $pages[$value['name']] = $value;
            unset($pages[$key]);
        }

        switch ($page) {
            case "indexPage":
                $controller = "IndexController";
                break;  
            case "aboutPage":
                $controller = "AboutController";
                break;  
            case "projectPage":
                $controller = "ProjectController";
                break;  
            case "contactPage":
                $controller = "ContactController";
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
		
        if($package !== 'App')
        {	
            $subRouter = $package.'\\'.$controller;
            //$router = new $subRouter();
            $subRouter::route($database);
        }else{
            $controller = new $controller($get, $post);
            $controller->setDatabase($database);
            $controller->setNavigation($pages);
            $controller->invoke();
        }
    }// end route
    //end class
}
