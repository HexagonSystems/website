<?php
Namespace Task;
class TimesheetController extends Controller
{

	protected $footer = "View/Template/footer.php";

	private $template_viewAll = "timesheetViewAll";
	private $template_viewSingle = "timesheetViewSingle";
	private $template_viewSearch = "timesheetViewSearched";

	public function invoke()
	{
		 
		$taskLoader = new TaskLoader();
		$taskLoader->setDatabase($this->database);
		if (!isset($_GET['action'])) {
			 
			$this->template = $this->template_viewAll;
			parent::invoke();


			//create a new view and pass it our template
			$view = new TimesheetView($this->template,$this->footer, 0);
			$view->assign('title' , 'Logged in');
			 
			$view->assign('task' , null);
		}else if($_GET['action'] == "single")
		{
			if(!isset($_GET['param']))
			{
				$this->template = $this->template_viewSingle;
				parent::invoke();
				 
				//create a new view and pass it our template
				$view = new TimesheetView($this->template,$this->footer, 0);
				$view->assign('title' , 'Logged in');
			}else
			{
				$this->template = $this->template_viewSingle;
				parent::invoke();

				$taskLoader = new TaskLoader();
				$taskLoader->setDatabase($this->database);
				$task = $taskLoader->loadTask($_GET['param']);
				if($task === false)
				{
					/* HANDLE IF UNABLE TO LOAD TASK */
					echo "fail";
				}
				 
				//create a new view and pass it our template
				$view = new TimesheetView($this->template,$this->footer, 0);
				$view->assign('title' , 'Logged in');

				$view->assign('task' , $task);

				//$taskLoader->createComment("@addedTag", "Yay, adding tags is working");
			}
		}else if($_GET['action'] == 'search')
		{
			/**
			 * Attributes you can search for
			 * 	- Tag
			 * 	- Task
			 * 	- User
			 * 	- String
			 * 	- Date
			 *
			 * $testingSearch = new TaskSearchHelper();
			 * $testingSearch->setTag("addedHours", false);
			 * $testingSearch->setTask(127, true);
			 * $testingSearch->setUser(2, true);
			 * $testingSearch->setDatabase($this->database);
			 * $testingSearch->search();
			 */
			
			$searchHelper = new TaskSearchHelperNEW();
			
			if(isset($_GET['tag_id']))
			{
				$searchHelper->setTag($_GET['tag_id'], true);
			}else if(isset($_GET['tag_text']))
			{
				$searchHelper->setTag($_GET['tag_text'], false);
			}
			
			if(isset($_GET['task_id']))
			{
				$searchHelper->setTask($_GET['task_id'], true);
			}else if(isset($_GET['task_text']))
			{
				$searchHelper->setTask($_GET['task_text'], false);
			}
			
			if(isset($_GET['user_id']))
			{
				$searchHelper->setUser($_GET['user_id'], true);
			}else if(isset($_GET['user_text']))
			{
				$searchHelper->setUser($_GET['user_text'], false);
			}
			$searchHelper->setDatabase($this->database);
			$result = $searchHelper->search('task');
			
			$this->template = $this->template_viewSearch;
			
			parent::invoke();
			
			//create a new view and pass it our template
			$view = new TimesheetView($this->template,$this->footer, 0);
			$view->assign('title' , 'Logged in');
			$view->assign('searchResult', $result);
			
			
			
			
			 
		}else
		{
			echo "Something went wrong";
		}

	} // end function
}

?>