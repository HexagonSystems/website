<?php
namespace Task;
class Task extends \ArticleEntity
{
	private $taskDA;
	private $taskCommentDA;

	private $COMMENTS_PER_PAGE = 5;

	function __construct()
	{
		$this->taskDA = new TaskDA();
	}

	public function setDatabase(\PDO $database)
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

	/**
	 * Loads the amount of comments associated with this Task.
	 *
	 * Will only load from the database if not already loaded or if $forceReload is set to true.
	 *
	 * @param boolean $forceReload
	 */
	public function loadCommentCount() {
		$this->taskCommentDA = new TaskCommentDA();
		$this->taskCommentDA->setDatabase($this->database);
		$returnValue = $this->taskCommentDA->getCommentCount($this->getId());
		if($returnValue['success'])
		{
			$this->article['commentCount'] = $returnValue['data'][0];
		}else
		{
			$this->article['commentCount'] = false;
		}
		unset($this->taskCommentDA);
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
		if(isset($this->article['members']))
		{
			return($this->article['members']);
		}else
		{
			return false;
		}

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
	
	/**
	 * Returns the amount of comments associated with this Task.
	 * 
	 * Please call loadCommentCount() before using this method.
	 * 
	 * @return boolean
	 */
	public function getCommentCount()
	{
		if(array_key_exists('commentCount', $this->article))
		{
			return $this->article['commentCount'];
		}else
		{
			return false;
		}
	}

	public function toArray()
	{
		return $this->article;
	}

	/**
	 * Sets up object using a row from a query
	 *
	 * This is a hacked method, if anyone can come up with some better, please let me know.
	 *
	 * @param array $row
	 */
	public function buildFromQueryRow($row)
	{
		/* A WILD DIRTY HACK APPEARS */

		/*
		 * Please note: I am setting the Task's title to be an array instead of just the task title
		* so it formats nicely when printed out in the search field.
		*
		* It might be worth making a different method to return a nicely formatted array
		* for the TaskSearchHelper.
		*/
		$this->setTitle(array('id' => $row['taskId'], 'value' => $row['name']));
		$this->setTimeStamp($row['entryDate']);
		$this->setStatus($row['status']);
		$this->setContent($row['details']);
		$this->setCategory($row['type']);
	}
}

?>