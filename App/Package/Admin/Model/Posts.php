<?php 
include_once("model/Post.php");

class Posts {

	private $posts;
	/**
	* database connection as an instance field
	*/
	private $conn;
	
	/**
	* Constructor creates the database connection and stores as an instance field
	* @params nil
	*/
	public function __construct()
	{
		//get a databse connection
		//and store it as an instance field so we can use it in the functions
		$this->conn = DataBase::getConnection();
	}
	
	/**
	* Calls all the post details from the database
	* Joins the author name onto the author ID
	* @params nil
	*/
	public function getAllPostsByDate()
	{
		$sql = "SELECT posts.post_id, posts.post_title, posts.post_date, posts.post_body, posts.last_edited, posts.post_author, posts.post_comments, user.us_id, user.first_name, user.last_name FROM posts LEFT JOIN `user` ON posts.post_author = `user`.us_id ORDER BY post_date DESC";
		$resultSet = $this->conn->query($sql) or die("failed!");
		return $resultSet;
	}
	
	/**
	* Calls all the comment details from the database
	* where the comment post is equal to param
	* Joins the user name onto the post author ID
	* @params = post id
	*/
	public function getCommentsForAPost($postId)
	{
		$sql = "SELECT comments.comment_id, comments.comment_body, comments.comment_author, comments.comment_post, user.us_id, user.us_name FROM comments LEFT JOIN `user` ON comments.comment_author = user.us_id WHERE comments.comment_post = '$postId'";
		$resultSet = $this->conn->query($sql) or die("failed!");
		return $resultSet;
	}
}
?>