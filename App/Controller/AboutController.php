
<?php

	/**
	 * This file assigns the database that will be used as a variable
	 * the template that will be used to another variable 
	 * and the function getAllBios() is called to get the content from 
	 * the database and asign it to a variable
	 * 
	 *
	 * @author Sam Imeneo
	 * @package
	 */

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