<?php
include_once("../Login/Model/Users.php");
include_once("../Login/Controller/MasterController.php");
class LoginController extends MasterController
{
 private $user;
	private $model;
	private $template;
	private $header;
	private $footer;
	private $nav;
	public function __construct()
	{
		$this->model = new Users();
		$this->header = '../Login/Includes/header.php';
		$this->footer = '../Login/Includes/footer.php';
		$this->nav = '../Login/Includes/nav.php';
	} //end constructor

	public function invoke()
	{

	}

	function loginPage()
        {
	    $this->template = '../Login/View/LoginTemplate.html';
	    include_once('../Login/View/LoginView.php');
	    $view = new LoginView($this->template,$this->header,$this->nav,$this->footer);
	}

	function proccessLogin()
        {
            //Input from Form
	    $us_name = $_POST['usernameInput'];
	    $password = $_POST['passwordInput'];

       	    //Prepare fields for sanitisation and minor validation.
	    $options = array("$us_name", "$password");
	    $fieldName = array("Username", "Password");
	    $level = array("2", "4");
	    $charLength = array("0", "8");

	    //Now run the sanitisation/minor validation
            $options = $this->fieldSanitisation($options, $fieldName, $level, $charLength);

	    //check if the $us_name, $password are vaild login data
	    $user = $this->model->userVerification($options);

            //$this->user = $user;
	    if(!$user)
	    {
                echo "NOT CHICKEN!";
                //todo
            }
            else
            {
                $resultSet = $this->model->getUser($options[0]);
                //var_dump($resultSet);
                $this->logUserIn($resultSet);
            }
        }

	function logUserIn($user) //user object as session
	{
            var_dump($user);
	    $_SESSION['userId'] = $user->getId();
	    $_SESSION['userName'] = $user->getUsername();
	    $_SESSION['userAge'] = $user->getAge();
	    $_SESSION['userFirstName'] = $user->getFirstName();
	    $_SESSION['userLastName'] = $user->getLastName();
            $_SESSION['userdateJoined'] = $user->getDateJoined();
            $_SESSION['userCountry'] = $user->getCountry();
	    $_SESSION['userBio'] = $user->getBio();

            //goto index which cals landing page
           header("Location: index.php?location=login&action=loggedInUser");
            //echo $_SESSION['user'] . "CHICKEN";
		//PUT $user into a session variable
		//when checking if session exists check accesslevel (eg if accesslvl 3 do this,if accesslvl 2 do this etc)
	}

	function loggedInUser()
        {
	    $this->template = '../Login/View/UserTemplate.html';
	    include_once('../Login/View/UserView.php');



		//SESSION VARIABLES INTO USER TEMPLATE $nameOfSession->getId();
		//HERE CANT PASS VARIABLES TO VIEW
		$view = new UserView($this->template,$this->header,$this->nav,$this->footer,$this->user);
	}
	function logOutUser()
	{
              header("Location: index.php");
              session_destroy();


        }

//End Class
}
?>