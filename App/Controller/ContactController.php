<?php

class ContactController extends Controller
{

    public function invoke()
    {
        parent::invoke();
        
        $getVars = $_GET;
		$page = isset($getVars['action']) ? $getVars['action'] : 'empty';
		
		$userObj = new User($this->database);
		
		$user = new ArticleBio();
		$user->setDatabase($this->database);
		$resultSet = $user->getAllBios();
		foreach ($resultSet as $row)
		{	
			$members[] = $userObj->userDetails($row['memberId'], $row['firstName'], $row['lastName'], $row['email']);
		}
		
		if (isset($_GET['action']))
		{
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
				}
			}
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