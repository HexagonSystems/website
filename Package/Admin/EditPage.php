<?php
//This will contain the project page settings, it will load information stored from the database about the description/and the link.
//This page itself will be the router, and will load the appropriate controllers.
<?php
class EditPage
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
		//Fetch the passed request
		$request = $_SERVER['QUERY_STRING'];

		//Parse the page request and other GET variables
		$parsed = explode('&' , $request);

		//The location is the first element
		$location = array_shift($parsed);
		$page = explode('=' , $location);

		//The rest of the array are get statements, parse them out.
		$getVars = array();

                //The action is the Method to call
		$action = array_shift($parsed);
		$method = explode('=' , $action);

		//The Id is the second metdhod to call
		$id = array_shift($parsed);
		$postId = explode('=' , $id);


		foreach ($parsed as $argument)
		{
			//explode GET vars along '=' symbol to separate variable, values
			list($variable , $value) = explode('=' , $argument);
			$getVars[$variable] = $value;
		}

		if(isset($page[1]))
		{

			if ($page[1] == "")
			{
				include_once("controller/EditPageController.php");
				$controller = new EditPageController();
				$var = $controller->viewConstructionPage();
			}

                        elseif ($page[1] == "")
			{
				include_once("controller/BlogController.php");
				$controller = new BlogController();
				$var = $controller->viewConstructionPage();
			}
                        //This is where we will log the user in.
                        elseif ($page[1] == "login")
			{
				if($method[1] == "logUserIn")
				{
					include_once("../Login/Controller/LoginController.php");
					$controller = new LoginController();
					$controller->loginPage();

				}
				elseif($method[1] == "proccessLogin")
				{
					include_once("../Login/Controller/LoginController.php");
					$controller = new LoginController();
					$controller->proccessLogin();
				}
				elseif($method[1] == "loggedInUser")
				{
					if(isset($_SESSION['adminId']))
					{
						include_once("../Login/Controller/LoginController.php");
						$controller = new LoginController();
						$controller->loggedInUser();
					}
					else
					{
						include_once("Controller/Index.php");
						$controller = new BlogController();
						$var = $controller->viewBlogPageGuest();
					}
				}
				elseif($method[1] == "logOutUser")
				{
					include_once("../Login/Controller/LoginController.php");
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
	}
} //end class
?>

?>