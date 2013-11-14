<?php
namespace Task;
class TaskDA
{
	private $database = FALSE;

	public function setDatabase($database)
	{
		$this->database = $database;
	}

	/**
	 * Loads all of the tasks for the requested page
	 *
	 * This function is still a work in progress. The ability to customise the search
	 * further than just the page number will be implemented in the future.
	 *
	 * @param int $page The page of tasks to load
	 * @param int $quantity The page of tasks to load
	 *
	 * @return array Array of Member Ids
	 * @throws Exception \PDO expection
	 */
	public function loadTasks($starting, $quantity)
	{
		try
		{
			$statement = "SELECT * FROM
					(
					SELECT DISTINCT tskCom.postedDate, tskCom.memberId, tsk.*
					FROM `taskcomment` tskCom
					LEFT JOIN `task` tsk
					ON tskCom.taskId = tsk.taskId
					ORDER BY tskCom.postedDate DESC
						
					) AS my_table
					GROUP BY taskId
					ORDER BY postedDate DESC
					LIMIT :starting, :quantity";

			$query = $this->database->prepare($statement);
				
			$starting = ($starting - 1) * $quantity;
				
			$quantity += 0;
				
			$query->bindParam(':starting'   , $starting , \PDO::PARAM_INT);
			$query->bindParam(':quantity'   , $quantity	, \PDO::PARAM_INT);

			$query->execute();

			$arrayOfTasks = array();
			$arrayOfTasks['success'] = true;
			$arrayOfTasks['data'] = array();

			foreach ($query as $row) {
				$tempObject = new Task();
				$tempObject->setDatabase($this->database);
				$tempObject->setId($row['taskId']);
				$tempObject->setTitle($row['name']);
				$tempObject->setTimeStamp($row['entryDate']);
				$tempObject->setStatus($row['status']);
				$tempObject->setContent($row['details']);
				$tempObject->setCategory($row['type']);
				$tempObject->loadMembers();
				$tempObject->setLastUpdate(array(
						"memberId" => $row['memberId'],
						"postedDate" => $row['postedDate'],
				));

				array_push($arrayOfTasks['data'], $tempObject);
			}
			return $arrayOfTasks;
		} catch (\PDOException $e) {
			return createError($e);
		}
	}
	
	/**
	 * Gets the amount of Tasks currently available
	 *
	 * @return string|multitype:multitype: boolean
	 */
	public function getAllTaskCount()
	{
		try {
			$statement = "SELECT COUNT(*) FROM `task`";
	
			$query = $this->database->prepare($statement);
				
			$taskCountHolder = array();
			$taskCountHolder['success'] = true;
			$taskCountHolder['data'] = array();
				
			if(!$query->execute())
			{
				return $this->createError("SQL had trouble executing");
			}else
			{
				$row = $query->fetch();
				if($row !== false)
				{
					array_push($taskCountHolder['data'], $row[0]);
				}else
				{
					return $this->createError("Unable to find any Tasks");
				}
			}
			return $taskCountHolder;
		} catch (PDOException $e) {
			return createError($e);
		}
	}//end loadAllTaskCOunt

	/**
	 * Loads an existing task from the database
	 *
	 * @param Int $taskId the id number of the task
	 *
	 * @return Boolean  	False if load failed
	 * @return Task			If the Task loaded correctly
	 * @throws Exception	\PDO expection
	 */
	public function loadTask($id)
	{

		try {
			$statement = "SELECT * FROM `task`
					WHERE `taskId` = :taskId";

			$query = $this->database->prepare($statement);

			$query->bindParam(':taskId'   , $id , \PDO::PARAM_INT);

			$query->execute();

			$row = $query->fetch();
			if($row !== false)
			{
				$tempTask = new Task();
				$tempTask->setDatabase($this->database);
				$tempTask->setId($row['taskId']);
				$tempTask->setTitle($row['name']);
				$tempTask->setTimeStamp($row['entryDate']);
				$tempTask->setStatus($row['status']);
				$tempTask->setContent($row['details']);
				$tempTask->setCategory($row['type']);
				$tempTask->loadMembers();
				return $tempTask;
			}else
			{
				return false;
			}
		} catch (\PDOException $e) {
			echo $e;

			return false;
		}
	}//end loadPost

	/**
	 * Loads the members of a specific task
	 *
	 * @param Int $id the id number of the task
	 *
	 * @return array Array of Member Ids
	 * @throws Exception \PDO expection
	 */
	public function loadMembers($id)
	{
		try {

			$statement = 'SELECT DISTINCT wrk.memberId, mbr.firstName
					FROM work wrk, member mbr
					WHERE wrk.taskId = :taskId AND
					mbr.memberId = wrk.memberId';

			$query = $this->database->prepare($statement);

			$query->bindParam(':taskId'   , $id , \PDO::PARAM_INT);

			$query->execute();

			$arrayOfMembers = array();
			foreach ($query as $row) {
				$arrayOfMembers[$row['memberId']] = $row['firstName'];
			}
			//print_r($arrayOfPosts);
			return $arrayOfMembers;
		} catch (\PDOException $e) {
			echo $e;

			return false;
		}
	}

