<?php
Namespace Task;

require_once('Config/Config.php');

class TaskRouter
{
    public function __construct()
    {
    }
    public static function route(\PDO $conn)
    {
        $getVars = $_GET;

        $page = isset($getVars['action']) ? $getVars['action'] : 'empty';
		

        /* CHECK THE USER IS LOGGED IN 
        if(! isset($_SESSION['account'])){
        	echo "Please login to view this page";
        	$page = 'empty';
        } */
        
        $accessHandler = new \AccessHandler($conn);
        if(! ($accessHandler->requireAccess(5)) ) // Require the user to be logged in.
        {
        	return false;
        }
        
        switch ($page) {
            case "single":
               	$controller = "SingleTimesheetController";
                break;
            case "search":
            	$controller = "SearchTimesheetController";
            	break;
            case "report":
            	$controller = "ReportTimesheetController";
            	break;
            default:
                $controller = "AllTimesheetController";
                break;
        }//end switch

        //include_once( AppBase.'/Controller/'.$controller.'.php');
        $controller = "Task\\".$controller;
        $controller = new $controller;
        $controller->setDatabase($conn);
        $controller->invoke();
    }// end route
    //end class
}
