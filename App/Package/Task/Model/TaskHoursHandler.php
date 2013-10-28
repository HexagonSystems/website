<?php
namespace Task;
class TaskHoursHandler
{
	private $taskHoursDA;
	private $databaseHolder;
	private $currentTest;
	private $failReason;

	/**
	 * Constructor
	*/
	function __construct()
	{
		$this->taskHoursDA = new TaskHoursDA();
		$this->taskCommentsHandler = new TaskCommentsHandler();
	}

	/**
	 * Sets the database PDO object
	 *
	 * @param unknown $database
	 */
	public function setDatabase($database)
	{
		$this->taskHoursDA->setDatabase($database);
		$this->databaseHolder = $database;
	}
	
	public function loadHours($taskId, $memberId, $startDate, $endDate)
	{
		return $this->taskHoursDA->loadHours($taskId, $memberId, $startDate, $endDate);
	}

	/**
	 * Adds hours for a member into the database
	 *
	 * @param int $taskId
	 * @param int $memberId
	 * @param unknown $workedDate
	 * @param int $workedHours
	 */
	function addHours($taskId, $memberId, $memberName, $workedDate, $workedHours, $workedComment)
	{
		$masterResponse = array();
		
		/* CREATE HOURS OBJECT */
		$tempHours = new TaskHours();
		$tempHours->setTask(array('id' => $taskId));
		$tempHours->setMember(array('id' => $memberId));
		$tempHours->setDate($workedDate);
		$tempHours->setHours($workedHours);
		
		$hoursDAReturn = $this->taskHoursDA->addHoursFromObject($tempHours);
		
		if($hoursDAReturn['success'] === true)
		{
			/* ADD TASK TO MASTER RESPONSE */
			$masterResponse['hours']['success'] = true;
			$masterResponse['hours']['data'] = $tempHours;
			
			/* STRUCTURING DATA TO GO INTO CREATE TASK COMMENT */
			$tempTaskCommentTag = "addedHours";
			$tempTaskCommentTitle = "I have added ".$workedHours." hours for the date ".$workedDate;
			
			/* CREATE THE COMMENT IN THE DATABASE */
			$taskCommentsHandler = new TaskCommentsHandler();
			$taskCommentsHandler->setDatabase($this->databaseHolder);
			$commentHandlerResponse = $taskCommentsHandler->createComment($taskId, $memberId, $tempTaskCommentTag, $tempTaskCommentTitle, $workedComment);
			
			if($commentHandlerResponse['success'] == false)
			{
				return $commentHandlerResponse;
			}else
			{
				$masterResponse['comment'] = $commentHandlerResponse;
				$masterResponse['success'] = true;
				return $masterResponse;
			}
		}else
		{
			return $hoursDAReturn;
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
		$errorMessage['error']['location'] = "TaskHoursHandler";
		$errorMessage['error']['message'] = $comment;
		return $errorMessage;
	}
}
?>