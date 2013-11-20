<?php
Namespace Admin;

require_once('Config/Config.php');

class AdminRouter
{
    public function __construct()
    {
    }
    public static function route(\PDO $conn)
    {
        $getVars = $_GET;

        $page = isset($getVars['action']) ? $getVars['action'] : 'empty';
		
		
		$accessHandler = new \AccessHandler($conn);
        if(! ($accessHandler->requireAccess(5)) ) // Require the user to be logged in.
        {
        	return false;
        }
        
        switch ($page) {
        	case "home":
        		$controller = "AllTimesheetController";
        		break;
            case "user":
               	$controller = "UserManagementController";
                break;
            case "task":
            	$controller = "SearchTimesheetController";
            	break;
            case "report":
            	$controller = "ReportTimesheetController";
            	break;
            default:
                $controller = "AdminController";
                break;
        }//end switch

        //include_once( AppBase.'/Controller/'.$controller.'.php');
        $controller = "Admin\\".$controller;
        $controller = new $controller;
        $controller->setDatabase($conn);
        $controller->invoke();
    }// end route
    //end class
}
