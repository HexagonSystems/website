<?php
namespace Task;
class TaskComment
{
	private $commentArray = array();
	private $errorArray = array();
	
	public function setId($id)
	{
		$this->commentArray['id'] = $id;	
	}
	
	public function setTaskId($taskId)
	{
		$this->commentArray['taskId'] = $taskId;
	}
	
	public function setTag($tag)
	{
		$this->commentArray['tag'] = $tag;
	}
	
	public function setTitle($title)
	{
		$this->commentArray['title'] = $title;
	}
	
	public function setContent($content)
	{
		$this->commentArray['content'] = $content;
	}
	
	public function setMemberId($memberId)
	{
		$this->commentArray['memberId'] = $memberId;
	}
	
	public function setDate($date)
	{
		$this->commentArray['date'] = $date;
	}
	
	public function getId()
	{
		return $this->commentArray['id'];
	}
	
	public function getTaskId()
	{
		return $this->commentArray['taskId'];
	}
	
	public function getTag()
	{
		return $this->commentArray['tag'];
	}
	
	public function getTitle()
	{
		return $this->commentArray['title'];
	}
	
	public function getContent()
	{
		return $this->commentArray['content'];
	}
	
	public function getMemberId()
	{
		return $this->commentArray['memberId'];
	}
	
	public function getDate()
	{
		return $this->commentArray['date'];
	}
	
	public function toArray()
	{
		return $this->commentArray;
	}
	
	public function isValid()
	{
		return true;
	}
	
	/**
	 * Sets up object using a row from a query
	 * 
	 * This is a hacked method, if anyone can come up with some better, please let me know.
	 * 
	 * @param array $row
	 */
	public function buildFromQueryRow($row)
	{
		$this->setTaskId($row['taskId']);
		$this->setTag($row['tag']);
		$this->setTitle($row['title']);
		$this->setContent($row['content']);
		$this->setMemberId($row['firstName']);
		$this->setDate($row['postedDate']);
	}
}

?>