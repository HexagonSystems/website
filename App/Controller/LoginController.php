<?php

class LoginController extends Controller
{

	public function invoke()
	{
		parent::invoke();
		$get = $_GET;

		//sets the action in get to null if it's unset to stop the if's throwing errors.
		if(!isset($get['action'])){
			$get['action'] = null;
		}

		//as the form isn't outputting an action we will just check for the POST data
		if(isset($_POST['username']) && isset($_POST['pass'])){
				
			$user = new User($this->database);

			$user = $user->loginUser($_POST["username"], $_POST["pass"]);

			//if User fails to log in
			if(!is_a($user, 'User')){
				//Alert with the error
				echo '<div class="alert alert-warning alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Error Logging In!</strong> '.$user.'</div>';

				//Return to Login Page
				$this->template = 'LoginTemplate';

				//create a new view and pass it our template
				$view = new LoginView($this->template,$this->footer);

			}else{ //Success
				//Serialize the user
				$user->sessionCreate();
				header('Location: '.$_SERVER['REQUEST_URI'].'&action=loginSuccess');
			}

		}else if($get['action'] == 'loginSuccess') {
			if(isset($_SESSION['accountObject']) || isset($_SESSION['account'])){
				//Destroy the session for the user
				$user = unserialize($_SESSION['accountObject']);
				$user->sessionDestroy();
				
				//var_dump($user);
				//Success message
				echo '<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>Logged In!</strong> '.$user->getFirstName().' '.$user->getLastName().' logged in.</div>';
				
				//Return to index
				$this->template = 'IndexTemplate';
				
				//create a new view and pass it our template
				$view = new LoginView($this->template,$this->footer);
			}else
			{
				$this->template = 'LoginTemplate';
				
				//create a new view and pass it our template
				$view = new LoginView($this->template,$this->footer);
			}
			
				
		}else if($get['action'] == 'logout'){

			if(isset($_SESSION['accountObject']) || isset($_SESSION['account'])){
				//Destroy the session for the user
				unserialize($_SESSION['accountObject'])->sessionDestroy();
				header('Location: '.$_SERVER['REQUEST_URI']);
			}

			//Alert about log out
			echo '<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>Logged Out!</strong> User logged out.</div>';
			$this->template = 'LoginTemplate';

			//create a new view and pass it our template
			$view = new LoginView($this->template,$this->footer);

		}else if($get['action'] == 'register'){

			$user = new User($this->database);
			if(isset($_POST['username']) && isset($_POST['pass']) && isset($_POST['email']))
			{
				$user = $user->createUser($_POST["username"], $_POST["pass"], $_POST["email"], "1");
				if(!is_a($user, 'User')){
					//NOt logged In
					echo $user;
					$this->template = 'IndexTemplate';
						
					//create a new view and pass it our template
					$view = new LoginView($this->template,$this->footer);

				}else
				{
					echo $user->save();

				}
			}
			/*
			 * Forgot Password Screen
			*/
		}else if($get['action'] == 'forgotPassword'){

			if(!isset($_POST['action']) && !isset($_POST['email'])){
				$this->template = 'ResetPasswordTemplate';

				$view = new LoginView($this->template,$this->footer);

			}else{
				$get = array("action" => "mailSent");
				$verify = new VerifyController($get, $_POST);
				$verify->setDatabase($this->database);
				$verify->invoke();
			}

		}else{

			$this->template = 'LoginTemplate';

			//create a new view and pass it our template
			$view = new LoginView($this->template,$this->footer);
		}

	} // end function
} //end class