<?php

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
	function createComment($taskId, $memberId, $tag, $title, $content)
	{
		try {
	
			$statement = 'INSERT INTO `taskComment`
					(taskId, memberId, title, content, tag)
					VALUES
					(:taskId, :memberId, :title, :content, :tag)';
	
			$query = DataBase::getConnection()->prepare($statement);
	
			$query->bindParam(':taskId'   , $taskId , PDO::PARAM_INT);
			$query->bindParam(':memberId'   , $memberId , PDO::PARAM_INT);
			$query->bindParam(':title'   , $title , PDO::PARAM_STR);
			$query->bindParam(':content'   , $content , PDO::PARAM_STR);
			$query->bindParam(':tag'   , $tag , PDO::PARAM_STR);
	
			$query->execute();
			
			return true;
		} catch (PDOException $e) {
			echo $e;
			return false;
		}
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
			$statement = "SELECT * FROM `taskComment`
						WHERE `taskId` = :taskId
						ORDER BY `postedDate` DESC
						LIMIT :starting, :quantity";
	
			$query = DataBase::getConnection()->prepare($statement);
	
			$query->bindParam(':taskId'   , $taskId , PDO::PARAM_INT);
				
			$starting = ($pageNum - 1) * $qty;
			
			$qty = $qty + 0;
			
			$query->bindParam(':starting'   , $starting , PDO::PARAM_INT);
			$query->bindParam(':quantity'   , $qty		, PDO::PARAM_INT);
	
			$query->execute();
				
			$commentHolder = array();
				
			$htmlString = "";
			foreach ($query as $row) {
				$tempTaskComment = new TaskComment();
				$tempTaskComment->setId($row['commentId']);
				$tempTaskComment->setTag($row['tag']);
				$tempTaskComment->setTitle($row['title']);
				$tempTaskComment->setContent($row['content']);
				$tempTaskComment->setMemberId($row['memberId']);
				$tempTaskComment->setDate($row['postedDate']);
	
				array_push($commentHolder, $tempTaskComment);
			}
				
			// echo json_encode($commentHolder);
			return $commentHolder;
		} catch (PDOException $e) {
			// echo $e;
			return false;
		}
	}//end loadComments
	
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
	
			$query->bindParam(':taskId'   , $taskId , PDO::PARAM_INT);
			$query->bindParam(':memberId'   , $memberId , PDO::PARAM_INT);
			$query->bindParam(':hours'   , $workedHours , PDO::PARAM_INT);
			$query->bindParam(':date'   , $workedDate , PDO::PARAM_STR);
	
			if($query->execute())
			{
				return true;
			}else
			{
				return false;
			}
	
				
		} catch (PDOException $e) {
			// echo $e;
			return false;
		}
	}
}


?>