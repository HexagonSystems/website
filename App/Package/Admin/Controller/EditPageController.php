<?php
include_once("Model/Users.php");
include_once("Controller/MasterController.php");
class EditPageController
{
    private $viewName = 'Default';
    private $view = 'Default';
    private $get;
    private $post;
    public function __construct()
    {
        $this->model = new Users();
        $this->header = '../../Templates/Admin/header.php';
	$this->footer = '../../Templates/Admin/footer.php';
	$this->nav = '../../Templates/Admin/nav.php';
    } //end constructor

    public function invoke()
    {
        if($this->get['post'] == 'new')
        {
            //Save a new post
            //We'll assume this is actually done safely
            $this->model = new Post();
            $this->model->createPost($this->post['Title'],$this->post['Author'],$this->post['Content']);
    
            if($this->model->save() == 'success')
            {
                $this->viewName = 'Post';
            }
            else
            {
                $this->viewName = 'Error';
            }
    
            $this->template = AppBase.'/View/'.$this->viewName.'.php';
            //create a new view and pass it our template
            $view = new ExampleView($this->template);
            //Set it's model
            $view->setModel($this->model);
        }
        elseif($this->get['post'] == 'edit')
        {
            //Set up a post to be editted
        }
    }

    function defaultPage()
    {
        $this->template = '';
        include_once('');
        $view = new EditPageView($this->template,$this->header,$this->nav,$this->footer);
    }

    function createPage()
    {
        $this->template = '';
        include_once('');
        $view = new EditPageView($this->template,$this->header,$this->nav,$this->footer);
    }

    function viewEditPage()
    {
        $this->template = '';
        include_once('');
        $view = new EditPageView($this->template,$this->header,$this->nav,$this->footer);
    }
    function processCreateAPage()
    {
        $this->template = '';
        include_once('');
        $view = new EditPageView($this->template,$this->header,$this->nav,$this->footer);
    }

    function processEditAPage()
    {

    }

    function processDeleteAPage()
    {

    }

    function loginPage()
    {
    $this->template = '../Login/View/LoginTemplate.html';
    include_once('../Login/View/LoginView.php');
    $view = new LoginView($this->template,$this->header,$this->nav,$this->footer);
    {

//End Class
}
?>