<?php
Namespace Task;
class ReportTimesheetController extends Controller
{

	protected $footer = "View/Template/footer.php";

	protected $template = "timesheetDisplayHours";

	public function invoke()
	{
			
		$taskLoader = new TaskLoader();
		$taskLoader->setDatabase($this->database);
		if(! isset($_SESSION['account'])){
			echo "Please login to view this page";
		}

		$hoursLoader = new TaskHoursHandler();
		$hoursLoader->setDatabase($this->database);

		$taskTimeSheet = new TaskTimeSheet();

		if(isset($_GET['timeFrame']))
		{
			$taskTimeSheet->setTimeFrame($_GET['timeFrame']);
		}else
		{
			$taskTimeSheet->setTimeFrame("default");
		}

		$format = $taskTimeSheet->getTimeFormat();


		if(isset($_GET['startDate']))
		{
			$startDate = $taskTimeSheet->getStartDate($_GET['startDate']);
		}else
		{
			$startDate = $taskTimeSheet->getStartDate(false);
		}

		if(isset($_GET['user']))
		{
			$memberId = $_GET['user'];
		}else
		{
			$memberId = false;
		}

		/*
		 For testing purposes
		echo "Start date:			" . $startDate . "<br/>";
		echo "End date:				" . $taskTimeSheet->getEndDate($startDate) . "<br/>";
		echo "Time Frame:			" . $taskTimeSheet->getTimeFrame() . "<br/>";
		echo "Time Frame interval:	" . $taskTimeSheet->getTimeFrameInterval() . "<br/>";
		*/

		$endDate = $taskTimeSheet->getEndDate($startDate);
		$hoursObjectArray = $hoursLoader->loadHours(false, false, $startDate, $endDate);
		$taskTimeSheet->setDateRange($startDate, $endDate);
		$taskTimeSheet->buildTimeSheetFromTaskHourArray($hoursObjectArray['data']);
		$taskTimeSheet->generateTotals();
		parent::invoke();

		//create a new view and pass it our template
		$view = new TimesheetView($this->template,$this->footer, 0);
		$view->assign('title' , 'Logged in');
		$view->assign('timesheetData', $taskTimeSheet);
		$view->assign('timesheetTotals', $taskTimeSheet);
		
		$view->assign('startDateFormatted', $startDate);
		$view->assign('endDateFormatted', $endDate);
	} // end function
}

?>