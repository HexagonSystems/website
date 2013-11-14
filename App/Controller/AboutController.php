<?php

class AboutController extends Controller
{

    public function invoke()
    {
        parent::invoke();
        
        if (!isset($this->get['action'])) {

			$articleBio = new ArticleBio();
			$articleBio->setDatabase($this->database);
			$resultSet = $articleBio->getAllBios();
		
			foreach ($resultset as /*key*/ => /*value*/)
			
			$this->template = 'AboutTemplate';
			$view = new AboutView($this->template,$this->footer);
			/*
			$member =  $membersWithBio;
			$view->assign('member' , $member);
			$content =  $allBios;
			$view->assign('bio' , $content);
			*/
        }

    } // end function
}

?>