<?php
class Router
{
	public function __Construct()
	{
        
        }
        //So we need to use three parameters at the most.
        //One to deligate the page, we will name it page
        //One to select the method to run for the page we will call it method
        //One to select the inner method to run for the pages that need it we will call that methodTwo
	Static public function route()
	{
		//fetch the passed request
		$request = $_SERVER['QUERY_STRING'];
		//parse the page request and other GET variables
		$parsed = explode('&' , $request);
		//the location is the first element
		$location = array_shift($parsed) ;
		$page = explode('=' , $location);
		//the rest of the array are get statements, parse them out.
		$getVars = array();

		$action = array_shift($parsed) ;
		$method = explode('=' , $action);
		$id = array_shift($parsed) ;
		$postId = explode('=' , $id);

		
		foreach ($parsed as $argument)
		{
			//explode GET vars along '=' symbol to separate variable, values
			list($variable , $value) = explode('=' , $argument);
			$getVars[$variable] = $value;
		}
		
		if(isset($page[1]))
		{
			
			if ($page[1] == "about")
			{
				include_once("controller/BlogController.php");
				$controller = new BlogController();
				$var = $controller->viewConstructionPage();
			}
			elseif ($page[1] == "contact")
			{
				include_once("controller/BlogController.php");
				$controller = new BlogController();
				$var = $controller->viewConstructionPage();
			}
			elseif ($page[1] == "login")
			{
				if($method[1] == "logUserIn")
				{
					include_once("../blogSecureLogin/controller/LoginController.php");
					$controller = new LoginController();
					$controller->loginPage();
					
				}
				elseif($method[1] == "proccessLogin")
				{
					include_once("../blogSecureLogin/controller/LoginController.php");
					$controller = new LoginController();
					$controller->proccessLogin();				
				}
				elseif($method[1] == "loggedInUser")
				{
					if(isset($_SESSION['userId']))
					{
						include_once("../blogSecureLogin/controller/LoginController.php");
						$controller = new LoginController();
						$controller->loggedInUser();
					}
					else
					{
						include_once("controller/BlogController.php");
						$controller = new BlogController();
						$var = $controller->viewBlogPageGuest();
					}
				}
				elseif($method[1] == "logOutUser")
				{
					include_once("../blogSecureLogin/controller/LoginController.php");
					$controller = new LoginController();
					$controller->logOutUser();
				}
			}
			elseif ($page[1] == "register")
			{
				if($method[1] == "registerUser")
				{
					/*echo "The page your requested is '$page[1]' Get a list off all users";
					echo '<br/>';
					$vars = print_r($getVars, TRUE);
					echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
					*/
					
					include_once("../blogSecureLogin/controller/RegisterController.php");
					$controller = new RegisterController();
					$controller->registerPage();
				}
			}
			elseif ($page[1] == "home")
			{
				include_once("controller/BlogController.php");
				$controller = new BlogController();
				$var = $controller->viewBlogPageGuest();
			}
			elseif ($page[1] == "individualPostView")
			{
				if($method[1] == "guestIndividualPostPage")
				{
					include_once("controller/BlogController.php");
					$controller = new BlogController();
					$controller->guestIndividualPostPage($postId[1]);
				}
				elseif($method[1] == "loggedInUserView")
				{
					include_once("controller/BlogController.php");
				    $controller = new BlogController();
				    $controller->loggedInUserIndividualPostPage();
				}
			}
		}
		else{
			include_once("controller/BlogController.php");
			$controller = new BlogController();
			$var = $controller->viewBlogPageGuest();
		}
		
			
			
			
			
			
			/*if($page[1] == "page1")
			{
				echo "The page your requested is '$page[1]' Get a list off all users";
				echo '<br/>';
				$vars = print_r($getVars, TRUE);
				echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
				include_once("controller/BlogController.php");
				$controller = new BlogController();
				$controller->invoke();
			}
			else if($page[1] == "page2")
			{
				echo "The page your requested is '$page[1]' Get details of a user with id of ". $_GET['id'];
				echo '<br/>';
				$vars = print_r($getVars, TRUE);
				echo "The following GET vars were passed to the page:<pre>".$vars."</pre>";
				include_once("controller/LoginController.php");
				$controller = new LoginController();
				$controller->invoke();
			}
		}
		else
		{
			include_once("controller/BlogController.php");
			$controller = new BlogController();
			$controller->invoke();
		}*/
	}// end route
} //end class
?>
