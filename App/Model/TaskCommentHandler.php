<?php
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
		$tempTaskComment = new TaskComment();
		$tempTaskComment->setId($taskId);
		$tempTaskComment->setMemberId($memberId);
		$tempTaskComment->setTag($tag);
		$tempTaskComment->setTitle($title);
		$tempTaskComment->setContent($content);
		
		$validation = $tempTaskComment->isValid();
		if($validation === false)
		{
			$this->failReason = $validation;
			return false;
		}
		return $this->taskCommentDA->createComment($taskId, $memberId, $tag, $title, $content);
	}

	/**
	 * Loads the task comments
	 *
	 * @param unknown $taskId
	 * @param unknown $memberId
	 * @param unknown $pageNum
	 * @param unknown $qty
	 * @return Ambigous <boolean, multitype:>
	 */
	public function loadComments($taskId, $memberId, $pageNum, $qty)
	{
		$temp = $this->taskCommentDA->loadComments($taskId, $memberId, $pageNum, $qty);
		return $temp;
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
		$addHoursResponse= $this->taskCommentDA->addHours($taskId, $memberId, $workedDate, $workedHours);
		if($addHoursResponse === true)
		{
			$createCommentResponse = $this->createComment($taskId, $memberId, "@addedHours", "Alex has added ".$workedHours." for the date ".$workedDate, $workedComment);
		}else
		{
			$this->failReason = "Error adding hours";
			return false;
		}
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
	 * Returns the last item that was tested before erroring
	 *
	 * @return number
	 */
	private function returnError()
	{
		return $this->failReason;
	}
}
?>