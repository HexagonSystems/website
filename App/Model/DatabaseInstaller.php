<?php

/**
 * Sets up the database if it doesn't already exist on the server
 * 
 * @author Alex
 */
class DatabaseInstaller
{
	private $database;
	private $currentDatabase = 'db_OCT_18_2013.sql';
	private $statement;
	
	public function __construct($template = NULL)
	{		
		$this->statement = file_get_contents(AppBase.'/includes/database/'.$this->currentDatabase);
	} //end constructor
	
	public function setDatabase(\PDO $database)
	{
		$this->database = $database;
	}
	
	public function installDatabase()
	{
		$query = $this->database->prepare($this->statement);
		
		try
		{
			$query->execute();
			echo "Database successfully imported";
		}catch(PDOException $e)
		{
			echo $e;
		}
	}
}

?>