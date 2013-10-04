<?php
class TaskCommentsHandler
{
	function createComment($taskId, $memberId, $tag, $content)
	{
		try {

			$statement = 'INSERT INTO `taskComment`
					(taskId, memberId, content, tag)
					VALUES
					(:taskId, :memberId, :content, :tag)';

			$query = DataBase::getConnection()->prepare($statement);

			$query->bindParam(':taskId'   , $taskId , PDO::PARAM_INT);
			$query->bindParam(':memberId'   , $memberId , PDO::PARAM_INT);
			$query->bindParam(':content'   , $content , PDO::PARAM_STR);
			$query->bindParam(':tag'   , $tag , PDO::PARAM_STR);

			$query->execute();

			echo "true";
		} catch (PDOException $e) {
			echo $e;
		}
	}
	
	/**
	 * Loads an existing post from the database
	 *
	 * @param Int $postId the id number of the post
	 *
	 * @return Boolean   True for loaded false for DB connection error
	 */
	public function loadComments($taskId, $memberId, $pageNum, $qty)
	{
		try {
			$statement = "SELECT * FROM `taskComment`
						WHERE `taskId` = :taskId
						ORDER BY `postedDate` DESC
						LIMIT :starting, 5";
	
			$query = DataBase::getConnection()->prepare($statement);
				
			$query->bindParam(':taskId'   , $taskId , PDO::PARAM_INT);
			
			$starting = ($pageNum - 1) * $qty;
			
			$query->bindParam(':starting'   , $starting , PDO::PARAM_INT);
	
			$query->execute();
			
			$commentHolder = array();
			
			$htmlString = "";
			foreach ($query as $row) {
				$tempTask = array();
				$tempTask['id'] = $row['commentId'];
				$tempTask['tag'] = $row['tag'];
				$tempTask['content'] = $row['content'];
				$tempTask['memberId'] = $row['memberId'];
				$tempTask['date'] = $row['postedDate'];
				
				array_push($commentHolder, $tempTask);
				/*
				echo '<tr>';
				echo '<td><a href="#">'.$tempTask["tag"].'</a></td>';
				echo '<td>'.$tempTask["content"].'</td>';
				echo '<td>'.$tempTask["memberId"].'</td>';
				echo '<td>'.$tempTask["date"].'</td>';
				echo '</tr>';
				*/
			}
			
			echo json_encode($commentHolder);
		} catch (PDOException $e) {
			echo $e;
		}
	}//end loadPost
	
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
				echo "true";
			}else
			{
				echo "Something failed";
			}
	
			
		} catch (PDOException $e) {
			echo $e;
		}
	}
}
?>