<?php
namespace Task;
class TaskHours
{
	private $taskHoursArray = array();
	private $errorArray = array();
	
	public function setId($id)
	{
		$this->taskHoursArray['id'] = $id;	
	}
	
	public function setTaskId($taskId)
	{
		$this->taskHoursArray['taskId'] = $taskId;
	}
	
	public function setMemberId($memberId)
	{
		$this->taskHoursArray['memberId'] = $memberId;
	}
	
	public function setDate($date)
	{
		$this->taskHoursArray['date'] = $date;
	}
	
	public function setHours($hours)
	{
		$this->taskHoursArray['hours'] = $hours;
	}
	
	public function getId()
	{
		return $this->taskHoursArray['id'];
	}
	
	public function getTaskId()
	{
		return $this->taskHoursArray['taskId'];
	}
	
	public function getMemberId()
	{
		return $this->taskHoursArray['memberId'];
	}
	
	public function getDate()
	{
		return $this->taskHoursArray['date'];
	}
	
	public function getHours()
	{
		return $this->taskHoursArray['hours'];
	}
	
	public function toArray()
	{
		return $this->taskHoursArray;
	}
	
	public function isValid()
	{
		return true;
	}
}

?>