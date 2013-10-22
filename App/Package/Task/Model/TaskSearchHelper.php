<?php

namespace Task;

class TaskSearchHelper
{
	
	private $taskSearchHelperDA;

	/**
	 * Master array holds:
	 * 	- tag			This is the tag seen in the task comments
	 * 	- task			This is the tag title
	 * 	- user			This is the user name
	 * 	- page			This is the page to search for (where to start looking in the database = quantity * page)
	 * 	- quantity		This is the quantity to receive back
	 * 	- sortBy		This is ascending (1) or descending (0)
	 * 	- wildCard		This is a string that we will try and find anywhere in the database
	 *
	 * Tag, task and user have the option to set whether you are searching by id (0) or by string (1)
	 *
	 * The array will look something like this
	 *
	 * $masterArray = array(
	 * 		$tag = array (value = "hoursAdded",
	 * 						searchBy = 1),
	 * 		$task = array(value = 120,
	 * 						searchBy = 0),
	 * 		$dateFrom = STILL WORKING THIS OUT,
	 * 		sortBy = 1
	 * );
	 *
	 * @var array
	 */
	
	private $primarySearchData = array();
	private $searchFilters = array();
	
	/* ARRAY VALUES
	 * 
	 *  This is to allow for easy naming and to hopefully reduce typos throughout the code
	 */
	private $ref_tag = 'tag';
	private $ref_task = 'task';
	private $ref_user = 'user';
	private $ref_wildCard = 'wildCard';
	
