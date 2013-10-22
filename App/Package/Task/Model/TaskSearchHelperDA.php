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
	
	private $searchOptions = array(
			'task' => array(
					'table' => 'task',
					'primary_id' => 'taskId',
					'primary_text' => 'name',
					'object'		=> 'Task\\Task',
					'join' => FALSE
			),
			'tag' => array(
					'table'			=> 'taskcomment',
					'primary_id'	=> 'commentId',
					'primary_text'	=> 'tag',
					'object'		=> 'Task\\TaskComment',
					'join'			=> array(
							'table'		=> 'member',
							'joinOn'	=> 'memberId',
							'get'		=> array('firstName')
					)
			),
			'member' => array(
					'table'	=> 'member',
					'primary_id' => 'memberId',
					'primary_text' => 'firstName',
					'join'	=> FALSE
			)
	);


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
			case $this->ref_tag: return $this->performSearchForTasks();
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
			
		if(isset($this->primarySearchData[$this->db_criteria]))
		{
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
				$query->bindValue(':primarySearch'   , $this->primarySearchData[$this->db_primary][$this->ref_value] , \PDO::PARAM_STR);
			}
				
			if(isset($this->primarySearchData[$this->db_criteria]))
			{
				foreach($this->primarySearchData[$this->db_criteria] as $criteria)
				{
					$tempName = $criteria['name'];
					$query->bindValue(":$tempName"   , $criteria[$this->ref_value] , \PDO::PARAM_INT);
				}
			}
				
			$query->execute();

			$commentHolder = array();
			$commentHolder['success'] = true;
			$commentHolder['data'] = array();


			$htmlString = "";
			foreach ($query as $row) {
				$tempTaskComment = new TaskComment();
				$tempTaskComment->setTaskId($row['taskId']);
				$tempTaskComment->setTag($row['tag']);
				$tempTaskComment->setTitle($row['title']);
				$tempTaskComment->setContent($row['content']);
				$tempTaskComment->setMemberId($row['memberId']);
				$tempTaskComment->setDate($row['postedDate']);

				array_push($commentHolder['data'], $tempTaskComment);
			}

			// echo json_encode($commentHolder);
			return $commentHolder;

		}catch(PDOException $e)
		{
			return $this->createError($e);
		}

	}
	
	private function performSearchForTasks()
	{
		/* SET THE PRIMARY SEARCH IN A LOCAL VARIABLE FOR EASY ACCESS */
		$primaryTarget = $this->primarySearchData[$this->db_primary]['name'];
		
		/* BEGIN STATEMENT */
		$statement = "SELECT primaryTable.*";
		
		/* ADD ANY JOINED TABLES INFORMATION WE WANT TO GET */
		if($this->searchOptions[$primaryTarget]['join'])
		{
			foreach($this->searchOptions[$primaryTarget]['join']['get'] as $secondaryColumn)
			{
				$statement .= ", secondaryTable." . $secondaryColumn;
			}
			
		}
				
		$statement .= " FROM ".$this->searchOptions[$primaryTarget]['table']." primaryTable";
		
		/* ADD INNER JOIN */
		if($this->searchOptions[$primaryTarget]['join'])
		{
			$statement .= " INNER JOIN " . $this->searchOptions[$primaryTarget]['join']['table'] ." secondaryTable";
			$statement .= " ON primaryTable." . $this->searchOptions[$primaryTarget]['join']['joinOn'] . " = secondaryTable." . $this->searchOptions[$primaryTarget]['join']['joinOn'];
		
		}
		
		$statement .= " WHERE primaryTable.";
	
		/* DETERMINE IF SEARCHING BY ID OR STRING, TRUE = ID, FALSE = STRING */
		if($this->primarySearchData[$this->db_primary][$this->ref_searchById])
		{
			$statement .= $this->searchOptions[$primaryTarget]['primary_id'];
		}else
		{
			$statement .= $this->searchOptions[$primaryTarget]['primary_text'];
		}
	
		$statement .= " LIKE :primarySearch";
		
		/* ADD SECONDARY SEARCH CRITERIA */
			
		if(isset($this->primarySearchData[$this->db_criteria]))
		{
			foreach($this->primarySearchData[$this->db_criteria] as $criteria)
			{
				$tempStatement .= " AND ";
				switch($criteria['name'])
				{
					case $this->ref_task: $statement .= $tempStatement . $this->table_taskComment_taskId." = :$this->ref_task ";
					break;
					case $this->ref_user: $statement .= $tempStatement . $this->table_taskComment_memberId." = :$this->ref_user ";
					break;
				}
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
				$query->bindValue(':primarySearch'   , "%".$this->primarySearchData[$this->db_primary][$this->ref_value]."%" , \PDO::PARAM_STR);
			}
	
			if(isset($this->primarySearchData[$this->db_criteria]))
			{
				foreach($this->primarySearchData[$this->db_criteria] as $criteria)
				{
					$tempName = $criteria['name'];
					$query->bindValue(":$tempName"   , $criteria[$this->ref_value] , \PDO::PARAM_INT);
				}
			}
			
			$query->execute();
			$tempObjectHolder = array();
			$tempObjectHolder['success'] = true;
			$tempObjectHolder['data'] = array();
			$htmlString = "";
			foreach ($query as $row) {
				$objectToMake = $this->searchOptions[$primaryTarget]['object'];
				$tempObject = new $objectToMake;
				$tempObject->buildFromQueryRow($row);
				array_push($tempObjectHolder['data'], $tempObject);
			}
	
			// echo json_encode($commentHolder);
			return $tempObjectHolder;
	
		}catch(PDOException $e)
		{
			return $this->createError($e);
		}
	
	}

	private function addSQLForSearchFilters()
	{
		$statement = " ";
		if(isset($this->searchFilters[$this->ref_sortByDesc]))
		{
			if($this->searchFilters[$this->ref_sortByDesc])
			{
				$statement .= "SORT BY DESC";
			}else
			{
				$statement .= "SORT BY ASC";
			}
		}else
		{
			$statement .= "SORT BY DESC";
		}

		if(isset($this->searchFilters[$this->ref_page]) && isset($this->searchFilters[$this->ref_quantity]))
		{
			$startingPoint = $this->searchFilters[$this->ref_page] * $this->searchFilters[$this->ref_quantity];
			$statement .= " LIMIT $startingPoint, $this->searchFilters[$this->ref_quantity";
		}else if(isset($this->searchFilters[$this->ref_quantity]))
		{
			$staement .= " LIMIT $this->searchFilters[$this->ref_quantity";
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