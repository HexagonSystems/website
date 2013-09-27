 <?php
/**
* user file
*
*@author Michael MacDonald
*@version 1.0
*@packpostDate
*/

class EditPageModel
{
    private $pageId;
    private $pageName;
    private $pageDescription;
    private $pageContent;
    private $pageVisibility;
    private $pageAuthor;
    private $pageDateEdited;

    /**
    *Constructor sets up initial values of object
    */
    public function __construct($postId, $postTitle, $postDate, $postBody, $postAuthor, $postComments, $postEdited)
    {
        $this->pageId = $pageId;
        $this->pageName = $pageName;
        $this->pageDescription = $pageDescription;
        $this->pageContent = $pageContent;
        $this->pageAuthor = $pageAuthor;
        $this->pageVisibility = $pageVisibility;
        $this->pageDateEdited = $pageDateEdited;
    }

    /**public function __destruct()
    {

    }

    public function __toString()
    {

    }

    *Sets the ID
    *@param string $id
    */
    //SETS//
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    public function setPageName($pageName)
    {
        $this->pageName = $pageName;
    }

    public function setPageDescription($pageDescription)
    {
        $this->pageDescription = $pageDescription;
    }

    public function setPageContent($pageContent)
    {
        $this->pageContent = $pageContent;
    }

    public function setPageAuthor($pageAuthor)
    {
        $this->pageAuthor = $pageAuthor;
    }

    public function setPageVisibility($pageVisibility)
    {
        $this->pageVisibility = $pageVisibility;
    }

    public function setPageDateEdited($pageDateEdited)
    {
        $this->pageDateEdited = $pageDateEdited;
    }
    
    //GETS//
    public function getPageId()
    {
        return $this->pageId;
    }

    public function getPageName()
    {
        return $this->pageName;
    }

    public function getPageDescription()
    {
        return $this->pageDescription;
    }

    public function getPageContent()
    {
        return $this->pageContent;
    }

    public function getPageVisibility()
    {
        return $this->pageVisibility;
    }

    public function getPageAuthor()
    {
        return $this->pageAuthor;
    }

    public function getPageDateEdited()
    {
        return $this->pageDateEdited;
    }

    /**
    * database connection as an instance field
    */
    private $conn;

    /**
    * Constructor creates the database connection and stores as an instance field
    * @params nil
    */

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
//end class
?>