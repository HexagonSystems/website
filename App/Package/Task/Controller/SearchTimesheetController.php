<?php
Namespace Task;
class SearchTimesheetController extends Controller
{

	protected $footer = "View/Template/footer.php";

	protected $template = "timesheetViewSearched";

	public function invoke()
	{
		$taskLoader = new TaskLoader();
		$taskLoader->setDatabase($this->database);
		if(! isset($_SESSION['account'])){
			echo "Please login to view this page";
		}
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

		parent::invoke();

		//create a new view and pass it our template
		$view = new TimesheetView($this->template,$this->footer, 0);
		$view->assign('title' , 'Logged in');
		$view->assign('searchResult', $result);



	} // end function
}

?>