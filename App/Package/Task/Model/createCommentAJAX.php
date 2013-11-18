<?php
namespace Task;

echo "working";
if(!isset($_POST['content']))
{
	return "not set";
	die();
}else
{
	createComment($_POST['taskId'], $_POST['memberId'], $_POST['tag'], $_POST['content']);
}

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

		return "all good";
	} catch (PDOException $e) {
		echo $e;

		return "fail";
	}
}

?>