	private $ref_value = 'value';
	private $ref_searchById = 'searchById';
	private $ref_page = 'page';
	private $ref_quantity = 'quantity';
	private $ref_sortByDesc = 'sortByDesc';
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->taskSearchHelperDA = new TaskSearchHelperDA();
	}
	
	/**
	 * Sets the database PDO object
	 *
	 * @param unknown $database
	 */
	public function setDatabase($database)
	{
		$this->taskSearchHelperDA->setDatabase($database);
	}

	/**
	 * Sets the tag you want to search for
	 *
	 * @param string || int 	$value
	 * @param boolean 			$searchById
	*/
	public function setTag($value, $searchById)
	{
		$this->primarySearchData[$this->ref_tag][$this->ref_value] = $value;
		$this->primarySearchData[$this->ref_tag][$this->ref_searchById] = $searchById;
	}

	/**
	 * Sets the task you want to seach for
	 *
	 * @param string || int 	$value
	 * @param boolean 			$searchById
	 */
	public function setTask($value, $searchById)
	{
		$this->primarySearchData[$this->ref_task][$this->ref_value] = $value;
		$this->primarySearchData[$this->ref_task][$this->ref_searchById] = $searchById;
	}

	/**
	 * Sets the user you want to seach for
	 *
	 * @param string || int 	$value
	 * @param boolean 			$searchById
	 */
	public function setUser($value, $searchById)
	{
		$this->primarySearchData[$this->ref_user][$this->ref_value] = $value;
		$this->primarySearchData[$this->ref_user][$this->ref_searchById] = $searchById;
	}
	
	/**
	 * Sets the page you want to search for
	 * 
	 * @param int $value
	 */
	public function setPage($value)
	{
		$this->searchFilters[$this->ref_page] = $value;
	}

	/**
	 * Sets how many rows to search for
	 * 
	 * @param int $value
	 */
	public function setQuantity($value)
	{
		$this->searchFilters[$this->ref_quantity] = $value;
	}
	
	/**
	 * Sets whether you want to sort the response by ascending or descending
	 *
	 * @param boolean $sortByDesc
	 */
	public function setSortByDesc($sortByDesc)
	{
		$this->searchFilters[$this->ref_sortByDesc] = $sortByDesc;
	}

	/**
	 * A general search function
	 *
	 * Will try and find this string in the tag, task and user
	 *
	 * @param string $stringToFind
	 */
	public function justFindThisAnywhere($stringToFind)
	{
		$this->master[$this->ref_wildCard] = $stringToFind;
	}

	/**
	 * Removes the tag from the search query
	 */
	public function removeTag()
	{
		if(isset($this->primarySearchData[$this->ref_tag]))
		{
			unset($this->primarySearchData[$this->ref_tag]);
		}
	}

	/**
	 * Removes the task from the search query
	 */
	public function removeTask()
	{
		if(isset($this->primarySearchData[$this->ref_task]))
		{
			unset($this->primarySearchData[$this->ref_task]);
		}
	}

	/**
	 * Removes the user from the search query
	 */
	public function removeUser()
	{
		if(isset($this->primarySearchData[$this->ref_user]))
		{
			unset($this->primarySearchData[$this->ref_user]);
		}
	}
	
	/**
	 * Removes the end date from the search query
	 */
	public function removePage()
	{
		if(isset($this->searchFilters[$this->ref_page]))
		{
			unset($this->searchFilters[$this->ref_page]);
		}
	}
	
	/**
	 * Removes the start and end date from the search query
	 */
	public function removeQuantity()
	{
		if(isset($this->searchFilters[$this->ref_quantity]))
		{
			unset($this->searchFilters[$this->ref_quantity]);
		}
	}
	
	/**
	 * Removes the the wild card string from the search query
	 */
	public function removeFindThisAnywhere()
	{
		if(isset($this->masterArray[$this->ref_wildCard]))
		{
			unset($this->masterArray[$this->ref_wildCard]);
		}
	}
	
	/**
	 * Gets the tag, returns false if the tag is not set
	 */
	public function getTag()
	{
		if(isset($this->primarySearchData[$this->ref_tag]))
		{
			return $this->primarySearchData[$this->ref_tag];
		}else
		{
			return false;
		}
	}
	
	/**
	 * Gets the task
	 */
	public function getTask()
	{
		return $this->primarySearchData[$this->ref_task];
	}
	
	/**
	 * Gets the user
	 */
	public function getUser()
	{
		return $this->primarySearchData[$this->ref_user];
	}
	
	/**
	 * Gets the page
	 */
	public function getPage()
	{
		return $this->searchFilters[$this->ref_page];
	}
	
	/**
	 * Gets the quantity
	 */
	public function getQuantity()
	{
		return $this->searchFilters[$this->ref_quantity];
	}
	
	/**
	 * Gets the wild card
	 */
	public function getWildCard()
	{
		return $this->masterArray[$this->ref_wildCard];
	}
	
	/**
	 * Returns true if the value is set
	 * 
	 * @return boolean
	 */
	private function valueIsSet($firstLevel, $secondLevel)
	{
		if($secondLevel === false)
		{
			if(isset($this->masterArray[$firstLevel]))
			{
				return true;
			}else
			{
				return false;
			}
		}else
		{
			if(isset($this->masterArray[$firstLevel][$secondLevel]))
			{
				return true;
			}else
			{
				return false;
			}
		}
	}
	
	/**
	 * Search
	 */
	public function search()
	{
		/* DETERMINE WHAT WE ARE ACTUALLY SEARCHING FOR */
		if(isset($this->primarySearchData[$this->ref_tag]))
		{
			/* SEARCHING FOR A TAG */
			$this->searchForTags();
		}else if(isset($this->primarySearchData[$this->ref_task]))
		{
			/* SEARCHING FOR A TASK */
			$this->searchForTasks();
		}else
		{
			return $this->createError("Please set a tag, task or a user to search for", "N/A");
		}
		
		$this->setSearchFilters();
		
		$response = $this->taskSearchHelperDA->performSearch();
		
		return $response;
		
	}
	
	/**
	 * Search for tags
	 */
	private function searchForTags()
	{
		/* SET THE PRIMARY TABLE */
		$this->taskSearchHelperDA->setPrimarySearch($this->ref_tag, $this->primarySearchData[$this->ref_tag]);
		
		/* CHECK IF WE ARE SEARCHING FOR TAGS IN A SPECIFIC TASK */
		if(isset($this->primarySearchData[$this->ref_task]))
		{
			$this->taskSearchHelperDA->addSearchCriteria($this->ref_task, $this->getTask());
		}
		
		/* CHECK IF WE ARE SEARCHING FOR TAGS FROM A SPECIFIC USER */
		if(isset($this->primarySearchData[$this->ref_user]))
		{
			$this->taskSearchHelperDA->addSearchCriteria($this->ref_user, $this->getUser());
		}
	}
	
	/**
	 * Search for tasks
	 */
	private function searchForTasks()
	{
		/* SET THE PRIMARY TABLE */
		$this->taskSearchHelperDA->setPrimarySearch($this->ref_task, $this->primarySearchData[$this->ref_task]);
	
		/* CHECK IF WE ARE SEARCHING FOR TASKS THAT A SPECIFIC USER IS A PART OF */
		if(isset($this->primarySearchData[$this->ref_user]))
		{
			$this->taskSearchHelperDA->addSearchCriteria($this->getUser());
		}
	}
	
	/**
	 * Sets the search filters for the taskSearchHelperDA object
	 */
	private function setSearchFilters()
	{
		if(sizeof($this->searchFilters) > 0)
		{
			$this->taskSearchHelperDA->setSearchFilters($this->searchFilters);
		}
	}
	
	/**
	 * Creates an array that holds information about the error
	 *
	 * @return string
	 */
	public function createError($comment, $attributeThatErrored)
	{
		$errorMessage = array();
		$errorMessage['success'] = false;
		$errorMessage['error']['location'] = $attributeThatErrored;
		$errorMessage['error']['message'] = $comment;
		return $errorMessage;
	}
	
	
}