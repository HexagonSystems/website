<?php

class Task extends ArticleEntity
{
	private $taskDA;
	
	private $COMMENTS_PER_PAGE = 5;
	
	function __construct()
	{
		$this->taskDA = new TaskDA();
	}
	
	public function setDatabase($database)
	{
		$this->taskDA->setDatabase($database);
		parent::setDatabase($database);
	}
	
	public function loadMembers()
	{
		$tempArrayOfMembers = $this->taskDA->loadMembers($this->getId());
		if($tempArrayOfMembers === false)
		{
			echo "Error"; // Need to implement a nice error
		}else
		{
			$this->article['members'] = $tempArrayOfMembers;
		}
	}
	
	public function loadComments($page)
	{
		/**
		 * This method can be simplified, was originally going to be more complex
		 */
		/*// A simple check to see if an invalid page number was passed through
		if($page < 1)
		{
			$page = 1;
		}
		
		// If the array hasn't been initialized, initialize it
		if(! (array_key_exists('comments', $this->article)) )
		{
			$this->article['comments'] = array();
		}
		
		// Adding the comments into the array
		$postitionToStartOn = ( $page - 1 ) * $this->COMMENTS_PER_PAGE;
		$postitionToEndOn = $postitionToStartOn + $this->COMMENTS_PER_PAGE;
		
		$tempCommentArray = $this->taskDA->loadComments($this->getId(), $page, $this->COMMENTS_PER_PAGE);
		
		for($counter = $postitionToStartOn; $counter < $postitionToEndOn; $counter++)
		{
			$this->article['comments'][$counter] = array_shift($tempCommentArray);
		}*/
	}
	
	//*********SETTERS----------------------
	public function setId($param)
	{
		$this->article['id'] = $param;
	}
	
	public function setTitle($param)
	{
		$this->article['title'] = $param;
	}
	
	public function setUpdates($param)
	{
		$this->article['updates'] = $param;
	}
	
	public function setLastUpdate($param)
	{
		$this->article['lastUpdate'] = $param;
	}
	
	//*********GETTERS--------------
	public function getThis()
	{
		return($this->article);
	}
	
	public function getId()
	{
		return($this->article['id']);
	}
	
	public function getTitle()
	{
		return($this->article['title']);
	}
	
	public function getMembers()
	{
		return($this->article['members']);
	}
	
	public function getMember($memberId)
	{
		return($this->article['members']);
	}
	
	public function getUpdates()
	{
		return($this->article['updates']);
	}
	
	public function getLastUpdate()
	{
		return $this->article['lastUpdate'];
	}
	
	public function getComments($startFrom = 0, $quantity = 5)
	{
		// return($this->article['comments']);
	}
	
	public function getUpdateAttribute($updatePlacement, $attribute)
	{
		if(array_key_exists($updatePlacement, $this->article['updates']))
		{
			$returnValue = ($this->article['updates'][$updatePlacement][$attribute]);
		}else {
			$returnValue = "null";
		}
	
		return $returnValue;
	
	}
	
	public function toArray()
	{
		return $this->article;
	}
}

?>