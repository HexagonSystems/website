<?php

class SiteMapController extends Controller
{

    public function invoke()
    {
        parent::invoke();
        
        if (!isset($this->get['action'])) {

			/*$articleBio = new ArticleBio();
			$articleBio->setDatabase($this->database);
			$resultSet = $articleBio->getAllBios();
*/
			
			
			$this->template = 'SiteMapTemplate';
			$view = new AboutView($this->template, $this->footer);
/*
			$content =  $resultSet;
			$view->assign('resultSet' , $content);*/
		}
    } // end function
}

?>