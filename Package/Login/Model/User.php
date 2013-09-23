 <?php
/**
* user file
*
*@author tara , kaylee
*@version 1.0
*@package 
*/

class User {

	private $id;
	private $username;
	private $firstName;
	private $lastName;
	private $dateJoined;
	private $age;
	private $country;
	private $bio;

	/**
	*Constructor sets up initial values of object
	*/
	public function __construct($id, $username, $age, $firstName, $lastName, $dateJoined, $country, $bio)
	{
		$this->id = $id;
		$this->username = $username;
		$this->age = $age;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->dateJoined = $dateJoined;
		$this->country = $country;
		$this->bio = $bio;
	}
	
	/*public function __destruct()
	{

	}
	
	public function __toString()
	{
		echo "Using the toString method: ";
		return $this->getName();
	}
		
	/**
	*Sets the ID
	*@param string $id
	*/
	public function setId($id)
	{
		$this->id = $id;
	}
	
	/**
	*Gets the id
	*@param string $id
	*/
	public function getId()
	{
                         return $this->id;
	}	
	
	/**
	*Sets the username
	*@param string $username
	*/
	public function setUsername($username)
	{
		$this->id = $username;
	}
	
	/**
	*Gets the username
	*@param string $username
	*/
	public function getUsername()
	{
		return $this->username;
	}
	
	/**
	*Sets the age
	*@param string $age
	*/
	public function setAge($age)
	{
		$this->id = $age;
	}
	
	/**
	*Gets the age
	*@param string $age
	*/
	public function getAge()
	{
		return $this->age;
	}	
	
	/**
	*returns a firstName
	*@return string
	*/
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	*Sets a firstName
	*@param string $firstName
	*/
	public function setName($firstName)
	{
		$this->firstName = $firstName;
	}
	
	/**
	*Sets a lastName
	*@param string $lastName
	*/
	public function getLastName()
	{
		return $this->lastName;
	}
	
	/**
	*Sets a lastName
	*@param string $lastName
	*/
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}
	
	/**
	*Sets a dateJoined
	*@param string $dateJoined
	*/
	public function getDateJoined()
	{
		return $this->dateJoined;
	}
	
	/**
	*Sets a dateJoined
	*@param string $dateJoined
	*/
	public function setDateJoined($dateJoined)
	{
		$this->dateJoined = $dateJoined;
	}
		
	/**
	*Sets a country
	*@param string $country
	*/
	public function getCountry()
	{
		return $this->country;
	}
	
	/**
	*Sets a country
	*@param string $country
	*/
	public function setCountry($country)
	{
		$this->country = $country;
	}
	
			
	/**
	*Sets a bio
	*@param string $bio
	*/
	public function getBio()
	{
		return $this->bio;
	}
	
	/**
	*Sets a bio
	*@param string $bio
	*/
	public function setBio($bio)
	{
		$this->bio = $bio;
	}
	
}//end class
?>