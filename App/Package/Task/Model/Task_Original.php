<?php
namespace Task;
/**
 * TODO Class description
 *
 * @author Alex Robinson
 */

class Task extends ArticleEntity
{

	public function loadTasks($page)
	{
		try {
			$statement = "SELECT *
					FROM `task`";

			$query = $this->database->prepare($statement);

			$query->execute();

			$arrayOfPosts = array();

			foreach ($query as $row) {
				$tempObject = new Task();

				$tempObject->setPostid($row['taskId']);
				$tempObject->setTitle($row['name']);
				$tempObject->setTimeStamp($row['entryDate']);
				$tempObject->setStatus($row['status']);
				$tempObject->setContent($row['details']);
				$tempObject->setCategory($row['type']);
				$tempObject->setMembers($this->loadMembers($row['taskId']));
				$tempObject->setUpdates($this->loadUpdates($row['taskId'], 1));
				$arrayOfPosts[$tempObject->getId()] = $tempObject;
			}

			//print_r($arrayOfPosts);
			return $arrayOfPosts;
		} catch (PDOException $e) {
			echo $e;

			return false;
		}
	}

	/**
	 * Loads the members of a specific task
	 *
	 * @param Int $id the id number of the task
	 *
	 * @return array Array of Member Ids
	 * @throws Exception PDO expection
	 */
	public function loadMembers($id)
	{
		try {

			$statement = 'SELECT DISTINCT wrk.memberId, mbr.firstName
					FROM work wrk, member mbr
					WHERE wrk.taskId = :taskId AND
					mbr.memberId = wrk.memberId';

			$query = $this->database->prepare($statement);

			$query->bindParam(':taskId'   , $id , PDO::PARAM_INT);
			 
			$query->execute();
			 
			$arrayOfMembers = array();
			 
			foreach ($query as $row) {
				$arrayOfMembers[$row['memberId']] = $row['firstName'];
			}
			 
			//print_r($arrayOfPosts);
			return $arrayOfMembers;
		} catch (PDOException $e) {
			echo $e;
			 
			return false;
		}
	}

	/**
	 * Loads the members of a specific task
	 *
	 * @param Int $id the id number of the task
	 *
	 * @return array Array of Member Ids
	 * @throws Exception PDO expection
	 */
	public function loadUpdates($id, $quantity = 5, $startFrom = 0)
	{
		try {

			$statement = 	"SELECT DISTINCT wrk.memberId, wrk.date, mbr.firstName
							FROM work wrk, member mbr
							WHERE wrk.taskId = :taskId
							AND mbr.memberId = wrk.memberId
							ORDER BY wrk.date LIMIT 0, 1";

			$query = $this->database->prepare($statement);

			$query->bindParam(':taskId'   , $id , PDO::PARAM_INT);
			 
			$query->execute();
			 
			$arrayParam = array();
			 
			foreach ($query as $row) {
				$tmpArray = array();
				$tmpArray['memberId'] = $row['memberId'];
				$tmpArray['firstName'] = $row['firstName'];
				$tmpArray['date'] = $row['date'];
				 
				array_push($arrayParam, $tmpArray);
			}
			 
			//print_r($arrayOfPosts);
			return $arrayParam;
		} catch (PDOException $e) {
			echo $e;
			 
			return false;
		}
	}

	/**
	 * Loads an existing post from the database
	 *
	 * @param Int $postId the id number of the post
	 *
	 * @return Boolean   True for loaded false for DB connection error
	 * @throws Exception PDO expection
	 */
	public function load($id)
	{

	try {
			$statement = "SELECT * FROM `task`
						WHERE `taskId` = :taskId";

			$query = $this->database->prepare($statement);
			
			$query->bindParam(':taskId'   , $id , PDO::PARAM_INT);

			$query->execute();
			
			$row = $query->fetch();
			if($row !== false)
			{
				$this->setPostid($row['taskId']);
				$this->setTitle($row['name']);
				$this->setTimeStamp($row['entryDate']);
				$this->setStatus($row['status']);
				$this->setContent($row['details']);
				$this->setCategory($row['type']);
				$this->setMembers($this->loadMembers($row['taskId']));
				return true;
			}else
			{
				return false;
			}
		} catch (PDOException $e) {
			echo $e;

			return false;
		}
	}//end loadPost
	
	/**
	 * Loads an existing post from the database
	 *
	 * @param Int $postId the id number of the post
	 *
	 * @return Boolean   True for loaded false for DB connection error
	 * @throws Exception PDO expection
	 */
	public function loadComments($id, $pageNumber)
	{
	
		try {
			$statement = "SELECT * FROM `taskComment`
						WHERE `taskId` = :taskId
						ORDER BY `postedDate` DESC
						LIMIT 0, 5";
	
			$query = $this->database->prepare($statement);
			
			$query->bindParam(':taskId'   , $id , PDO::PARAM_INT);
	
			$query->execute();
	
			$tmpMasterArray = array();
	
			foreach ($query as $row) {
				$tmpChildArray = array();
				$tmpChildArray['tag'] = $row['tag'];
				$tmpChildArray['content'] = $row['content'];
				$tmpChildArray['memberId'] = $row['memberId'];
				$tmpChildArray['date'] = $row['postedDate'];
				
				array_push($tmpMasterArray, $tmpChildArray);
			}
	
			//print_r($arrayOfPosts);
			return $tmpMasterArray;
		} catch (PDOException $e) {
			echo $e;
	
			return false;
		}
	}//end loadPost

