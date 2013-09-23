<?php
include_once("../blogSecureLogin/model/Users.php");
class RegisterController
{
	private $model;
	private $template;
	private $header;
	private $footer;
	private $nav;
	public function __construct()
	{
		$this->model = new Users();
		$this->header = '../blogSecureLogin/includes/header.php';
		$this->footer = '../blogSecureLogin/includes/footer.php';
		$this->nav = '../blogSecureLogin/includes/nav.php';
	} //end constructor
	public function invoke()
	{
		/* // if nothing passed in
		{
			// no special user is requested, we'll show a list of all available users
			$this->template = 'view/UserTemplate.html';
			
			$users = $this->model->getUserList();
			include_once('view/UserView.php');
			//create a new view and pass it our template
			$view = new UserView($this->template,$this->header,$this->footer,$this->nav);
			
			// show the users list
			$content ="";
			$view->assign('title' , 'Users Details');
			foreach ($users as $name => $user)
			{
				$content = $content . '<tr><td><a href="' . SITE_ROOT .'/index.php?location=page2&action=view&id='.$user->getId().'">'.$user->getFirstName().'</a></td><td>'.$user->getLastName().'</td></tr>';
			}
			
			$view->assign('content' , $content);
		} // end else*/
	} // end function
	
	function registerPage()
        {
		$this->template = '../blogSecureLogin/view/RegisterTemplate.html';
		include_once('../blogSecureLogin/view/RegisterView.php');
		//create a new view and pass it our template
		$view = new RegisterView($this->template,$this->header,$this->nav,$this->footer);
	}

	function proccessRegister(){
		$userName = $_POST['usernameInput'];
		$firstName = $_POST['firstnameInput'];
		$lastName = $_POST['lastnameInput'];
		$age = $_POST['ageInput'];
		$country = $_POST['countryInput'];
		$email = $_POST['emailInput'];
		$password = $_POST['passwordInput'];
		$confirmPassword = $_POST['passwordConfirmInput'];
		
		$users = $this->model->userRegistration($userName, $firstName, $lastName, $age, $country, $email, $password, $confirmPassword);
	}
	
} //end class
?>