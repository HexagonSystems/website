 <?php
/**
* user file
*
*@author tara , kaylee
*@version 1.0
*@packpostDate 
*/

class Post {

	private $postId;
	private $postTitle;
	private $postDate;
	private $postBody;
	private $postAuthor;
	private $postComments;
	private $postEdited;

	/**
	*Constructor sets up initial values of object
	*/
	public function __construct($postId, $postTitle, $postDate, $postBody, $postAuthor, $postComments, $postEdited)
	{
		$this->postId = $postId;
		$this->postTitle = $postTitle;
		$this->postDate = $postDate;
		$this->postBody = $postBody;
		$this->postAuthor = $postAuthor;
		$this->postComments = $postComments;
		$this->postEdited = $postEdited;
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
	public function setId($postId)
	{
		$this->postId = $postId;
	}
	
	/**
	*Gets the id
	*@param string $id
	*/
	public function getId()
	{
		return $this->postId;
	}	
	
	/**
	*Sets the postTitle
	*@param string $postTitle
	*/
	public function setPostTitle($postTitle)
	{
		$this->postTitle = $postTitle;
	}
	
	/**
	*Gets the postTitle
	*@param string $postTitle
	*/
	public function getPostTitle()
	{
		return $this->postTitle;
	}
	
	/**
	*Sets the postDate
	*@param string $postDate
	*/
	public function setPostDate($postDate)
	{
		$this->postDate = $postDate;
	}
	
	/**
	*Gets the postDate
	*@param string $postDate
	*/
	public function getPostDate()
	{
		return $this->postDate;
	}	
	
	/**
	*returns a postBody
	*@return string
	*/
	public function getPostBody()
	{
		return $this->postBody;
	}

	/**
	*Sets a postBody
	*@param string $postBody
	*/
	public function setPostBody($postBody)
	{
		$this->postBody = $postBody;
	}
	
	/**
	*Sets a postAuthor
	*@param string $postAuthor
	*/
	public function getPostAuthor()
	{
		return $this->postAuthor;
	}
	
	/**
	*Sets a postAuthor
	*@param string $postAuthor
	*/
	public function setPostAuthor($postAuthor)
	{
		$this->postAuthor = $postAuthor;
	}
	
	/**
	*Sets a postComments
	*@param string $postComments
	*/
	public function getPostComments()
	{
		return $this->postComments;
	}
	
	/**
	*Sets a postComments
	*@param string $postComments
	*/
	public function setPostComments($postComments)
	{
		$this->postComments = $postComments;
	}
		
	/**
	*Sets a postEdited
	*@param string $postEdited
	*/
	public function getPostEdited()
	{
		return $this->postEdited;
	}
	
	/**
	*Sets a postEdited
	*@param string $postEdited
	*/
	public function setPostEdited($postEdited)
	{
		$this->postEdited = $postEdited;
	}
}//end class
?>