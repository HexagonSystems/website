<?php

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
	 * @param Int $page The page of tasks to load
	 *
	 * @return array Array of Member Ids
	 * @throws Exception PDO expection
	 */
	public function loadTasks($page)
	{
		try {
			$statement = "SELECT *
					FROM `task`";

			$query = $this->database->prepare($statement);

			$query->execute();

			$arrayOfTasks = array();

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
				$tempObject->setUpdates($this->loadUpdates($row['taskId'], 1));
				
				$arrayOfTasks[$tempObject->getId()] = $tempObject;
			}

			// print_r($arrayOfPosts);
			return $arrayOfTasks;
		} catch (PDOException $e) {
			echo $e;

			return false;
		}
	}

	/**
	 * Loads an existing task from the database
	 *
	 * @param Int $taskId the id number of the task
	 *
	 * @return Boolean  	False if load failed
	 * @return Task			If the Task loaded correctly
	 * @throws Exception	PDO expection
	 */
	public function loadTask($id)
	{

		try {
			$statement = "SELECT * FROM `task`
					WHERE `taskId` = :taskId";

			$query = $this->database->prepare($statement);

			$query->bindParam(':taskId'   , $id , PDO::PARAM_INT);

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
		} catch (PDOException $e) {
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
	 * @throws Exception PDO expection
	 */
	public function loadMembers($id)
	{
		try {

			$statement = 'SELECT DISTINCT wrk.memberId, mbr.firstName
					FROM work wrk, member mbr
					WHERE wrk.taskId = :taskId AND
					mbr.memberId = wrk.memberId';

			$query = $this->database->prepare($statement);

			$query->bindParam(':taskId'   , $id , PDO::PARAM_INT);

			$query->execute();

			$arrayOfMembers = array();

			foreach ($query as $row) {
				$arrayOfMembers[$row['memberId']] = $row['firstName'];
			}

			//print_r($arrayOfPosts);
			return $arrayOfMembers;
		} catch (PDOException $e) {
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
	 * @throws Exception PDO expection
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

			$query->bindParam(':taskId'   , $id , PDO::PARAM_INT);

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
		} catch (PDOException $e) {
			echo $e;

			return false;
		}
	}
	
	function createTask($title, $description, $memberId, $status)
	{
		try {
	
			$statement = 'INSERT INTO `task`
					(name, details, status)
					VALUES
					(:name, :details, :status)';
	
			$query = DataBase::getConnection()->prepare($statement);
	
			$query->bindParam(':name'		, $title		, PDO::PARAM_STR);
			$query->bindParam(':details'	, $description	, PDO::PARAM_STR);
			$query->bindParam(':status'		, $status		, PDO::PARAM_STR);
	
			$query->execute();
	
			return true;
		} catch (PDOException $e) {
			// echo $e;
			return false;
		}
	}
	
}

?>