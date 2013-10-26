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

        $page = isset($get['location']) ? $get['location'] : 'Index';

        $sth = $database->prepare("SELECT * FROM menu");
        $sth->execute();
        $pages = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pages as $key => $value) {
            $value['link'] = '/index.php?location='.$value['link'];
            $pages[$value['name']] = $value;
            unset($pages[$key]);
        }
        var_dump($pages);
        // echo $page;
        foreach ($pages as $nav => $details) {
            if($details['name'] == $page){
                $controller = $details['controller'];
                $package = $details['package'];
            };
        };


        //Fall Through to Index needs to be replaced with Error controller
        if(!isset($controller)){
            $controller = 'IndexController';
        };
		
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
