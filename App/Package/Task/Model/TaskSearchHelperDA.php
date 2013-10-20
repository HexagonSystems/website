<?php
namespace Task;

class TaskSearchHelperDA
{
	private $database = FALSE;
	private $primarySearchData = array();
	private $searchFilters = FALSE;

	/* HELPER ARRAY VALUES */
	private $ref_tag = 'tag';
	private $ref_task = 'task';
	private $ref_user = 'user';
	private $ref_value = 'value';
	private $ref_searchById = 'searchById';
	private $ref_page = 'page';
	private $ref_quantity = 'quantity';
	private $ref_wildCard = 'wildCard';
	private $ref_sortByDesc = 'sortByDesc';

	/* HELPER DB SPECIFIC ARRAY VALUES */
	private $db_primary = 'primary';
	private $db_criteria = 'criteria';

	/* DATABASE INFORMATION */

	/* TASK ATTRIBUTES */
	private $table_task			= 'task';
	private $table_task_id		= 'taskId';
	private $table_task_name	= 'name';

	/* TASK COMMENTS ATTRIBUTES */
	private $table_taskComment		= 'taskcomment';
	private $table_taskComment_id	= 'commentId';
	private $table_taskComment_taskId = 'taskId';
	private $table_taskComment_memberId	= 'memberId';
	private $table_taskComment_tag	= 'tag';


	public function setDatabase($database)
	{
		$this->database = $database;
	}

	/**
	 * Sets what table you are looking to search in
	 *
	 * @param array $value
	 */
	public function setPrimarySearch($name, $value)
	{
		$value['name'] = $name;
		$this->primarySearchData[$this->db_primary] = $value;
	}

	/**
	 * Adds search criteria for the database
	 *
	 * @param array $value
	 */
	public function addSearchCriteria($name, $value)
	{
		if(!isset($this->primarySearchData[$this->db_criteria]))
		{
			$this->primarySearchData[$this->db_criteria] = array();
		}
		$value['name'] = $name;
		array_push($this->primarySearchData[$this->db_criteria], $value
		);
	}

	/**
	 * Sets the search filters
	 *
	 * @param array $value
	 */
	public function setSearchFilters($value)
	{
		$this->searchFilters = $value;
	}

	/**
	 * Attempt to perform the search
	 */
	public function performSearch()
	{
		switch($this->primarySearchData[$this->db_primary]['name'])
		{
			case $this->ref_tag: return $this->performSearchForTags();
			break;
			case $this->ref_task: return $this->performSearchForTasks();
			break;
			default: return $this->createError("Primary search data is invalid");
		}
	}

	private function performSearchForTags()
	{
		/* BEGIN STATEMENT */
		$statement = "SELECT * FROM taskcomment WHERE ";

		/* DETERMINE IF SEARCHING BY ID OR STRING, TRUE = ID, FALSE = STRING */
		if($this->primarySearchData[$this->db_primary][$this->ref_searchById])
		{
			$statement .= $this->table_taskComment_id;
		}else
		{
			$statement .= $this->table_taskComment_tag;
		}

		$statement .= " = :primarySearch";
		
		foreach($this->primarySearchData[$this->db_criteria] as $criteria)
		{
			$statement .= " AND ";
			switch($criteria['name'])
			{
				case $this->ref_task: $statement .= $this->table_taskComment_taskId." = :$this->ref_task ";
				break;
				case $this->ref_user: $statement .= $this->table_taskComment_memberId." = :$this->ref_user ";
				break;
			}
		}
		/* ADD SEARCH FILTERS */
		$statement .= $this->addSQLForSearchFilters();
		
		try
		{
			$query = $this->database->prepare($statement);
			
			/* BIND PARAMETERS */
			if($this->primarySearchData[$this->db_primary][$this->ref_searchById])
			{
				$query->bindValue(':primarySearch'   , $this->primarySearchData[$this->db_primary][$this->ref_value] , \PDO::PARAM_INT);
			}else
			{
				$query->bindValue(':primarySearch'   , "@".$this->primarySearchData[$this->db_primary][$this->ref_value] , \PDO::PARAM_STR);
			}
			
			foreach($this->primarySearchData[$this->db_criteria] as $criteria)
			{
				echo "bind added<br/>";
				$tempName = $criteria['name'];
				echo $tempName."<br/>";
				$query->bindValue(":$tempName"   , $criteria[$this->ref_value] , \PDO::PARAM_INT);
			}
			
			echo $statement;
			
			echo "<br/>";
			
		if($query->execute())
		{
			echo "working";
		}else {
			echo "not working";
		}
		
		foreach ($query as $row) {
				echo "found a row<br/>";
				echo $row['commentId'];
		};
		
	
		}catch(PDOException $e)
		{
			echo $e;
		}
		
	}

	private function addSQLForSearchFilters()
	{
		$statement = "";
		if(isset($this->searchFilters[$this->ref_sortByDesc]))
		{
			if($this->searchFilters[$this->ref_sortByDesc])
			{
				$statement += "SORT BY DESC";
			}else
			{
				$statement += "SORT BY ASC";
			}
		}else
		{
			$statement += "SORT BY DESC";
		}
		
		if(isset($this->searchFilters[$this->ref_page]) && isset($this->searchFilters[$this->ref_quantity]))
		{
			$startingPoint = $this->searchFilters[$this->ref_page] * $this->searchFilters[$this->ref_quantity];
			$statement += " LIMIT $startingPoint, $this->searchFilters[$this->ref_quantity";
		}else if(isset($this->searchFilters[$this->ref_quantity]))
		{
			$staement += " LIMIT $this->searchFilters[$this->ref_quantity";
		}
		
		//return $statement;
	}

	/**
	 * Build the SQL query to perform
	 */
	private function buildSQLQuery()
	{
		/* BEGIN STATEMENT */
		$statement = "SELECT * FROM ";

		/* DETERMINE WHICH TABLE TO SEARCH IN */
		switch($this->primarySearchData[$this->db_primary][$this->ref_value])
		{
			case $this->ref_tag: $statemnt += $this->table_taskComment;
			break;
			case $this->ref_task: $statement += $this->table_task;
			break;
			default: return $this->createError("Primary search data is invalid");
		}

		$statement += " WHERE ";

		/* ADD PRIMARY SEARCH CRITERIA (id vs title/string) */
		switch($this->primarySearchData[$this->db_primary][$this->ref_value])
		{
			case $this->ref_tag: $statemnt += $this->table_taskComment;
			break;
			case $this->ref_task: $statement += $this->table_task;
			break;
			default: return $this->createError("Primary search data is invalid");
		}
	}


	/**
	 * Creates an array that holds information about the error
	 *
	 * @return string
	 */
	public function createError($comment)
	{
		$errorMessage = array();
		$errorMessage['success'] = false;
		$errorMessage['error']['location'] = "TaskDA";
		$errorMessage['error']['message'] = $comment;
		return $errorMessage;
	}

}

?>