<?php
/**
 * Contains a single Post entry for the blog
 * Features a helper method for building and returning Arrays of posts
 *
 * @author Stephen McMahon <stephentmcm@gmail.com>
 */

class Post extends Article
{

    public function getPosts($startPost, $endPost)
    {
        try {
            $statement = "SELECT `postid`, `title`, `displayStatus`, `content`, `creationDate`, `username` 
                    FROM `posts`
                    WHERE `displayStatus` = 'published'
                    ORDER BY  `creationDate` DESC 
                    LIMIT :startPost, :endPost";

            $query = $this->database->prepare($statement);

            $query->bindParam(':startPost'   , $startPost , PDO::PARAM_INT);
            $query->bindParam(':endPost'  	 , $endPost   , PDO::PARAM_INT);

            $query->execute();

            $arrayOfPosts = array();

            foreach ($query as $row) {
                $tempObject = new Post();

                $tempObject->setPostid($row['postid']);
                $tempObject->setTitle($row['title']);
                $tempObject->setStatus($row['displayStatus']);
                $tempObject->setContent($row['content']);
                $tempObject->setDate($row['creationDate']);
                $tempObject->setUsername($row['username']);

                $arrayOfPosts[$tempObject->getPostid()] = $tempObject;

            }

            //print_r($arrayOfPosts);
            return $arrayOfPosts;
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
            $statement = "SELECT * FROM `posts` WHERE `postid` = '$id'";

            $post = $this->database->query($statement)->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {

            throw new Exception('Database error:', 0, $e);

            return(false);
        };

        $this->article = $post;

        return(true);
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

    //*********GETTERS--------------
    public function getPost()
    {
        return($this->article);
    }

    public function getPostid()
    {
        return($this->article['postid']);
    }

    public function getTitle()
    {
        return($this->article['title']);
    }

}//end post class
