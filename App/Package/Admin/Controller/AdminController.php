<?php
Namespace Admin;
class AdminController extends Controller
{

	protected $footer = "View/Template/footer.php";
	private $template_edit = "editSite";
	private $template_editForm = "editArticleForm";
	private $template_addFile = "uploadFile";
	
	
	public function invoke()
	{
		
		$getVars = $_GET;
        $page = isset($getVars['action']) ? $getVars['action'] : 'empty';
		$article = new \ArticleEntity($this->database);
		
		if(!isset($_GET['action'])) 
		{
			$this->template = $this->template_edit;
			parent::invoke();
			
			$projectarry = $article->getAllArticles();
			foreach ($projectarry as $row)
			{
				$projObj[] = $article->getArticleObject($row['articleId'], $row['category'],$row['title'],$row['content'],$row['tag'],$row['date'],$row['status']);
			}
				
			//create a new view and pass it our template
			$view = new AdminView($this->template,$this->footer, 0);
			$view->assign('proj' , $projObj);
		}
		elseif (isset($_GET['action']))
		{
			if ($page == 'alter')
			{
				if ($_POST['alter'] == 'Edit')
				{
					$this->template = $this->template_editForm;
					parent::invoke();
					
					$id = $_POST['articleId'];					
					$singleArticle = $article->getProjectDataToEdit($id);
					$singleArticleObj = $article->getArticleObject($singleArticle[0]['articleId'], $singleArticle[0]['category'], $singleArticle[0]['title'], $singleArticle[0]['content'], $singleArticle[0]['tag'], $singleArticle[0]['date'], $singleArticle[0]['status']);
					
					$title = $singleArticle[0]['title'];
					$files[] = $article->getIndividualProjectFiles($title);
					
					$view = new AdminView($this->template, $this->footer, 0);
					$view->assign('proj' , $singleArticleObj);
/**/					$view->assign('files' , $files);
				}
				else if ($_POST['alter'] == 'Upload File')
				{
					$this->template = $this->template_addFile;
					parent::invoke();
					
					$id = $_POST['articleId'];					
					$singleArticle = $article->getProjectDataToEdit($id);
					$singleArticleObj = $article->getArticleObject($singleArticle[0]['articleId'], $singleArticle[0]['category'], $singleArticle[0]['title'], $singleArticle[0]['content'], $singleArticle[0]['tag'], $singleArticle[0]['date'], $singleArticle[0]['status']);
					
					$title = $singleArticle[0]['title'];
					$files[] = $article->getIndividualProjectFiles($title);
					
					
					$view = new AdminView($this->template, $this->footer, 0);
					$view->assign('files' , $files);
/**/					$view->assign('proj' , $singleArticleObj);
				}
				else{
					header('Location: index.php?location=adminPage');
				}
			}
			else if ($page == 'saveChanges')
			{
				if ($_POST['action'] == 'Cancel')
				{
					header('Location: index.php?location=adminPage');
				}
/**/				elseif ($_POST['action'] == 'Save') /*********************************************************** needs select data and confirmation note */
				{
					$result = $article->saveChanges($_POST);
					//header('Location: index.php?location=adminPage&&action=alter');
				}
				elseif($_POST['action'] == 'Upload')
				{
					echo "<pre>";
					var_dump($_POST);
					var_dump($_FILES);
					$result = $article->uploadFile($_POST, $_FILES);
					//auto add tag as proj title
					//auto add catagory as 3
					//auto add date as today
					//auto add content as filename
					//auto add status to completed
				}
				else
				{
					header('Location: index.php?location=adminPage');
				}
			}
		}
	} // end function
}


?>