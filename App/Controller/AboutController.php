<?php

class AboutController extends Controller
{

    public function invoke()
    {
        parent::invoke();
        
        if (!isset($this->get['action'])) {
           /* $user = new User($this->database);
			$resultSet = $user->getAllMembers();
			
			foreach ($resultSet as $row)
			{	
				
				$members[] = $user->userDetails($row['memberId'], $row['firstName'], $row['lastName'], $row['email'], $row['phoneNo']);

			}
			
			$this->template = 'AboutTemplate';
			
			$view = new AboutView($this->template,$this->footer);
			$content =  $members;
			$view->assign('member', $content);	*/
			
			//find the members
			/*$user = new User($this->database);
			$allMembers = $user->getAllMembers();
			$article = new ArticleEntity($this->database);
			
			//find bios associated with member
			foreach ($allMembers as $row)
			{
				$individualBio = $article->getBioData($row['memberId']);
				
				//if the member has a bio
				if ($individualBio != null)
				{
					$membersWithBio[] = $user->userDetails($row['memberId'], $row['firstName'], $row['lastName'], $row['email'], $row['phoneNo']);
					
					
					foreach ($individualBio as $data)
					{
						$bioContent[] = $article->getMemberBio($data['content']);
					}
				}
				
				$allBios[]= $individualBio;
			}			
			
			//var_dump($allBios);
			
			$this->template = 'AboutTemplate';
			$view = new AboutView($this->template,$this->footer);
			
			$member =  $membersWithBio;
			$view->assign('member' , $member);
			$content =  $allBios;
			$view->assign('bio' , $content);
			*/
        }

    } // end function
}

?>