<?php
/**
 * Contains a single Comment entry for the blog
 * Features a helper method for building and returning Arrays of comments
 *
 * @author Stephen McMahon <stephentmcm@gmail.com>
 */
class Comment extends Article
{
    /**
     * Loads an existing comment from the database
     *
     * @param Int $commentId the id number of the comment
     *
     * @return Boolean   True for loaded false for DB connection error
     * @throws Exception PDO expection
     */
    public function load($id)
    {

        try {
            $statement = "SELECT * FROM `comments` WHERE `commentid` = '$id'";

            $comment = $this->database->query($statement)->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {

            throw new Exception($e);

            return(false);
        };

        $this->article = $comment;

        return(true);
    }//end loadComment

    /**
     * Stores the input data in the comment object used for creating new comment
     *
     * @param Array $comment Must contain `title`, `status`, `ACL`, `content`,
     * `username`
     *
     * @return Boolean   True on sucess else false
     * @throws Exception Throws Database and custom errors
     */
    public function create($comment = array())
    {
        if (empty($comment)) {

            throw new Exception('Create requires an Array of values');

            return(false);
        };

        $keys = array('postid' ,'displayStatus', 'ACL', 'content', 'username');

        foreach ($keys as $key) {
            if (!array_key_exists($key, $comment)) {
                throw new Exception('Create requires an value for "'.$key.'"');

                return(false);
            };
        };

        $this->setPostid($comment['postid']);

        $this->setStatus($comment['displayStatus']);

        $this->setACL($comment['ACL']);

        $this->setContent($comment['content']);

        $this->setUsername($comment['username']);

        try {

            $statement = "INSERT INTO `comments` ( `postid`, `displayStatus`, `ACL`, `content`, `username`)
                                       VALUES ( :postid, :displayStatus, :ACL, :content, :username)";

            $query = $this->database->prepare($statement);

            $query->execute($this->articleNamedParams());

        } catch (Exception $e) {
            throw new Exception($e);

            return(false);
        };

        $this->setCommentid($this->database->lastInsertId());

        return(true);
    }

    /**
     * Uses the super cool on duplicate key update MySQL function to update an existing comment
     * @return Boolean   True on sucess else false
     * @throws Exception PDO expections on Database errors
     */
    public function save()
    {
        try {

            $statement = "INSERT INTO `comments` (`commentid`, `postid`, `displayStatus`, `ACL`, `content`, `creationDate`, `username`)
                                       VALUES (:commentid, :postid, :displayStatus, :ACL, :content, :creationDate, :username)
                          ON DUPLICATE KEY UPDATE
                                    commentid=values(commentid), postid=values(postid), displayStatus=values(displayStatus), ACL=values(ACL),
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
     * Deletes the current comment from the database
     *
     * @return Boolean   Sucess or failure
     * @throws Exception Database exceptions if query fails
     */
    public function delete()
    {
        try {

            $statement = "UPDATE `tow`.`comments` SET `status` = 'deleted'
                          WHERE `commentid` = ?;";

            $query = $this->database->prepare($statement);

            $query->execute($this->getCommentid());

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return(false);
        };

        return(true);
    }

    //*********SETTERS----------------------
    public function setCommentid($param)
    {
        $this->article['commentid'] = $param;
    }

    public function setPostid($param)
    {
        $this->article['postid'] = $param;
    }

    //*********GETTERS--------------
    public function getComment()
    {
        return($this->article);
    }

    public function getPostid()
    {
        return($this->article['postid']);
    }

    public function getCommentid()
    {
        return($this->article['commentid']);
    }

}
