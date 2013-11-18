<?php

class AccessHandler
{
	private $loginController;
	
	public function __construct($database)
	{
		$this->loginController = new LoginPluginController();
		$this->loginController->setDatabase($database);
	}
	
	public function requireAccess($access)
	{
		
		if($access > 0)
		{
			// Check the user is logged in
			if(! isset($_SESSION['account'])){
				// Include login form
				$this->loginController->invoke('login');
				return false;
			}
		}

		if($access > 5)
		{
			// Check the user has required access
		}
		
		return true;
	}
}

?>