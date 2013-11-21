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
	
	/**
	 * Contacts the TaskHoursDA class and requests to load the hours for the given attributes
	 * 
	 * @param int $taskId
	 * @param int $memberId
	 * @param Date $startDate
	 * @param Date $endDate
	 * @return multitype:multitype: boolean
	 */
	public function loadHours($taskId, $memberId, $startDate, $endDate)
	{
		return $this->taskHoursDA->loadHours($taskId, $memberId, $startDate, $endDate);
	}

	/**
	 * Adds hours for a member into the database
	 *
	 * @param int $taskId
	 * @param int $memberId
	 * @param Date $workedDate
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
	 * Wipes the hour for a member on the given date.
	 * 
	 * After the hours have been wiped a TaskComment will be inserted into the database which should explain the wipe.
	 * 
	 * @param unknown $taskId
	 * @param unknown $memberId
	 * @param unknown $memberName
	 * @param unknown $workedDate
	 */
	function wipeHoursForDate($taskId, $memberId, $memberName, $workedDate, $workedComment)
	{
		$masterResponse = array();
	
		/* CREATE HOURS OBJECT */
		$tempDate = new TaskHours();
		$tempDate->setTask(array('id' => $taskId));
		$tempDate->setMember(array('id' => $memberId));
		$tempDate->setDate($workedDate);
	
		$hoursDAReturn = $this->taskHoursDA->wipeHoursFromObject($tempDate);
	
		if($hoursDAReturn['success'] === true)
		{
			/* ADD TASK TO MASTER RESPONSE */
			$masterResponse['hours']['success'] = true;
			$masterResponse['hours']['data'] = $tempDate;
				
			/* STRUCTURING DATA TO GO INTO CREATE TASK COMMENT */
			$tempTaskCommentTag = "wipedHours";
			$tempTaskCommentTitle = "I have wiped hours for the date ".$workedDate;
				
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
	 * Contacts the TaskHoursDA class, requests to get each member's
	 * contribution to a certain Task.
	 * 
	 * @param int $taskId
	 */
	public function getMembersContributionToTask($taskId)
	{
		return $this->taskHoursDA->getMemberContributionToTask($taskId);
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