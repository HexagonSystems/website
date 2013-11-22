<?php
/**
* This is the controller that shows all emails of the
* team members of Hexagon as well a form that a user can 
* fill out which when sent will send to all members
* 
* @author Tara Stevenson <tara.stevenson@hotmail.com>
* @version 1.0
* @package app
*
*/
class ContactController extends Controller
{
	/*
	* If the user has clicked a sumbit on the form this function will 
	* make sure the correct action was called and that there was data 
	* in the form. It method will then create the variables for the 
	* mail() function an send it. the template will then be called 
	* with a notification of whether the email was sent or not. if 
	* there was no action the default template will be called
	*
	*/
    public function invoke()
    {
        parent::invoke();
        
        $getVars = $_GET;
		$page = isset($getVars['action']) ? $getVars['action'] : 'empty';
		
		$userObj = new User($this->database);
		$user = new ArticleBio();
		$user->setDatabase($this->database);
		//gets all members
		$resultSet = $user->getAllBios();
		//turn into an object array
		foreach ($resultSet as $row)
		{	
			$members[] = $userObj->userDetails($row['memberId'], $row['firstName'], $row['lastName'], $row['email']);
		}

		if (isset($_GET['action']))
		{
			//@param 	$page action requested in the url
			if ($page == 'email')
			{
				if (isset($_POST['msg']))
				{
					$sendTo = "1624970@student.swin.edu.au,". "stephentmcm@gmail.com,"."alex-robinson@live.com,"."9511954@student.swin.edu.au,"."1732862@student.swin.edu.au";
					$subject = "Email via Hexagon Webpage";
					$content = $_POST['email']."\r\n". $_POST['name']."\r\n".$_POST['msg']."\r\n";
					$headers = "From: web@hexagon.com.au";
					//writes email to G:\xampp\mailoutput
					mail($sendTo, $subject, $content, $headers, "-r 1624970@student.swin.edu.au");
					
					$emailSuccessful=true;
					$this->template = 'ContactTemplate';
					$view = new View($this->template,$this->footer);
					$content =  $members;
					$view->assign('member', $content);
					$view->assign('note', $emailSuccessful);
				}
				else
				{
					$emailSuccessful=false;
					$this->template = 'ContactTemplate';
					$view = new View($this->template,$this->footer);
					$content =  $members;
					$view->assign('member', $content);
					$view->assign('note', $emailSuccessful);
				}//end if msg
			}//end email
		}
		else if (!isset($_get['action'])) 
		{
			$this->template = 'ContactTemplate';
			
			$view = new View($this->template,$this->footer);
			$content =  $members;
			$view->assign('member', $content);	
		}// end action
    } // end function
}

?>