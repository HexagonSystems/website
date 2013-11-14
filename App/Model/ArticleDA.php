<?php
/**
*	
*
*@ author:	Sam Imeneo
*@author:	Alex Robinson
**/
class ArticleDA
{
	private $database = FALSE;
	
	public function setDatabase($database)
	{
		$this->database = $database;
	}
	/**
	*@param		
	*@returns	$row - contains an array of team members with their bio
	*			$e	 - if there is a PDO Exception then $e will be returned with the error msg
	*
	**/
	public function getAllMemberBios()
	{
		try
		{	
			$sql = "SELECT a.title, a.content, m.memberId, m.firstName, m.lastName FROM article a 
					LEFT JOIN memberarticle ma ON a.articleId = ma.articleId 
					LEFT JOIN member m ON ma.memberId = m.memberId 
					WHERE a.category = '1';";
			$result = $this->database->prepare($sql);
			$result->execute();
			$resultset = $result->fetchAll();
			return $resultset;
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}
}
?>