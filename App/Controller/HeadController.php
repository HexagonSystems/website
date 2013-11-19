<?php
class HeadController
{
	private $header;
	private $navigation;
	private $user;
	
	public function __construct()
	{
		$this->header = AppBase.'/View/Template/header.html';

		$this->nav = AppBase.'/View/Template/nav.html';
	} //end constructor

    public function setNavigation($navigation)
    {
        $this->navigation = $navigation;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

	public function invoke()
	{
		//create a new view and pass it our template
		$view = new HeadView($this->header,$this->nav);

        if(isset($_SESSION['accountObject'])){
        	$view->assign('user', 'loggedIn');
        }else{
        	$view->assign('user', 'loggedOut');
        }

		$view->assign('navigation', $this->navigation);
	} // end function
} //end class