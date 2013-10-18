<?php
namespace Task;
class TaskHandler
{
	
	private $taskDA;
	private $databaseHolder;
	private $currentTest;
	private $failReason;
	
	function __construct()
	{
		$this->taskDA = new TaskDA();
	}
	
	public function setDatabase($database)
	{
		$this->taskDA->setDatabase($database);
		$this->databaseHolder = $database;
	}
	
	public function loadTasks($page, $quantity)
	{
		if($page > 0)
		{
			if($quantity > 0)
			{
				$tempArray = $this->taskDA->loadTasks($page, $quantity);
				return $tempArray;
			}else
			{
				return createError("Quantity must be more than 0");
			}
		}else
		{
			return createError("Page must be at least 1");
		}
		
	}
	
	public function loadTask($id)
	{
		return $this->taskDA->loadTask($id);
	}
	
	public function createTask($title, $description, $memberId, $status)
	{
		/* CREATE TASK OBJECT WITH CURRENT DATA */
		$tempTask = new Task();
		$tempTask->setDatabase($this->databaseHolder);
		$tempTask->setTitle($title);
		$tempTask->setContent($description);
		$tempTask->setStatus($status);
		
		/* ENTER THE TASK INTO THE DATABASE */
		$taskDAReturn = $this->taskDA->createTaskFromObject($tempTask);
		
		if($taskDAReturn['success'] === true)
		{
			/* UPDATE TEMP TASK WITH ID THAT WAS JUST GENERATED  VIA AUTO INCREMENT IN DATABASE */
			$tempTask->setId($taskDAReturn['taskId']);
			$tempTask->loadMembers(); // This is a very lazy way of doing this
			
			/* ADD TASK TO MASTER ARRAY */
			$masterArray['task']['success'] = true;
			$masterArray['task']['data'] = $tempTask;
			
			/* GET CURRENT TIME FOR THE HOURS OBJECT */
			date_default_timezone_set('Australia/Melbourne');
			$date = date('Y/m/d', time());
			
			/* ADD HOURS INTO DATABASE */
			$taskHoursHandler = new TaskHoursHandler();
			$taskHoursHandler->setDatabase($this->databaseHolder);
			$taskHoursHandlerResponse = $taskHoursHandler->addHours($taskDAReturn['taskId'], $memberId, $date, 0, "Task created");
			
			if($taskHoursHandlerResponse['success'] == true)
			{
				$masterArray['hours'] = $taskHoursHandlerResponse['hours'];
				$masterArray['comment'] = $taskHoursHandlerResponse['comment'];
				$masterArray['success'] = true;
				return $masterArray;
			}else
			{
				return $taskHoursHandlerResponse;
			}
		}else
		{
			return $taskDAReturn;
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
		$errorMessage['error']['location'] = "TaskHandler";
		$errorMessage['error']['message'] = $comment;
		return $errorMessage;
	}
	
}

?>