<?php

class LoginPluginController extends Controller
{
	// private $loggedOutView = 'loginView';
	// private $loggedInView = 'loggedInView';
	// private $forgottenPassword = 'ResetPassword';
	
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
					//NOt logged In
					$this->template = 'RequireLoginTemplate';

					//create a new view and pass it our template
					$view = new LoginView($this->template,$this->footer);
					$content ="";
					$view->assign('title' , 'Logged in');
					$view->assign('content' , $content);
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
				$content ="";
				$view->assign('title' , 'Logged in');
				$view->assign('content' , $content);
			}

		} // end function
	} //end class
}