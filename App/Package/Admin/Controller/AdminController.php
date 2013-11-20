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
			
			$projObj = array();
			
			$projectarry = $article->getAllArticles();
			foreach ($projectarry as $row)
			{
				$content = $article->htmlOut($row['content']);
				$projObj[] = $article->getArticleObject($row['articleId'], $row['category'],$row['title'], $content ,$row['tag'],$row['date'],$row['status']);
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
					$allStatus = $article->getEnumStatus();
					
					$view = new AdminView($this->template, $this->footer, 0);
					$view->assign('proj' , $singleArticleObj);
					$view->assign('select' , $allStatus);
					$view->assign('files' , $files);

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
					$view->assign('proj' , $singleArticleObj);
					$view->assign('files' , $files);
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
					exit();
				}
				elseif ($_POST['action'] == 'Save')
				{
					$result = $article->saveChanges($_POST);
					$_SESSION['fileMsg'] = "<p class='text-success'>You changes were successfully made.</p>";
					header('Location: index.php?location=adminPage&&action=alter');
					exit();
				}
				elseif($_POST['action'] == 'Upload')
				{
					$result = $article->uploadFile($_FILES);
					if($result != null)
					{
						$upload = $article->createArticle($_POST, $result);
						if ($upload)
						{
								$_SESSION['fileMsg'] = "<p class='text-success'>You file was uploaded successfully.</p>";
								header('Location: index.php?location=adminPage');
								exit();
						}
					}
					else
					{
							$_SESSION['fileMsg'] = "<p class='text-danger'>There was an error with your upload.</p>";
							header('Location: index.php?location=adminPage');
							exit();
					}
				}
				else
				{
					header('Location: index.php?location=adminPage');
					exit();
				}
			}
		}
	} // end function
}


?>