	/**
	 * Stores the input data in the post object used for creating new post
	 *
	 * @param Array $post Must contain `title`, `status`, `ACL`, `content`,
	 * `username`
	 *
	 * @return Boolean   True on sucess else false
	 * @throws Exception Throws Database and custom errors
	 */
	public function create($post = array())
	{
		//'title' => '', 'displayStatus' => '', 'ACL' => '', 'content' => '', 'username' => ''
		if (empty($post)) {

			throw new Exception('Create requires an Array of values');

			return(false);
		};

		//The keys we require
		$keys = array('title', 'displayStatus', 'ACL', 'content', 'username');

		//Check if each key exists in the $post array we've recieved
		foreach ($keys as $key) {
			if (!array_key_exists($key, $post)) {
				throw new Exception('Create requires an value for "'.$key.'" received: '.  implode(", ", array_keys($post)));

				return(false);
			};
		};

		$this->setTitle($post['title']);

		$this->setStatus($post['displayStatus']);

		$this->setACL($post['ACL']);

		$this->setContent($post['content']);

		$this->setUsername($post['username']);

		try {

			$statement = "INSERT INTO `posts` ( `title`, `displayStatus`, `ACL`, `content`, `username`)
					VALUES ( :title, :displayStatus, :ACL, :content, :username)";

			$query = $this->database->prepare($statement);

			$query->execute($this->articleNamedParams());

		} catch (Exception $e) {
			throw new Exception('Database error:', 0, $e);

			return(false);
		};

		$this->setPostid($this->database->lastInsertId());

		$this->load($this->getPostid());
		//var_dump($this);
		return(true);
	}

	/**
	 * Uses the super cool on duplicate key update MySQL function to update an existing post
	 * @return Boolean   True on sucess else false
	 * @throws Exception PDO expections on Database errors
	 */
	public function save()
	{
		try {

			$statement = "INSERT INTO `posts` (`postid`, `title`, `displayStatus`, `ACL`, `content`, `creationDate`, `username`)
					VALUES (:postid, :title, :displayStatus, :ACL, :content, :creationDate, :username)
					ON DUPLICATE KEY UPDATE
					title=values(title), displayStatus=values(displayStatus), ACL=values(ACL),
					content=values(content), creationDate=values(creationDate), username=values(username) ";

			$query = $this->database->prepare($statement);

			$query->execute($this->articleNamedParams());

		} catch (Exception $e) {

			throw new Exception($e);

			return(false);
		};

		return(true);
	}//end save

	/**
	 * Deletes the current post from the database
	 *
	 * @return Boolean   Sucess or failure
	 * @throws Exception Database exceptions if query fails
	 */
	public function delete()
	{
		try {

			$statement = "UPDATE `tow`.`posts` SET `status` = 'deleted'
					WHERE `postid` = ?;";

			$query = $this->database->prepare($statement);

			$query->execute($this->getPostid());

		} catch (Exception $e) {
			throw new Exception('Database error:', 0, $e);

			return(false);
		};

		return(true);
	}

	//*********SETTERS----------------------
	public function setPostid($param)
	{
		$this->article['postid'] = $param;
	}

	public function setTitle($param)
	{
		$this->article['title'] = $param;
	}

	public function setMembers($param)
	{
		$this->article['members'] = $param;
	}

	public function setUpdates($param)
	{
		$this->article['updates'] = $param;
	}

	//*********GETTERS--------------
	public function getThis()
	{
		return($this->article);
	}

	public function getId()
	{
		return($this->article['postid']);
	}

	public function getTitle()
	{
		return($this->article['title']);
	}

	public function getMembers()
	{
		return($this->article['members']);
	}

	public function getUpdates()
	{
		return($this->article['updates']);
	}

	public function getUpdateAttribute($updatePlacement, $attribute)
	{
		if(array_key_exists($updatePlacement, $this->article['updates']))
		{
			$returnValue = ($this->article['updates'][$updatePlacement][$attribute]);
		}else {
			$returnValue = "null";
		}
		
		return $returnValue;
		
	}
	
	public function createComment($tag, $content)
	{
		try {
		
			$statement = 'INSERT INTO `taskComment`
							(taskId, memberId, content, tag)
							VALUES
							(:taskId, :memberId, :content, :tag)';
		
			$query = $this->database->prepare($statement);
		
			$currentId = $this->getId();
			$memberId = 1;
			$query->bindParam(':taskId'   , $currentId , PDO::PARAM_INT);
			$query->bindParam(':memberId'   , $memberId , PDO::PARAM_INT);
			$query->bindParam(':content'   , $content , PDO::PARAM_STR);
			$query->bindParam(':tag'   , $tag , PDO::PARAM_STR);
		
			$query->execute();
		
			return true;
		} catch (PDOException $e) {
			echo $e;
		
			return false;
		}
	}

}//end post class
