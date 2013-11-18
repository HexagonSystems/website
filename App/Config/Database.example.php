<?php
/**
* Singleton database connection class
* Only one instance (object) of this class can be created at any one time
* If an object exists it uses it if not it creates one and then uses it.
*/
class Database
{
  /**
	* Host configeration in an instance field
	*/
	private $host = "localhost";
	/**
	* Database configeration ia an instance field
	*/
	private $db = "ccpmg501a_18_db1";
	/**
	* user configeration ia an instance field
	*/
	private $user = "root";
	/**
	* password configeration ia an instance field
	*/
	private $pass = "";
	/**
	* The connection is a static class level variable
	*/
	private static $conn;
	/**
	* Private Constructor called internally from within the class
	* prams nil
	*
	*/
	
	private function __construct()
	{
		try
		{
		self::$conn = new PDO("mysql:host=$this->host;dbname=$this->db",$this->user,$this->pass);
		}
		catch (PDOException $e)
		{
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
		}
	}
	
	public static function getConnection()
	{
		if ( !self::$conn )
		{
			new Database();
		}
		return self::$conn;
	}
}