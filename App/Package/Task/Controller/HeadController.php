<?php
namespace Task;
class HeadController
{
	private $header;
	private $nav;
	private $fileName = 'Head';
	
	public function __construct()
	{
		$this->header = Base.'/View/Template/header.php';

		if(isset($_SESSION['activeUser'])&&($_SESSION['account']['access']=="10")){
			$this->nav = Base.'View/Template/nav10.php';
		}
		elseif(isset($_SESSION['activeUser'])){
			$this->nav = Base.'/View/Template/nav5.php';
		}else{
			$this->nav = Base.'/View/Template/nav0.php';				
		}
		
	} //end constructor
	public function invoke()
	{
			$this->template = Base.'/View/Template/'.$this->fileName.'Template.php';
			
			//create a new view and pass it our template
			$view = new HeadView($this->template,$this->header,$this->nav);

	} // end function
} //end class