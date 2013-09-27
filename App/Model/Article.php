<?php
/**
 * Contains a single Article entry for the blog
 * Features a helper method for building and returning Arrays of articles
 *
 * @author Stephen McMahon <stephentmcm@gmail.com>
 */
class Article
{
    protected $database;
    //Holds the articles data in an Array most easily accessed through the getters
    protected $article;

    /**
     * Sets up an empty Article object
     *
     * @param PDO $database Needs a PDO database connection
     */
    public function __construct()
    {

    }//end construct

    public function setDatabase(PDO $database)
    {
        $this->database = $database;
    }

    /**
     * Loads an existing article from the database
     *
     * @param Int $articleId the id number of the article
     *
     * @return Boolean   True for loaded false for DB connection error
     * @throws Exception PDO expection
     */
    /*public function load($id)
    {
        return('Error load() not implemented');
    }//end loadArticle
    */
    /**
     * Stores the input data in the article object used for creating new article
     *
     * @param Array $article Must contain `title`, `status`, `ACL`, `content`,
     * `username`
     *
     * @return Boolean   True on sucess else false
     * @throws Exception Throws Database and custom errors
     */
    public function create($data = array())
    {
        return('Error create() not implemented');
    }

    /**
     * Uses the super cool on duplicate key update MySQL function to update an existing article
     * @return Boolean   True on sucess else false
     * @throws Exception PDO expections on Database errors
     */
    public function save()
    {
        return('Error save() not implemented');
    }//end save

    /**
     * Deletes the current article from the database
     *
     * @return Boolean   Sucess or failure
     * @throws Exception Database exceptions if query fails
     */
    public function delete()
    {
        return('Error load() not implemented');
    }

    /**
     * Quick function to shift the keys from 'articleid' to ':articleid' etc
     * @return Array The article array with keys in PDO named parameter form
     * @link www.php.net/maunal/PDO Info about binded parameters
     */
    protected function articleNamedParams()
    {
        foreach ($this->article as $key => $value) {
            $key = ':'.$key;
            $binded[$key] = $value;
        }

        return($binded);
    }//end articleNamedParams

    //*********SETTERS----------------------
    public function setStatus($param)
    {
        $this->article['displayStatus'] = $param;
    }

    public function setACL($param)
    {
        $this->article['ACL'] = $param;
    }

    public function setContent($param)
    {
        $this->article['content'] = $param;
    }

    public function setDate($param)
    {
        $this->article['creationDate'] = $param;
    }

    public function setUsername($param)
    {
        $this->article['username'] = $param;
    }

    //*********GETTERS--------------
    public function getStatus()
    {
        return($this->article['displayStatus']);
    }

    public function getACL()
    {
        return($this->article['ACL']);
    }

    public function getContent()
    {
        return($this->article['content']);
    }

    public function getDate()
    {
        return($this->article['creationDate']);
    }

    public function getUsername()
    {
        return($this->article['username']);
    }
}//end article class
