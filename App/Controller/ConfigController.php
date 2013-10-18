<?php

class ConfigController extends Controller
{
	// private $loggedOutView = 'loginView';
	// private $loggedInView = 'loggedInView';
	// private $forgottenPassword = 'ResetPassword';

	public function invoke()
	{
		parent::invoke();

		if (!isset($_GET['request']))
		{
			echo "Nothing was requested";
		}elseif (isset($_GET['request'])){
			if($_GET['request'] == 'database')
			{
				if(isset($_GET['action']))
				{
					if($_GET['action'] == "install")
					{
						$dbInstaller = new DatabaseInstaller();
						$dbInstaller->setDatabase($this->database);
						$dbInstaller->installDatabase();
					}else
					{
						echo "Action not recongnized";
					}
				}else
				{
					echo "No action requested";
				}

			}
		}else{
			echo "failed";
		}
	} // end function invoke
} //end class