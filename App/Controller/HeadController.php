<?php
class HeadController
{
	private $header;
	private $navigation;
	
	public function __construct()
	{

		$this->header = AppBase.'/View/Template/header.html';

		$this->nav = AppBase.'/View/Template/nav.html';
		
	} //end constructor


    /**
     * Simple setter for the Database connection of the controller this is used for injecting the DB into any models the
     * controller class.
     * @param PDO $database Database connection to the App's database.
     * @return Void
     */
    public function setNavigation($navigation)
    {
        $this->navigation = $navigation;
    }

	public function invoke()
	{		
		//create a new view and pass it our template
		$view = new HeadView($this->header,$this->nav);
		$view->assign('navigation', $this->navigation);
	} // end function
} //end class