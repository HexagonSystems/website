<?php

class PostTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var LogininTracker
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $host = "localhost";
        $db = "tow";
        $user = "towuser";
        $pass = "towpassword";
        $this->database = new PDO("mysql:host=$host;dbname=$db",$user,$pass);
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {      
    }
    
    public function testConstruct()
    {
        
        $this->object = new Post($this->database);
        
        $this->assertNotNull($this->object);
        
        $object = $this->object;
        
        return($object);
    }
    
    /**
     * @depends testConstruct
     */    
    public function testLoadPost(Post $object) {
        
        $postId = 1;
        
        $object->loadPost($postId);
        
        $post = $object->getPost();
        
        $this->assertArrayHasKey( 'postid' , $post);
    }
}
?>
