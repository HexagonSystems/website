<?php

class LoginController extends Controller
{
	// private $loggedOutView = 'loginView';
	// private $loggedInView = 'loggedInView';
	// private $forgottenPassword = 'ResetPassword';

	public function invoke()
	{
		parent::invoke();

		if (!isset($_GET['action']))
		{
			$user = new User($this->database);
			if(isset($_POST['username']) && isset($_POST['pass']))
			{
				$user = $user->loginUser($_POST["username"], $_POST["pass"]);
				if(!is_a($user, 'User')){
					//NOt logged In
					if($user = "verify"){
						echo "This account has not been verified. Please follow the instructions in your email to validate your account ";
						$get = array("action" => "mailSent");
						$verify = new VerifyController($get, $_POST);
						$verify->setDatabase($this->database);
						$verify->invoke();
					}else{
						echo $user;
						$this->template = 'view/'.$this->loggedOutView.'Template.php';
							
						//create a new view and pass it our template
						$view = new LoginView($this->template,$this->footer);
						$content ="";
						$view->assign('title' , 'Loggged in');
						$view->assign('content' , $content);
					}

				}else{
					$user->sessionCreate();
					var_dump($_SESSION);
				}
			}else
			{
				$this->template = 'LoginTemplate';

				//create a new view and pass it our template
				$view = new LoginView($this->template,$this->footer);
				$content ="";
				$view->assign('title' , 'Logged in');
				$view->assign('content' , $content);
			}
		}elseif (isset($_GET['action'])){
			if($_GET['action'] == 'logout')
			{
				if(isset($_SESSION['accountObject']) || isset($_SESSION['account']))
				{
					unserialize($_SESSION['accountObject'])->sessionDestroy();
					echo "You have been logged out";
				}else
				{
					echo "You were already logged out";
				}
				
			}
			/*
			 * Forgot Password Screen
			*/
			else if($_GET['action'] == 'forgotPassword')
			{
				if(!isset($_POST['action']) && !isset($_POST['email'])){
					$this->template = 'view/ResetPasswordTemplate.html';
					
					$view = new LoginView($this->template,$this->footer);
					$content ="";
					$view->assign('title' , 'Loggged in');
					$view->assign('content' , $content);
				}else{
					$get = array("action" => "mailSent");
					$verify = new VerifyController($get, $_POST);
					$verify->setDatabase($this->database);
					$verify->invoke();
				}

			}
			else if($_GET['action'] == 'login')
			{
				$user = new User($this->database);
				if(isset($_POST['username']) && isset($_POST['pass']))
				{
					$user = $user->loginUser($_POST["username"], $_POST["pass"]);
					if(!is_a($user, 'User')){
						//NOt logged In
						if($user = "verify"){
							echo "This account has not been verified. Please follow the instructions in your email to validate your account ";
							$get = array("action" => "mailSent");
							$verify = new VerifyController($get, $_POST);
							$verify->setDatabase($this->database);
							$verify->invoke();
						}else{
							echo $user;
							$this->template = 'View/Template/IndexTemplate.html';
							
							//create a new view and pass it our template
							$view = new LoginView($this->template,$this->footer);
							$content ="";
							$view->assign('title' , 'Loggged in');
							$view->assign('content' , $content);
						}
						
					}else{
						$user->sessionCreate();
						var_dump($_SESSION);
					}

				}else{
					//if $_POSTs arn't set
					//NOt logged In
					echo "Post not set";
					$this->template = 'View/Template/IndexTemplate.html';
					
					//create a new view and pass it our template
					$view = new LoginView($this->template,$this->footer);
					$content ="";
					$view->assign('title' , 'Loggged in');
					$view->assign('content' , $content);
				}

			}else if($_GET['action'] == 'register'){
				echo "attempting to register<br/>";
				$user = new User($this->database);
				if(isset($_POST['username']) && isset($_POST['pass']) && isset($_POST['email']))
				{
					$user = $user->createUser($_POST["username"], $_POST["pass"], $_POST["email"], "1");
					if(!is_a($user, 'User')){
						//NOt logged In
						echo $user;
						$this->template = 'View/Template/IndexTemplate.html';
						
						//create a new view and pass it our template
						$view = new LoginView($this->template,$this->footer);
						$content ="";
						$view->assign('title' , 'Loggged in');
						$view->assign('content' , $content);
					}else
					{
						echo $user->save();

					}
				}
			}else{
				echo "failed";
			}
		}

	} // end function
} //end class