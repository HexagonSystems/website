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
	
	public function setTask($task)
	{
		$this->taskHoursArray['task'] = $task;
	}
	
	public function setMember($member)
	{
		$this->taskHoursArray['member'] = $member;
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
	
	public function getTask()
	{
		return $this->taskHoursArray['task'];
	}
	
	public function getMember()
	{
		return $this->taskHoursArray['member'];
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
	
	public function buildFromQueryRow($tempArray)
	{
		
		//$this->setId($tempArray['workId']);
		$tempTaskArray = array(
				'id'	=> $tempArray['taskId'],
				'value'	=> $tempArray['name']
		);
		$this->setTask($tempTaskArray);
		$tempMemberArray = array(
				'id'	=> $tempArray['memberId'],
				'value'	=> $tempArray['firstName']
		);
		$this->setMember($tempMemberArray);
		$this->setHours($tempArray['hours']);
		$this->setDate($tempArray['date']);
	}
	
	public function isValid()
	{
		return true;
	}
}

?>