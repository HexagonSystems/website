<?php
Namespace Admin;

class UserManagementController extends Controller
{

	protected $footer = "View/Template/footer.php";

	private $template_index = "userIndex";

	public function invoke()
	{
			
		if(! isset($_SESSION['account'])){
			echo "Please login to view this page";
		}
		
		if (!isset($_GET['perform'])) {

			$this->template = $this->template_index;
				
			parent::invoke();
				
			//create a new view and pass it our template
			$view = new AdminView($this->template,$this->footer, 0);
			$view->assign('title' , 'Logged in');
			
		}else if($_GET['action'] == "single")
		{
			
		}
		else
		{
			echo "Something went wrong";
		}

	} // end function
}

?>