
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

			
			
			$this->template = 'AboutTemplate';
			$view = new AboutView($this->template, $this->footer);

			$content =  $resultSet;
			$view->assign('resultSet' , $content);
		}
    } // end function
}

?>