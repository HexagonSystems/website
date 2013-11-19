<?php

class LoginPluginController extends Controller
{	
	private $loginFailedMessage = "We were unable to find any users that matched the details provided.";

	public function invoke($action)
	{
		parent::invoke();

		$user = new User($this->database);

		if($action == 'login')
		{
			if(isset($_POST['username']) && isset($_POST['pass']))
			{
				$user = $user->loginUser($_POST["username"], $_POST["pass"]);
				if(!is_a($user, 'User')){
					//Not logged In
					$this->template = 'RequireLoginTemplate';
					//create a new view and pass it our template
					$view = new LoginView($this->template,$this->footer);
					$view->assign('alert' , $this->loginFailedMessage);
				}else
				{
					$user->sessionCreate();
					header("refresh: 0;"); // Reload the page if the user successfully logs in
				}
			}else // Display the login form
			{
				$this->template = 'RequireLoginTemplate';
				//create a new view and pass it our template
				$view = new LoginView($this->template,$this->footer);

			}

		} // end function
	} //end class
}