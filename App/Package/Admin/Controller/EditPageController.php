<?php
include_once("Model/Users.php");
include_once("Controller/MasterController.php");
class EditPageController extends MasterController
{
    private $user;
    private $model;
    private $template;
    private $header;
    private $footer;
    private $nav;
    public function __construct()
    {
        $this->model = new Users();
        $this->header = '../../Templates/Admin/header.php';
	$this->footer = '../../Templates/Admin/footer.php';
	$this->nav = '../../Templates/Admin/nav.php';
    } //end constructor

    public function invoke()
    {

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