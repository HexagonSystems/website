<?php

class ContactController extends Controller
{

    public function invoke()
    {
        parent::invoke();
        
        $user = new User($this->database);
		$resultSet = $user->getAllMembers();
		
		foreach ($resultSet as $row)
		{	
			
			$members[] = $user->userDetails($row['memberId'], $row['firstName'], $row['lastName'], $row['email'], $row['phoneNo']);

		}
		
		$this->template = 'ContactTemplate';
		
		$view = new ContactView($this->template,$this->footer);
		$content =  $members;
		$view->assign('member', $content);	

    } // end function
}

?>