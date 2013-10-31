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

		parent::invoke();

		//create a new view and pass it our template
		$view = new TimesheetView($this->template,$this->footer, 0);
		$view->assign('title' , 'Logged in');
		$view->assign('timesheetData', $taskTimeSheet);
		$view->assign('timesheetTotals', $taskTimeSheet);
	} // end function
}

?>