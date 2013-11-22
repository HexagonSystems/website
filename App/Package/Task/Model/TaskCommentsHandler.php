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
	 * @param int $taskId
	 * @param int $memberId
	 * @param String $tag
	 * @param String $title
	 * @param String $content
	 * @return boolean
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
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
	 * Loads the a certain amount of comments for the specified Task
	 *
	 * @param int $taskId		The Task's ID
	 * @param int $memberId		The Member's ID who is requesting this information
	 * @param int $pageNum		The page number you would like to load
	 * @param int $qty			The quantity of comments to load
	 * @return Ambigous <boolean, multitype:>
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	public function loadComments($taskId, $memberId, $pageNum, $qty)
	{
		$temp = $this->taskCommentDA->loadComments($taskId, $memberId, $pageNum, $qty);
		return $temp;
	}

	/**
	 * Contacts the TaskCommentDA to get the newest comments
	 *
	 * @param int $taskId		The Task's ID
	 * @param int $memberId		The Member's ID who is requesting this information
	 * @param Date $lastLoaded	The date of the last loaded comment
	 * @param int $qty			The quantity of comments to load
	 * @return Ambigous <string, \Task\multitype:multitype:, multitype:multitype: boolean >
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	public function loadNewestComments($taskId, $memberId, $lastLoaded, $qty)
	{
		return $this->taskCommentDA->loadNewestComments($taskId, $memberId, $lastLoaded, $qty);
	}

	/**
	 * Returns how many comments exist for a certain Task
	 * 
	 * @param int $taskId		The Task's ID
	 * @param int $memberId		The Member's ID who is requesting this information
	 * @return Ambigous <string, \Task\multitype:multitype:, multitype:multitype: boolean >
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	public function getCommentCount($taskId, $memberId)
	{
		return $this->taskCommentDA->getCommentCount($taskId);
	}


	/**
	 * Adds hours for a member into the database
	 *
	 * @param int $taskId		The Task's ID the member worked for
	 * @param int $memberId		The Member's ID
	 * @param Date $workedDate	The date the member worked
	 * @param int $workedHours	How many hours the member worked
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
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
	
	/**
	 * Validates the given  integer fits inside the min and max amount provided
	 * 
	 * @param int $testParam
	 * @param int $minAmount
	 * @param int $maxAmount
	 * @return boolean
	 * @deprecated In the process of being phases out as this method doesn't really fit inside TaskCommentHandler well
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	private function validateInteger($testParam, $minAmount, $maxAmount)
	{
		if($testParam < $minAmount && $minAmount != null)
		{
			$this->failReason = "Integer too small";
			return false;
		}
		if($testParam > $maxAmount && $maxAmount != null)
		{
			$this->failReason = "Integer too large";
			return false;
		}

		return true;
	}
	
	/**
	 * Validates the given String's length fits inside the min and max amount provided
	 * 
	 * @param unknown $testParam
	 * @param unknown $minLength
	 * @param unknown $maxLength
	 * @return boolean
	 * @deprecated In the process of being phases out as this method doesn't really fit inside TaskCommentHandler well
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	private function validateString($testParam, $minLength, $maxLength)
	{
		if($testParam < $minLength && $minLength != null)
		{
			$this->failReason = "String too small";
			return false;
		}
		if($testParam > $maxLength && $maxLength != null)
		{
			$this->failReason = "String too large";
			return false;
		}
	}

	/**
	 * Creates an array that holds information about the error
	 *
	 * @return string
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
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