<?php
Namespace Task;
class AllTimesheetController extends Controller
{

	protected $footer = "View/Template/footer.php";

	protected $template = "timesheetViewAll";

	public function invoke()
	{
			
		$taskLoader = new TaskLoader();
		$taskLoader->setDatabase($this->database);
		if(! isset($_SESSION['account'])){
			echo "Please login to view this page";
		}

		$taskHandler = new TaskHandler();
		$taskHandler->setDatabase($this->database);
		/* LOAD POSSIBLE TASK STATUS' */
		$allStatus = $taskHandler->getAllTaskStatus();
		if($allStatus['success'] === false)
		{
			/* HANDLE ERROR HERE OR IN VIEW */
		}
		
		/* GET HOW MANY TASKS THERE ARE FOR THE PAGEINATOR */
		$amountOfTasks = $taskHandler->countAllTasks();

		parent::invoke();

		//create a new view and pass it our template
		$view = new TimesheetView($this->template,$this->footer, 0);
		$view->assign('title' , 'Logged in');
		$view->assign('allTaskStatus' , $allStatus);
		$view->assign('taskCount' , $amountOfTasks);

		$view->assign('task' , null);


	} // end function
}

?>