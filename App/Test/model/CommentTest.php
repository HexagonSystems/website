<?php

class CommentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var LogininTracker
     */
    protected $comment;

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

        $this->titleDummy = array('Flexitarian', 'Tonx', 'non accusamus', 'fashion', 'axe kale', 'chips squid',                                                                                             'ethnic', 'tempor',  'asymmetrical', 'irure', 'meggings', 'Cosby', 'sweater',
                                  'YOLO', 'Retro', 'skateboard', '8-bit', 'plaid', 'literally');

        $commentContent = $this->titleDummy[rand(0,18)]." ".$this->titleDummy[rand(0,18)]." "
                            .$this->titleDummy[rand(0,18)]." ".$this->titleDummy[rand(0,18)]
                            ." Lebowski ipsum look, I've got certain information, certain things have come to light, and
                               uh, has it ever occurred to you, man, that given the nature of all this new shit, that,"
                            .$this->titleDummy[rand(0,18)]." ".$this->titleDummy[rand(0,18)]." "
                            .$this->titleDummy[rand(0,18)]." ".$this->titleDummy[rand(0,18)]
                            ."uh, instead of running around blaming me, that this whole thing might just be, not, you
                              know, not just such a simple, but uh—you know? Okay. Vee take ze money you haf on you und
                              vee call it eefen. Updated:".date("Y-m-d H:i:s");
    
//        $commentContent = " Lebowski ipsum look, I've got certain information, certain things have come to light, and
//                            uh, has it ever occurred to you, man, that given the nature of all this new shit, that,
//                            uh, instead of running around blaming me, that this whole thing might just be, not, you
//                            know, not just such a simple, but uh—you know? Okay. Vee take ze money you haf on you und
//                            vee call it eefen. Updated:".date("Y-m-d H:i:s");
        
        $this->commentContent = $commentContent;
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
        $this->comment = new Comment($this->database);

        $this->assertNotNull($this->comment);

        $comment = $this->comment;
        
        $this->assertInstanceOf('Comment', $comment);
                
        return($comment);
    }

    /**
     * @depends testConstruct
     */
    public function testSetComment(Comment $comment)
    {
        //$this->assertInstanceOf('Comment', $comment);
        //`postid`, `displayStatus`, `ACL`, `content`, `username`
        
        $postid = rand(9,25);
        
        $comment->setPostid($postid);
        $comment->setStatus("published");
        $comment->setACL(1);
        $comment->setContent($this->commentContent);
        $comment->setUsername("stephen");
        
        
        $this->assertEquals( $this->commentContent , $comment->getContent());

        $this->assertEquals( $postid , $comment->getPostid());
        
        $commentArray = $comment->getComment();

        $this->assertArrayHasKey( 'postid' , $commentArray);
        
        $this->assertEquals( $this->commentContent , $commentArray['content']);
        
        return($comment);
        
    }

     /**
     * @depends testSetComment
     */
    public function testCreateComment(Comment $comment)
    {
        $content = $comment->getContent();
         
        $comment->create($comment->getComment());

        $comment->load($comment->getCommentid());
        
        $this->assertGreaterThanOrEqual(0, $comment->getCommentid());

        $this->assertEquals( $comment->getContent() , $content);

        return($comment);
    }

    /**
     * @depends testCreateComment
     */
    public function testLoadComment(Comment $comment)
    {
        $postId = 1;

        $comment->load($postId);

        $commentArray = $comment->getComment();

        $this->assertArrayHasKey( 'postid' , $commentArray);
        
        $this->assertEquals( $comment->getPostid() , $postId);

        return($comment);
    }

    /**
     * @depends testLoadComment
     */
    public function testSetContent(Comment $comment)
    {
        $commentArray = $comment->getComment();

        $this->assertArrayHasKey( 'postid' , $commentArray);

        $comment->setContent($this->commentContent);

        $this->assertEquals( $this->commentContent , $comment->getContent());

        $this->assertEquals( 1 , $comment->getPostid());

        return($comment);
    }

    /**
     * @depends testSetContent
     */
    public function testSaveComment(Comment $comment)
    {
        
        $comment->save();

        //reload the post from the database to check it saved
        $comment->load($comment->getPostid());

        $this->assertEquals( 1 , $comment->getPostid());

    }

}
