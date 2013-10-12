<?php

class TaskRouter
{
    public function __construct()
    {
    }
    public static function route(PDO $conn)
    {
        $getVars = $_GET;

        $page = isset($getVars['location']) ? $getVars['location'] : 'empty';

        switch ($page) {
            case "timesheetPage":
               	$controller = "TimesheetController";
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
