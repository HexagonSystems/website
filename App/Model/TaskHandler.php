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
	
	public function loadTasks($page)
	{
		$tempArray = $this->taskDA->loadTasks($page);
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
			$taskCommentHandler->addHours($returnValue, $memberId, $date, 0, "Task created");
		}
		return $returnValue;
	}
	
}

?>