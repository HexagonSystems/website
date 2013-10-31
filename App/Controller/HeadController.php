<?php
class HeadController
{
	private $header;
	private $nav;
	private $fileName = 'Head';
	
	public function __construct()
	{
		$this->header = AppBase.'/View/Template/header.php';

		if(isset($_SESSION['activeUser'])&&($_SESSION['account']['access']=="10")){
			$this->nav = AppBase.'View/Template/nav10.php';
		}
		elseif(isset($_SESSION['account'])){
			/* PLEASE FIX UP THIS NAV SECTION, TEMP WORK AROUND TO DISPLAY USER'S FIRST NAME IN PLACE */
			$this->nav = AppBase.'/View/Template/nav5_2.php';
		}else{
			$this->nav = AppBase.'/View/Template/nav0.php';			
		}
		
	} //end constructor
	public function invoke()
	{
			$this->template = AppBase.'/View/Template/'.$this->fileName.'Template.php';
			
			//create a new view and pass it our template
			$view = new HeadView($this->template,$this->header,$this->nav);

	} // end function
} //end class