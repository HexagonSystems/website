<?php
namespace Task;
class TaskHoursDA
{
	private $database = FALSE;

	public function setDatabase($database)
	{
		$this->database = $database;
	}

	/*
	 * THIS IS A TEMP FUNCTION, PLEASE FIX UP LATER
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
					ON DUPLICATE KEY UPDATE hours = hours + :hours';

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
					INNER JOIN `member` memberTable ON workTable.memberId = memberTable.memberId ";

			if($taskId || $memberId || $startDate || $endDate)
			{
				$statement .= " WHERE";
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
				$hoursContributionHolder['data'][$row['firstName']] = $row['workedHours'];
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