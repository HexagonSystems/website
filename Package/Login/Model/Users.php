<?php
include_once("../blogSecureLogin/model/User.php");

class Users {

	private $sqlUser = array();
	private $persons;
	/**
	* database connection as an instance field
	*/
	private $conn;
	
	/**
	* Constructor creates the database connection and stores as an instance field
	* @params nil
	*/
	public function __construct()
	{
		//get a databse connection
		//and store it as an instance field so we can use it in the functions
		$this->conn = DataBase::getConnection();
	}
	
	public function getUser($username)
	{
		$sql = "SELECT * FROM user WHERE us_name = '$username'";
		$resultSet = $this->conn->query($sql) or die("failed!");
		
		while($row= $resultSet ->fetch(PDO::FETCH_ASSOC))
		{
			$user = new User($row['us_id'],$row['us_name'], $row['us_age'], $row['first_name'], $row['last_name'], $row['date_joined'], $row['country'], $row['us_bio']);
		}
		return $user;
	}

	public function userVerification($options)
	{
		$sql = "SELECT * FROM user WHERE us_name='$options[0]' AND us_password='$options[1]'";
		$resultSet = $this->conn->query($sql);
		$count = $resultSet->rowCount();
		if ($count === 1)
		{
			return true;
                }
		else
		{
			return false;
		}
	}
	

}
?>