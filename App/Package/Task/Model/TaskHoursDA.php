<?php
namespace Task;
class TaskHoursDA
{
	private $database = FALSE;

	public function setDatabase($database)
	{
		$this->database = $database;
	}

	/**
	 * Accepts a TaskHours object to be passed in instead of sending the data individually.
	 * 
	 * Extracts the data from the object and calls the addHours() function with the correct data.
	 * 
	 * @param TaskHours $tempTask
	 */
	function addHoursFromObject($tempTask)
	{
		$taskId = $tempTask->getTask();
		$taskId = $taskId['id'];
		$memberId = $tempTask->getMember();
		$memberId = $memberId['id'];
		return $this->addHours($taskId, $memberId, $tempTask->getDate(), $tempTask->getHours());
	}
	
	/**
	 * Accepts a TaskHours object to be passed in instead of sending the data individually.
	 * 
	 * Extracts the data from the object and calls the wipeHours() function with the correct data.
	 *
	 * @param TaskHours $tempTask
	 */
	function wipeHoursFromObject($tempTask)
	{
		$taskId = $tempTask->getTask();
		$taskId = $taskId['id'];
		$memberId = $tempTask->getMember();
		$memberId = $memberId['id'];
		return $this->wipeHours($taskId, $memberId, $tempTask->getDate());
	}

	/**
	 * Adds hours for a member into the database
	 *
	 * @param int $taskId
	 * @param int $memberId
	 * @param date $workedDate
	 * @param int $workedHours
	 */
	function addHours($taskId, $memberId, $workedDate, $workedHours)
	{
		try {

			$statement = 'INSERT INTO `work`
					(taskId, memberId, hours, date)
					VALUES
					(:taskId, :memberId, :hours, :date)
					ON DUPLICATE KEY UPDATE hours = LEAST(GREATEST((hours + :hours), 0), 24)';

			$query = $this->database->prepare($statement);

			$workedDate = date("Y-m-d", strtotime($workedDate));

			$query->bindParam(':taskId'   , $taskId , \PDO::PARAM_INT);
			$query->bindParam(':memberId'   , $memberId , \PDO::PARAM_INT);
			$query->bindParam(':hours'   , $workedHours , \PDO::PARAM_INT);
			$query->bindParam(':date'   , $workedDate , \PDO::PARAM_STR);

			if($query->execute())
			{
				return array('success' => true);
			}else
			{
				return createError("Something went wrong while adding the hours into the database");
			}


		} catch (\PDOException $e) {
			return createError($e);
		}
	}
	
	/**
	 * Set's the hours for a specific date for a member to 0
	 *
	 * @param int $taskId
	 * @param int $memberId
	 * @param date $workedDate
	 * @param int $workedHours
	 */
	function wipeHours($taskId, $memberId, $workedDate)
	{
		try {
	
			$statement = 'INSERT INTO `work`
					(taskId, memberId, hours, date)
					VALUES
					(:taskId, :memberId, 0, :date)
					ON DUPLICATE KEY UPDATE hours = 0';
	
			$query = $this->database->prepare($statement);
	
			$workedDate = date("Y-m-d", strtotime($workedDate));
	
			$query->bindParam(':taskId'   , $taskId , \PDO::PARAM_INT);
			$query->bindParam(':memberId'   , $memberId , \PDO::PARAM_INT);
			$query->bindParam(':date'   , $workedDate , \PDO::PARAM_STR);
	
			if($query->execute())
			{
				return array('success' => true);
			}else
			{
				return createError("Something went wrong while adding the hours into the database");
			}
	
	
		} catch (\PDOException $e) {
			return createError($e);
		}
	}

	public function loadHours($taskId, $memberId, $startDate, $endDate)
	{
		/*
		 * Print values
		*

		echo "taskId = $taskId<br/>";
		echo "memeberId = $memberId<br/>";
		echo "startDate = $startDate<br/>";
		echo "endDate = $endDate<br/>";

		/* END Print values */

		try {
			$statement = "SELECT * FROM `work` workTable
					INNER JOIN `task` taskTable ON workTable.taskId = taskTable.taskId
					INNER JOIN `member` memberTable ON workTable.memberId = memberTable.memberId 
					WHERE workTable.hours > 0 ";

			if($taskId || $memberId || $startDate || $endDate)
			{
				$statement .= " AND";
				$needAndWhere = false;
				if($taskId)
				{
					$statement .= " `taskId` = :taskId";
					$needAndWhere = true;
				}
					
				if($memberId)
				{
					if($needAndWhere)
					{
						$statement .= " AND";
					}
					$needAndWhere = true;
					$statement .= " `memberId` = :memberId";
				}

				if($startDate)
				{
					if($needAndWhere)
					{
						$statement .= " AND";
					}
					$needAndWhere = true;
					$statement .= " `date` >= STR_TO_DATE(:startDate, '%Y-%m-%d')";
				}

				if($startDate)
				{
					if($needAndWhere)
					{
						$statement .= " AND";
					}
					$needAndWhere = true;
					$statement .= " `date` <= STR_TO_DATE(:endDate, '%Y-%m-%d')";
				}
			}
			$query = $this->database->prepare($statement);

			if($taskId)
			{
				$query->bindParam(':taskId'   , $taskId , \PDO::PARAM_INT);
			}
			if($memberId)
			{
				$query->bindParam(':memberId'   , $memberId , \PDO::PARAM_INT);
			}
			if($startDate)
			{
				$query->bindParam(':startDate'   , $startDate , \PDO::PARAM_STR);
			}
			if($endDate)
			{
				$query->bindParam(':endDate'   , $endDate , \PDO::PARAM_STR);
			}
			$query->execute();

			$timesheetHolder = array();
			$timesheetHolder['success'] = true;
			$timesheetHolder['data'] = array();


			$htmlString = "";
			foreach ($query as $row) {
				$tempTimeSheet = new TaskHours();
				$tempTimeSheet->buildFromQueryRow($row);
				array_push($timesheetHolder['data'], $tempTimeSheet);
			}

			// echo json_encode($commentHolder);
			return $timesheetHolder;
		} catch (PDOException $e) {
			return createError($e);
		}
	}//end loadComments

	public function getMemberContributionToTask($taskId)
	{
		try {
			$statement = "SELECT SUM(workTable.hours) as 'workedHours', memberTable.firstName, memberTable.lastName FROM `work` workTable
					INNER JOIN `task` taskTable ON workTable.taskId = taskTable.taskId
					INNER JOIN `member` memberTable ON workTable.memberId = memberTable.memberId
					WHERE taskTable.taskId = :taskId
					GROUP BY memberTable.memberId";


			$query = $this->database->prepare($statement);

			$query->bindParam(':taskId'   , $taskId , \PDO::PARAM_INT);

			$query->execute();

			$hoursContributionHolder = array();
			$hoursContributionHolder['success'] = true;
			$hoursContributionHolder['data'] = array();

			foreach ($query as $row) {
				$hoursContributionHolder['data'][htmlentities($row['firstName'])] = $row['workedHours'];
			}

			// echo json_encode($commentHolder);
			return $hoursContributionHolder;
		} catch (PDOException $e) {
			return createError($e);
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
		$errorMessage['error']['location'] = "TaskHoursDA";
		$errorMessage['error']['message'] = $comment;
		return $errorMessage;
	}
}


?>