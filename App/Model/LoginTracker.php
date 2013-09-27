<?php
/**
 * Minor helper class for logging failed user log in attempts
 * Usage pass the class a DB connection and the username of the log in attempt
 * the class will create a log event and test how many times the IP has
 * attempted to login which it will return as a boolean. If the log in event is
 * a failure (tested in the user class) the class must be called again with
 * loginFailed()
 *
 * @version 1000
 * @author Stephen McMahon <stephentmcm@gmail.com>
 */
class LoginTracker
{
    private $database;
    private $ipAddress;
    private $username;
    private $log;
    private $maxAttempts = 3;
    private $attemptsUsername = 0;
    private $attemptsIp = 0;
    private $message = array();

    /**
     * Constructor creates a logIn event requires a database and username
     * @param  PDO    $database A PDO object of the current Database
     * @param  String $username The username currently trying to log in
     * @return void
     */
    public function __construct(PDO $database, $username)
    {
        $this->database = $database;

        $this->setIP();

        $this->setUsername($username);

        $this->checkLog();

    }

    /**
     * Sets IP to a long of the users IP address
     */
    private function setIP()
    {
        //Convert IP address to Long for storage.
        //This can be reversed with long2ip or in MySQL with INET_NTOA
        $this->ipAddress = ip2long($_SERVER['REMOTE_ADDR']);
    }

    private function setUsername($username)
    {
        $this->username = $username;
    }

    private function checkLog()
    {
        $statement = "SELECT * FROM `badLogins` WHERE ( `username` = '$this->username' OR `IPAddress` = '$this->ipAddress' )
                      AND `attemptTime` >= NOW() - INTERVAL 30 MINUTE";

        $this->log = $this->database->query($statement)->fetchAll();

        foreach ($this->log as $row) {
            if ($row['username'] == $this->username) {
                $this->attemptsUsername++;
            };
            if ($row['IPAddress'] == $this->ipAddress) {
                $this->attemptsIp++;
            };
        };

        if ($this->attemptsIp > $this->maxAttempts) {
            $this->message[] = 'Too Many IP';
        };

        if ($this->attemptsUsername > $this->maxAttempts) {
            $this->message[] = 'Too Many Username';
        }

    }

    public function addLog()
    {
        try {

            $statement = "INSERT INTO badLogins (`IPAddress`, `username`)
                          VALUES ( :IPAddress, :username )";

            $query = $this->database->prepare($statement);

            $values = array( ':IPAddress' => $this->ipAddress , ':username' => $this->username );

            $query->execute($values);

        } catch (Exception $e) {
                throw new Exception('Database error:', 0, $e);

                return(false);
        };

        return(true);
    }

    /**
     * Returns the log of messages the LoginTracker has generated
     * @return Array of messages
     */
    public function getMessage()
    {
        return($this->message);
    }
}
