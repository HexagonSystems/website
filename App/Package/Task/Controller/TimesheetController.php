<?php
Namespace Task;
class TimesheetController extends Controller
{

	protected $footer = "View/Template/footer.php";

	private $template_viewAll = "timesheetViewAll";
	private $template_viewSingle = "timesheetViewSingle";
	private $template_viewSearch = "timesheetViewSearched";
	private $template_viewHours = "timesheetDisplayHours";

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
			
			foreach($_GET as $getDataKey => $getDataValue)
			{
				
				if(strpos($getDataKey, "_") && strlen($getDataValue)) // If the string conains a '_' that also isn't at index 0
				{
					$exploded = explode("_", $getDataKey);
					if(sizeof($exploded) == 2) // If the string was the correct format
					{
						if($exploded[1] == 'value') // If we are dealing with an actual input
						{
							$attribute_value = $getDataValue;
							$attribute_searchById = false; // Search for text by default
							
							if(isset($_GET[$exploded[0] . '_searchBy']))
							{
								if($_GET[$exploded[0] . '_searchBy'] == 'id') // If the user is searching by id
								{
									$attribute_searchById = true;
								}
							}
							
							switch($exploded[0])
							{
								case 'tag': $searchHelper->setTag($attribute_value, $attribute_searchById);
								break;
								case 'task': $searchHelper->setTask($attribute_value, $attribute_searchById);
								break;
								case 'member': $searchHelper->setUser($attribute_value, $attribute_searchById);
							}
						}
					}
				}
			}
			
			$searchHelper->setDatabase($this->database);
			
			$result = FALSE;
			
			if(isset($_GET['searchFor']))
			{
				if($_GET['searchFor'] == 'tag' || $_GET['searchFor'] == 'task')
				{
					$result = $searchHelper->search($_GET['searchFor']);
				}else
				{
					$result = $searchHelper->search('tag'); // Search for tags by default
				}
			}
			
			
			
			
			
			
			$this->template = $this->template_viewSearch;
			
			parent::invoke();
			
			//create a new view and pass it our template
			$view = new TimesheetView($this->template,$this->footer, 0);
			$view->assign('title' , 'Logged in');
			$view->assign('searchResult', $result);
			
			
			
			
			 
		}else if($_GET['action'] == 'displayHours')
		{
			$hoursLoader = new TaskHoursHandler();
			$hoursLoader->setDatabase($this->database);
			
			$taskTimeSheet = new TaskTimeSheet();
			
			
			
			if(isset($_GET['startDate']))
			{
				$startDate = strtotime($_GET['startDate']);
				
				
				$endDate = strtotime("+7 day", $startDate);
				
				$startDate = date('Y-m-d', $startDate);
				$endDate = date('Y-m-d', $endDate);
			}else
			{
				date_default_timezone_set('Australia/Melbourne');
				$endDate = date(time());
				
				
				$startDate = strtotime("-7 day", $endDate);
				$startDate = date('Y-m-d', $startDate);
				$endDate = date('Y-m-d', $endDate);
			}
			
			if(isset($_GET['user']))
			{
				$memberId = $_GET['user'];	
			}else
			{
				$memberId = false;
			}
			$hoursObjectArray = $hoursLoader->loadHours(false, false, $startDate, $endDate);
			
			$taskTimeSheet->setDateRange($startDate, $endDate);
			$taskTimeSheet->buildTimeSheetFromTaskHourArray($hoursObjectArray['data']);
			$taskTimeSheet->generateTotals();
			
			$this->template = $this->template_viewHours;
				
			parent::invoke();
				
			//create a new view and pass it our template
			$view = new TimesheetView($this->template,$this->footer, 0);
			$view->assign('title' , 'Logged in');
			$view->assign('timesheetData', $taskTimeSheet);
			$view->assign('timesheetTotals', $taskTimeSheet);
		}
		else
		{
			echo "Something went wrong";
		}

	} // end function
}

?>