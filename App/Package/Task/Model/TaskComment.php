<?php
namespace Task;
/**
 * Task Comment
 * 
 * Houses all of the data for Task Comments.
 * 
 * @author Alex Robinson <alex-robinson@live.com>
 */
class TaskComment
{
	private $commentArray = array();
	private $errorArray = array();
	
	/* SETS */
	public function setId($id)
	{
		$this->commentArray['id'] = $id;	
	}
	
	public function setTaskId($taskId)
	{
		$this->commentArray['taskId'] = $taskId;
	}
	
	public function setParentTask($taskArray)
	{
		$this->commentArray['task'] = $taskArray;
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
	
	/* GETS */
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
	
	/**
	 * Returns the array all of the Task's data is stored in
	 *
	 * @return array
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	public function toArray()
	{
		return $this->commentArray;
	}
	
	/**
	 * Was originally made to check if the TaskComment was valid, however was never implemented and isn't a neccessary
	 * feature at the moment.
	 * 
	 * @return boolean
	 * @deprecated
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
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
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	public function buildFromQueryRow($row)
	{
		if(isset($row['name']))
		{
			$tempArray = array(
					'id'	=> $row['taskId'],
					'value'	=> $row['name']
			);
			$this->setParentTask($tempArray);
		}else
		{
			$this->setTaskId($row['taskId']);
		}
		$this->setTag($row['tag']);
		$this->setTitle($row['title']);
		$this->setContent($row['content']);
		/* SEE COMMENT IN THE SAME METHOD FOR TASK TO SEE WHY THIS WAS DONE */
		$this->commentArray['member'] = $row['firstName'];
		$this->setDate($row['postedDate']);
	}
}

?>