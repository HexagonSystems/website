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
		

        /* CHECK THE USER IS LOGGED IN */
        if(! isset($_SESSION['account'])){
        	echo "Please login to view this page";
        	$page = 'empty';
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
                $controller = "TimesheetController";
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
