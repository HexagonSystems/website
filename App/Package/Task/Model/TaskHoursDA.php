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
		return $this->addHours($tempTask->getTaskId(), $tempTask->getMemberId(), $tempTask->getDate(), $tempTask->getHours());
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
				
			$workedDate = date("Y-d-m", strtotime($workedDate));
	
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