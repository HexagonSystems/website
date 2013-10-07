<?php

class TaskHandler
{
	
	private $taskDA;
	private $currentTest;
	private $failReason;
	
	function __construct()
	{
		$this->taskDA = new TaskDA();
	}
	
	public function setDatabase($database)
	{
		$this->taskDA->setDatabase($database);
	}
	
	public function loadTasks($page, $quantity)
	{
		$tempArray = $this->taskDA->loadTasks($page, $quantity);
		return $tempArray;
	}
	
	public function loadTask($id)
	{
		return $this->taskDA->loadTask($id);
	}
	
	public function createTask($title, $description, $memberId, $status)
	{
		$returnValue = $this->taskDA->createTask($title, $description, $memberId, $status);
		if($returnValue !== false)
		{
			date_default_timezone_set('Australia/Melbourne');
			$date = date('Y/m/d', time());
			$taskCommentHandler = new TaskCommentsHandler();
			return $taskCommentHandler->addHours($returnValue, $memberId, $date, 0, "Task created");
		}else
		{
			$this->failReason = "Failed creating the task";
			return false;
		}
		
	}
	
	/**
	 * Returns the reason the last method errored
	 *
	 * @return string
	 */
	public function getError()
	{
		return $this->failReason;
	}
	
}

?>