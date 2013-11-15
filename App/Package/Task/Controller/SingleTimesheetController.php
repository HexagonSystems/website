<?php
Namespace Task;
class SingleTimesheetController extends Controller
{

	protected $footer = "View/Template/footer.php";

	protected $template = "timesheetViewSingle";

	public function invoke()
	{
			
		$taskLoader = new TaskLoader();
		$taskLoader->setDatabase($this->database);


		if(!isset($_GET['param']))
		{
			parent::invoke();

			//create a new view and pass it our template
			$view = new TimesheetView($this->template,$this->footer, 0);
			$view->assign('title' , 'Logged in');
		}else
		{
			parent::invoke();

			$taskHandler = new TaskHandler();
			$taskHandler->setDatabase($this->database);
			$task = $taskLoader->loadTask($_GET['param']);
			if($task === false)
			{
				/* HANDLE IF UNABLE TO LOAD TASK */
				echo "fail";
			}

			/* LOAD POSSIBLE TASK STATUS' */
			$allStatus = $taskHandler->getAllTaskStatus();
			if($allStatus['success'] === false)
			{
				/* HANDLE ERROR HERE OR IN VIEW */
			}

			//create a new view and pass it our template
			$view = new TimesheetView($this->template,$this->footer, 0);
			$view->assign('title' , 'Logged in');

			$view->assign('task' , $task);
			$task->loadCommentCount();

			$view->assign('allTaskStatus' , $allStatus);

			//$taskLoader->createComment("@addedTag", "Yay, adding tags is working");
		}

	} // end function
}

?>