	/**
	 * I believe it gets the latest time someone adding in a time for the task (I forgot what this method does :()
	 *
	 * @param int $id the id number of the task
	 * @param int $quantity The quantity of comments to load
	 * @param int $startFrom Where to start from (the page number)
	 *
	 * @return array Array of comments
	 * @throws Exception \PDO expection
	 */
	public function loadUpdates($id, $quantity = 5, $startFrom = 0)
	{
		try {

			$statement = 	"SELECT DISTINCT wrk.memberId, wrk.date, mbr.firstName
					FROM work wrk, member mbr
					WHERE wrk.taskId = :taskId
					AND mbr.memberId = wrk.memberId
					ORDER BY wrk.date LIMIT 0, 1";

			$query = $this->database->prepare($statement);

			$query->bindParam(':taskId'   , $id , \PDO::PARAM_INT);

			$query->execute();

			$arrayParam = array();

			foreach ($query as $row) {
				$tmpArray = array();
				$tmpArray['memberId'] = $row['memberId'];
				$tmpArray['firstName'] = $row['firstName'];
				$tmpArray['date'] = $row['date'];

				array_push($arrayParam, $tmpArray);
			}

			//print_r($arrayOfPosts);
			return $arrayParam;
		} catch (\PDOException $e) {
			echo $e;

			return false;
		}
	}

	function createTask($title, $description, $status)
	{
		try {

			$statement = 'INSERT INTO `task`
					(name, details, status)
					VALUES
					(:name, :details, :status)';

			$query = $this->database->prepare($statement);
			$query->bindParam(':name'		, $title		, \PDO::PARAM_STR);
			$query->bindParam(':details'	, $description	, \PDO::PARAM_STR);
			$query->bindParam(':status'		, $status		, \PDO::PARAM_STR);

			$query->execute();
				
			$returnArray = array();
			$returnArray['success'] = true;
			$returnArray['taskId'] = $this->database->lastInsertId();
			return $returnArray;
		} catch (\PDOException $e) {
			return createError($e);
		}
	}

	function createTaskFromObject($taskObject)
	{
		/* MAYBE DO A CHECK HERE IF THE MEMBER HAS THE CORRECT ACCESS LEVEL */
		return $this->createTask($taskObject->getTitle(), $taskObject->getContent(), $taskObject->getStatus());
	}

	function EditTask($id, $title, $description, $status)
	{
		try {

			$statement = 'UPDATE `task`
					SET `name` = :name,
					`details` = :details,
					`status` = :status
					WHERE `taskId` = :taskId';

			$query = $this->database->prepare($statement);
			$query->bindParam(':taskId'		, $id		, \PDO::PARAM_INT);
			$query->bindParam(':name'		, $title		, \PDO::PARAM_STR);
			$query->bindParam(':details'	, $description	, \PDO::PARAM_STR);
			$query->bindParam(':status'		, $status		, \PDO::PARAM_STR);

			$query->execute();

			$returnArray = array();
			$returnArray['success'] = true;
			return $returnArray;
		} catch (\PDOException $e) {
			return createError($e);
		}
	}

	function editTaskFromObject($taskObject)
	{
		/* MAYBE DO A CHECK HERE IF THE MEMBER HAS THE CORRECT ACCESS LEVEL */
		$returnArray = $this->editTask($taskObject->getId(), $taskObject->getTitle(), $taskObject->getContent(), $taskObject->getStatus());
		$returnArray['data'] = $taskObject;
		return $returnArray;
	}

	/**
	 * Gets all possible Task status'
	 * 
	 * @return array
	 */
	function getAllTaskStatus()
	{
		$statement = "SHOW COLUMNS FROM `task` WHERE Field = 'status'";

		try{
			$query = $this->database->prepare($statement);

			$query->execute();
			
			$returnArray = array();
			$returnArray['success'] = true;
			$returnArray['data'] = array();
			/*
			 * Inspiration for code http://akinas.com/pages/en/blog/mysql_enum/
			* Regex taken straight from website
			* 19/04/2013
			*/
			if($query->rowCount())
			{
				foreach($query as $row){
					$enumValues = $row[1];
				}
					
				$regex = "/'(.*?)'/";
				preg_match_all( $regex , $enumValues, $enum_array );
				$enum_fields = $enum_array[1];
				
				foreach($enum_fields as $row){
					array_push($returnArray['data'], $row);
				}
				return $returnArray;
			}else
			{
				return $this->createError("No status' found");
			}
			
			
		} catch(PDOException $e) {
			return $this->createError($e);
		} //end of try/catch statement;
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