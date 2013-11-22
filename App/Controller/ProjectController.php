<?php
/**
 * This is the controller that handles the projects 
 * Hexagon has worked on.
 * 
 * @author Tara Stevenson <tara.stevenson@hotmail.com>
 * @version 1.0
 * @package app
 *
 */
class ProjectController extends Controller
{

	/*
	* If the user has clicked a project to view
	* this method will discoved which project and 
	* load the project data, the files associated
	* and the template for that project. If there is no
	* action the page will load all projects.
	*/
    public function invoke()
    {
        parent::invoke();
        $getVars = $_GET;
		$page = isset($getVars['action']) ? $getVars['action'] : 'empty';
        
		if (isset($_GET['action']))
		{
			$article = new ArticleEntity($this->database);
			//param = $page String ID of the article that was presssed
			$userData = array();
			switch ($page) {
				case "6":
				case "7":
				case "8":
				case "9":
				case "10":
					$data = $article->getIndividualProjectData($page);
					foreach($data as $key => $row)
					{
						$userData[] = $article->getIndividualProjectObject($row['articleId'], $row['title'], $row['content'], $row['tag'], $row['date'], $row['firstName'], $row['lastName']);
					}
					$title = $data[0]['title'];
					$files = $article->getIndividualProjectFiles($title);						
					break;
				default:
					$page = null;
			}//end switch
			
			$this->template = 'IndividualProjectTemplate';
            $view = new ProjectView($this->template,$this->footer);
			$content =  $userData;
			$view->assign('projectData', $content);
			$content = $files;
			$view->assign('fileData', $content);
			
			
		}
		else if (!isset($_get['action'])) {

			$article = new ArticleEntity($this->database);
			//finds all projects
			$resultSet = $article->getProjectData();
			$project = array();
			//creates object
			foreach($resultSet as $row){
				$project[] = $article->getIndividualProjectObject($row['articleId'], $row['title'], $row['content'], $row['tag'], $row['date'], $row['firstName'], $row['lastName']);
			}
			
            //create a new view and pass it our template
			$this->template = 'ProjectTemplate';
            $view = new ProjectView($this->template,$this->footer);
			$content =  $project;
			$view->assign('projectData', $content);	
        
		}
    } // end function
}
?>