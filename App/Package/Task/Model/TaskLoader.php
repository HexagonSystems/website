<?php

class TaskLoader
{
	
	private $taskDA;
	
	function __construct()
	{
		$this->taskDA = new TaskDA();
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
	
	public function setDatabase($database)
	{
		$this->taskDA->setDatabase($database);
	}
	
}

?>