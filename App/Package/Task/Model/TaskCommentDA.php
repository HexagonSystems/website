<?php
namespace Task;
class TaskCommentDA
{
	private $database = FALSE;
	
	public function setDatabase($database)
	{
		$this->database = $database;
	}
	
	/**
	 * Creates a comment in the database
	 *
	 * @param int $taskId
	 * @param int $memberId
	 * @param string $tag
	 * @param string $title
	 * @param string $content
	 */
	function createComment($taskId, $memberId, $tag, $title, $content, $time)
	{
		try {
	
			$statement = 'INSERT INTO `taskComment`
					(taskId, memberId, title, content, tag, postedDate)
					VALUES
					(:taskId, :memberId, :title, :content, :tag, :postedDate)';
	
			$query = $this->database->prepare($statement);
	
			$query->bindParam(':taskId'   , $taskId , \PDO::PARAM_INT);
			$query->bindParam(':memberId'   , $memberId , \PDO::PARAM_INT);
			$query->bindParam(':title'   , $title , \PDO::PARAM_STR);
			$query->bindParam(':content'   , $content , \PDO::PARAM_STR);
			$query->bindParam(':tag'   , $tag , \PDO::PARAM_STR);
			$query->bindParam(':postedDate'   , $time , \PDO::PARAM_INT);
			/* MIGHT NEED TO GET AUTO INCREMENT COMMENT ID HERE */
			$query->execute();
			return array('success' => true);
		} catch (PDOException $e) {
			return createError($e);
		}
	}
	
	/*
	 * TEMP FUNCTION, PLEASE FIX AT A LATER DATE
	 */
	public function createCommentFromObject($tempTaskComment)
	{
		return $this->createComment($tempTaskComment->getTaskId(), $tempTaskComment->getMemberId(), $tempTaskComment->getTag(), $tempTaskComment->getTitle(), $tempTaskComment->getContent(), $tempTaskComment->getDate());
	}
	
	/**
	 * Loads an existing post from the database
	 *
	 * @param int $taskId the id number of the post
	 * @param int $memberId
	 * @param int $pageNum
	 * @param int $qty
	 *
	 * @return boolean True for loaded false for DB connection error
	 */
	public function loadComments($taskId, $memberId, $pageNum, $qty)
	{
		try {
			$statement = "SELECT comment.*, member.firstName FROM `taskComment` comment 
					INNER JOIN `member` member ON comment.memberId = member.memberId 
					WHERE comment.taskId = :taskId 
					ORDER BY comment.postedDate DESC 
					LIMIT :starting, :quantity";
	
			$query = $this->database->prepare($statement);
	
			$query->bindParam(':taskId'   , $taskId , \PDO::PARAM_INT);
				
			$starting = ($pageNum - 1) * $qty;
			
			$qty = $qty + 0;
			
			$query->bindParam(':starting'   , $starting , \PDO::PARAM_INT);
			$query->bindParam(':quantity'   , $qty		, \PDO::PARAM_INT);
	
			if(!$query->execute())
			{
				return $this->createError("SQL had trouble executing ");
			}
				
			$commentHolder = array();
			$commentHolder['success'] = true;
			$commentHolder['data'] = array();
			
				
			$htmlString = "";
			foreach ($query as $row) {
				$tempTaskComment = new TaskComment();
				$tempTaskComment->setId($row['commentId']);
				$tempTaskComment->setTag($row['tag']);
				$tempTaskComment->setTitle($row['title']);
				$tempTaskComment->setContent($row['content']);
				$tempTaskComment->setMemberId($row['firstName']);
				$tempTaskComment->setDate($row['postedDate']);
	
				array_push($commentHolder['data'], $tempTaskComment);
			}
				
			// echo json_encode($commentHolder);
			return $commentHolder;
		} catch (PDOException $e) {
			return createError($e);
		}
	}//end loadComments
	
	/*
	 * THIS IS A TEMP FUNCTION, PLEASE FIX UP LATER
	 */
	function addHoursShort($tempTask)
	{
		return $this->addHours($tempTask->getTaskId(), $tempTask->getMemberId(), $tempTask->getDate(), $tempTask->getHours());
	}
	
	/**
	 * Adds hours for a member into the database
	 *
	 * @param int $taskId
	 * @param int $memberId
	 * @param date $workedDate
	 * @param int $workedHours
	 */
	function addHours($taskId, $memberId, $workedDate, $workedHours)
	{
		try {
				
			$statement = 'INSERT INTO `work`
					(taskId, memberId, hours, date)
					VALUES
					(:taskId, :memberId, :hours, :date)
					ON DUPLICATE KEY UPDATE hours = hours + :hours';
	
			$query = DataBase::getConnection()->prepare($statement);
				
			$workedDate = date("Y-d-m", strtotime($workedDate));
	
			$query->bindParam(':taskId'   , $taskId , \PDO::PARAM_INT);
			$query->bindParam(':memberId'   , $memberId , \PDO::PARAM_INT);
			$query->bindParam(':hours'   , $workedHours , \PDO::PARAM_INT);
			$query->bindParam(':date'   , $workedDate , \PDO::PARAM_STR);
	
			if($query->execute())
			{
				return array('success' => true);
			}else
			{
				return array('success' => false);
			}
	
				
		} catch (PDOException $e) {
			return array('success' => false);
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
		$errorMessage['error']['location'] = "TaskCommentDA";
		$errorMessage['error']['message'] = $comment;
		return $errorMessage;
	}
}


?>