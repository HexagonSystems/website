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
			$statement = 'INSERT INTO `taskcomment`
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
			
			if($query->execute())
			{
				return array('success' => true);
			}else
			{
				return $this->createError($query->errorInfo());
			}
			
		} catch (PDOException $e) {
			return $this->createError($e);
		}
	}
	
	/**
	 * Extracts the required information from a TaskComment object and passes it through to the createComment() method
	 * 
	 * @param unknown $tempTaskComment
	 * @return Ambigous <multitype:boolean , string>
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	public function createCommentFromObject($tempTaskComment)
	{
		return $this->createComment($tempTaskComment->getTaskId(), $tempTaskComment->getMemberId(), $tempTaskComment->getTag(), $tempTaskComment->getTitle(), $tempTaskComment->getContent(), $tempTaskComment->getDate());
	}
	
	/**
	 * Loads an existing comment from the database
	 *
	 * @param int $taskId the id number of the post
	 * @param int $memberId
	 * @param int $pageNum
	 * @param int $qty
	 *
	 * @return boolean True for loaded false for DB connection error
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	public function loadComments($taskId, $memberId, $pageNum, $qty)
	{
		try {
			$statement = "SELECT comment.*, member.firstName FROM `taskcomment` comment 
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
				$tempTaskComment->setTag(htmlentities($row['tag']));
				$tempTaskComment->setTitle(htmlentities($row['title']));
				$tempTaskComment->setContent(htmlentities($row['content']));
				$tempTaskComment->setMemberId(htmlentities($row['firstName']));
				$tempTaskComment->setDate($row['postedDate']);
	
				array_push($commentHolder['data'], $tempTaskComment);
			}
				
			// echo json_encode($commentHolder);
			return $commentHolder;
		} catch (PDOException $e) {
			return $this->createError($e);
		}
	}//end loadComments
	
	/**
	 * Loads the newest comments for the specified Task from the database
	 * 
	 * @param int $taskId The Task's id you want to load the newest comments for
	 * @param int $memberId Not needed anymore
	 * @param Date lastLoaded The date of the last loaded comment
	 * 
	 * @return string|multitype:multitype: boolean
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	public function loadNewestComments($taskId, $memberId, $lastLoaded, $qty)
	{
		try {
			$statement = "SELECT comment.*, member.firstName FROM `taskcomment` comment
					INNER JOIN `member` member ON comment.memberId = member.memberId
					WHERE comment.taskId = :taskId
					AND comment.postedDate > :lastLoaded
					ORDER BY comment.postedDate DESC
					LIMIT 0, :quantity";
	
			$query = $this->database->prepare($statement);
	
			$query->bindParam(':taskId'	, $taskId , \PDO::PARAM_INT);
			$query->bindParam(':lastLoaded'	, $lastLoaded , \PDO::PARAM_STR);
				
			$qty = $qty + 0; // A silly way to make it an integer
				
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
				$tempTaskComment->setTag(htmlentities($row['tag']));
				$tempTaskComment->setTitle(htmlentities($row['title']));
				$tempTaskComment->setContent(htmlentities($row['content']));
				$tempTaskComment->setMemberId(htmlentities($row['firstName']));
				$tempTaskComment->setDate($row['postedDate']);
	
				array_push($commentHolder['data'], $tempTaskComment);
			}
	
			// echo json_encode($commentHolder);
			return $commentHolder;
		} catch (PDOException $e) {
			return $this->createError($e);
		}
	}//end loadComments
	
	/**
	 * Gets the amount of comments that are in the specified Task
	 * 
	 * @param int $taskId
	 * 
	 * @return string|multitype:multitype: boolean
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	public function getCommentCount($taskId)
	{
		try {
			$statement = "SELECT COUNT(*) FROM `taskcomment` WHERE `taskId` = :taskId";
	
			$query = $this->database->prepare($statement);
	
			$query->bindParam(':taskId'   , $taskId , \PDO::PARAM_INT);
			
			$commentCountHolder = array();
			$commentCountHolder['success'] = true;
			$commentCountHolder['data'] = array();
			
			if(!$query->execute())
			{
				return $this->createError("SQL had trouble executing");
			}else
			{
				$row = $query->fetch();
				if($row !== false)
				{
					array_push($commentCountHolder['data'], $row[0]);
				}else
				{
					return $this->createError("Unable to find Task requested");
				}
			}
			return $commentCountHolder;
		} catch (PDOException $e) {
			return $this->createError($e);
		}
	}//end loadComments
	
	/**
	 * Extracts the required information from the TaskHours object and passes it through to the AddHours() method.
	 * 
	 * @param int $tempTask
	 * @return multitype:boolean
	 * @deprecated This is now handled in the TaskHoursDA class
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
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
	 * @deprecated This is now handled in the TaskHoursDA class
	 *
	 * @author Alex Robinson <alex-robinson@live.com>
	 */
	function addHours($taskId, $memberId, $workedDate, $workedHours)
	{
		try {
				
			$statement = "INSERT INTO `work`
					(taskId, memberId, hours, date)
					VALUES
					(:taskId, :memberId, :hours, :date)
					ON DUPLICATE KEY UPDATE hours = hours + :hours";
	
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