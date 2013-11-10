<?php
namespace Task;
class TaskCommentsHandler
{
	private $taskCommentDA;
	private $currentTest;
	private $failReason;

	/**
	 * Constructor
	*/
	function __construct()
	{
		$this->taskCommentDA = new TaskCommentDA();
	}

	/**
	 * Sets the database PDO object
	 *
	 * @param unknown $database
	 */
	public function setDatabase($database)
	{
		$this->taskCommentDA->setDatabase($database);
	}

	/**
	 * Creates a comment in the database
	 *
	 * @param unknown $taskId
	 * @param unknown $memberId
	 * @param unknown $tag
	 * @param unknown $title
	 * @param unknown $content
	 * @return boolean
	 */
	function createComment($taskId, $memberId, $tag, $title, $content)
	{
		$masterResponse = array();
		
		/* CREATE THE TASK COMMENT OBJECT */
		$tempTaskComment = new TaskComment();
		$tempTaskComment->setTaskId($taskId);
		$tempTaskComment->setMemberId($memberId);
		$tempTaskComment->setTag($tag);
		$tempTaskComment->setTitle($title);
		$tempTaskComment->setContent($content);
		
		/* GET CURRENT TIME FOR THE TASK COMENT OBJECT */
		date_default_timezone_set('Australia/Melbourne');
		$date = date('Y-m-d H:i:s');
		$tempTaskComment->setDate($date);
		
		/* CHECK IF THE TASK COMMENT IS VALID */
		$validation = $tempTaskComment->isValid();
		if($validation === false)
		{
			return createError("TaskComment contained invalid data");
		}else
		{
			$taskCommentDAResponse = $this->taskCommentDA->createCommentFromObject($tempTaskComment);
			if($taskCommentDAResponse['success'] == true)
			{
				$masterResponse['success'] = true;
				$masterResponse['data'] = $tempTaskComment;
				return $masterResponse;
			}else
			{
				return $taskCommentDAResponse;
			}
		}
		
	}

	/**
	 * Loads the task comments
	 *
	 * @param int $taskId
	 * @param int $memberId
	 * @param int $pageNum
	 * @param int $qty
	 * @return Ambigous <boolean, multitype:>
	 */
	public function loadComments($taskId, $memberId, $pageNum, $qty)
	{
		$temp = $this->taskCommentDA->loadComments($taskId, $memberId, $pageNum, $qty);
		return $temp;
	}
	
	/**
	 * Contacts the TaskCommentDA to get the newest comments
	 * 
	 * @param unknown $taskId
	 * @param unknown $memberId
	 * @param unknown $lastLoaded
	 * @param unknown $qty
	 * @return Ambigous <string, \Task\multitype:multitype:, multitype:multitype: boolean >
	 */
	public function loadNewestComments($taskId, $memberId, $lastLoaded, $qty)
	{
		return $this->taskCommentDA->loadNewestComments($taskId, $memberId, $lastLoaded, $qty);
	}
	
	public function getCommentCount($taskId, $memberId)
	{
		return $this->taskCommentDA->getCommentCount($taskId);
	}
	

	/**
	 * Adds hours for a member into the database
	 *
	 * @param unknown $taskId
	 * @param unknown $memberId
	 * @param unknown $workedDate
	 * @param unknown $workedHours
	 */
	function addHours($taskId, $memberId, $workedDate, $workedHours, $workedComment)
	{
		$masterResponse = array();
		
		$tempHours = new TaskHours();
		$tempHours->setTaskId($taskId);
		$tempHours->setMemberId($memberId);
		$tempHours->setDate($workedDate);
		$tempHours->setHours($workedHours);
		$addHoursResponse = $this->taskCommentDA->addHoursShort($tempHours);
		
		if($addHoursResponse['success'] === true)
		{
			$masterResponse['hours']['success'] = true;
			$masterResponse['hours']['data'] = array();
			array_push($masterResponse['hours']['data'], $tempHours->toArray());
			
			/* CREATE THE TASK COMMENT */
			$tempTaskComment = new TaskComment();
			$tempTaskComment->setTag("@addedHours");
			$tempTaskComment->setDate($workedDate);
			$tempTaskComment->setTaskId($taskId);
			$tempTaskComment->setTitle("Alex has added ".$workedHours." hours for the date ".$workedDate);
			$tempTaskComment->setContent($workedContent);
			
			/* CREATE THE COMMENT IN THE DATABASE */
			$masterResponse['comment'] = $this->createComment($tempTaskComment);
		}else
		{
			$masterResponse['hours']['success'] = false;
			$this->failReason = "Error adding hours";
		}
		
		return $masterResponse;
	}

	private function validateInteger($testParam, $minAmount, $maxAmount)
	{
		if($testParam < $minAmount && $minAmount == null)
		{
			$this->failReason = "Integer too small";
			return false;
		}
		if($testParam > $maxAmount && $maxAmount == null)
		{
			$this->failReason = "Integer too large";
			return false;
		}

		return true;
	}

	private function validateString($testParam, $minLength, $maxLength)
	{
		if($testParam < $minLength && $minLength == null)
		{
			$this->failReason = "String too small";
			return false;
		}
		if($testParam > $maxLength && $maxLength == null)
		{
			$this->failReason = "String too large";
			return false;
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
		$errorMessage['error']['location'] = "TaskCommentHandler";
		$errorMessage['error']['message'] = $comment;
		return $errorMessage;
	}
}
?>