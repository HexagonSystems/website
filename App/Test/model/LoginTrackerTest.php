<?php

class LoginTrackerTest extends PHPUnit_Framework_TestCase
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
        $username = 'Test';
        
        $this->object = new LoginTracker($this->database, $username);
        
        $this->assertNotNull($this->object);
        
        $object = $this->object;
        
        return($object);
    }
    
    /**
     * @depends testConstruct
     */    
    public function testAddLog(LoginTracker $object) {
        
        $logged = $object->addLog();
        
        $this->assertTrue($logged);
    }
    
    /**
     * @depends testConstruct
     */    
    public function testGetMessage(LoginTracker $object) {
        
        $this->assertContains('Too Many IP', $object->getMessage());
        
        $this->assertContains('Too Many Username', $object->getMessage());

    }
    
    public function testConstructOkay()
    {
        $username = 'Test'.rand(0, 99);
        
        $this->object = new LoginTracker($this->database, $username);
        
        $this->assertNotNull($this->object);
        
        $object = $this->object;
        
        return($object);
    }
    
    /**
     * @depends testConstructOkay
     */    
    public function testAddLogOkay(LoginTracker $object) {
        
        $logged = $object->addLog();
        
        $this->assertTrue($logged);
    }
    
    /**
     * @depends testConstructOkay
     */    
    public function testGetMessageOkay(LoginTracker $object) {
        
        $this->assertNotContains('Too Many Username', $object->getMessage());

    }
}
?>
