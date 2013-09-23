<?php

class Router
{
	public function __Construct()
	{

        }

	Static public function route()
	{
		//fetch the passed request
		$request = $_SERVER['QUERY_STRING'];
		//parse the page request and other GET variables
		$parsed = explode('&' , $request);
		//the location is the first element
		$location = array_shift($parsed) ;
		$page = explode('=' , $location);
		$action = array_shift($parsed) ;
		$method = explode('=' , $action);
		//the rest of the array are get statements, parse them out.
		$getVars = array();

		foreach ($parsed as $argument)
		{
			//explode GET vars along '=' symbol to separate variable, values
			list($variable , $value) = explode('=' , $argument);
			$getVars[$variable] = $value;
		}
		
		//just to see the page onload
		//if(!isset ($_SESSION['userId'])){
		//	include_once("controller/LoginController.php");
		//	$controller = new LoginController();
		//	$controller->loginPage();
	//	}
		
		/*when the form is submitted*/
	//	if(isset ($_GET['login']))
		//{
		//	include_once("controller/LoginController.php");
		//	$controller = new LoginController();
		//	$controller->proccessLogin();
	//	}




		if(isset($page[1]))
		{
			if($page[1] == "login")
			{
				if($method[1] == "loginPage")
				{
					echo "The page your requested is '$page[1]' Get a list off all users";
					echo '<br/>';
					$vars = print_r($getVars, TRUE);
					echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
					include_once("controller/LoginController.php");
					$controller = new LoginController();
					$controller->loginPage();
				}
				elseif($method[1] == "proccessLogin")
				{
					echo "The page your requested is '$page[1]' Get a list off all users";
					echo '<br/>';
					$vars = print_r($getVars, TRUE);
					echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
					include_once("controller/LoginController.php");
					$controller = new LoginController();
					$controller->proccessLogin();
				}
				elseif($method[1] == "loggedInUser")
				{
					if(isset($_SESSION['userId']))
					{
						echo "The page your requested is '$page[1]' Get a list off all users";
						echo '<br/>';
						$vars = print_r($getVars, TRUE);
						echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
						include_once("controller/LoginController.php");
						$controller = new LoginController();
						$controller->loggedInUser();

					}
					else
					{
						echo 'CHICKEN!';
					//header("Location: .");
					}

				}
				elseif($method[1] == "logOutUser")
				{
					echo "The page your requested is '$page[1]' Get a list off all users";
					echo '<br/>';
					$vars = print_r($getVars, TRUE);
					echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
					include_once("controller/LoginController.php");
					$controller = new LoginController();
					$controller->logOutUser();

				}
			}
			else if($page[1] == "register")
			{
				if($method[1] == "registration")
				{
					echo "The page your requested is '$page[1]' Get a list off all users";
					echo '<br/>';
					$vars = print_r($getVars, TRUE);
					echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
					include_once("controller/RegisterController.php");
					$controller = new RegisterController();
					$controller->registerPage();
				}
				//echo "The page your requested is '$page[1]' Get details of a user with id of ". $_GET['id'];
				//echo '<br/>';
				//$vars = print_r($getVars, TRUE);
				//echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
				//	include_once("controller/LoginController.php");
				//$controller = new LoginController();
				//$controller->invoke();
			}
			else if($page[1] == "blog")
			{
				if($method[1] == "guest")
				{
					echo "The page your requested is '$page[1]' Get a list off all users";
					echo '<br/>';
					$vars = print_r($getVars, TRUE);
					echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
					include_once("controller/BlogController.php");
					$controller = new BlogController();
					$controller->guest();
				}
			//echo "The page your requested is '$page[1]' Get details of a user with id of ". $_GET['id'];
			//echo '<br/>';
			//$vars = print_r($getVars, TRUE);
			//echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
			//	include_once("controller/LoginController.php");
			//$controller = new LoginController();
			//$controller->invoke();
			}
		}
		else
		{
		include_once("controller/LoginController.php");
		$controller = new LoginController();
		$controller->loginPage();
		}
	}// end route
} //end class
?>
