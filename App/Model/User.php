<?php
class User
{
    private $database;

    private $memberId;
    private $firstName;
    private $lastName;
    private $username;
    private $password;
    private $email;
    private $phoneNo;

    /**
     * Constructor with extra quick create/login
     * @param  PDO    $database  sets the database connection
     * @param  Array  $userArray Optional must contain a key of "new" or "login" needs "username", "password" [, "email", "accessLevel"]
     * @return Object or String returns this object
     */
    public function __construct(PDO $database, $userArray = array(''))
    {

        $this->database = $database;

        if (isset($userArray["new"])) {
            //@TODO FIX THIS WE"RE MISSING FIRT LAST NAME AND PHONE
            $this->createUser($userArray["username"], $userArray["password"], $userArray["email"]);

            return($this);

        } elseif (isset($userArray["login"])) {

            $this->loginUser($userArray["username"], $userArray["password"]);

            return($this);
        }

    }

    /**
     * Creates a new user
     * This function sets up a new user object it does not save the object/user to database though.
     * To create and save a user invoke createUser() and then save()
     *
     * @param String $username
     * @param String $password
     * @param String $email
     * @param return Object|String Return this object or an error string
     */
    public function createUser($firstName, $lastName, $username, $password, $email, $phoneNo)
    {
        //Check if the username exists
        if ($this->checkUsername($username) == "Username not found") {
            //Because we've tested and it doesn't we can set and continue
            $this->setUsername($username);
        } else {
            return($this->checkUsername($username));
        }

        //We repeat this test for email
        if ($this->checkEmail($email) == "Email not found") {

            $this->setEmail($email);

        } else {
            return($this->checkEmail($email));

        }

        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setPhoneNo($phoneNo);
        $this->setPassword($password);
        //If success return this object
        //NOTE We haven't saved the user yet
        return($this);

    } //end createUser

    /**
     * Tests if username exists in Database
     * Can be used to check the user has the right username or check the user isn't trying to
     * register a username that already exists
     * @param  String    $username
     * @return String
     * @throws Exception
     */
    public function checkUsername($username)
    {

        try {
            $user = $this->database->query("select username from member where username = '$username'")->fetch();

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return;
        };

        if ($user !== false) {
            return("Username found");
        } else {
            return("Username not found");
        }

    }// end checkUsername

    /**
     * Tests if email exists in Database
     * Can be used to check the user has the right email or check the user isn't trying to
     * register an email that already exists
     * @param  String    $email
     * @return String    either "Email found" or "Email not found"
     * @throws Exception
     */
    public function checkEmail($email)
    {
        try {
            $user = $this->database->query("select email from member where email = '$email'")->fetch();

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return;
        };

        if ($user !== false) {
            return("Email found");
        } else {
            return("Email not found");
        }

    }// end checkEmail

    /**
     * Checks if a string matches the password. Does NOT set the Password
     * @todo Need to add an error check if password hasn't been set yet
     * @param  String  $password
     * @return boolean
     */
    public function checkPassword($password)
    {
        $verify = password_verify($password, $this->getPassword());

        if ($verify == 1) {
            return true;
        } else {
            return false;
        }
    }// end checkPassword

    /**
     * Wraps checking Username and Password together
     * @todo Set Up calls to create session for user if successful
     * @param  String        $username
     * @param  String        $password
     * @return String/Object
     * @throws Exception
     */
    public function loginUser($username, $password)
    {
        if ($this->checkUsername($username) == "Username not found") {
            return("Username not found");
        }

        //Collect User from the database
        try {
            //returns multidemnsional array
            $user = $this->database->query("select * from member where username = '$username'")->fetch();

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return;
        };

        //We pull the password from the DB and set it for this User object
        $this->setPassword($user["memberPassword"], "old");

        //We then test this password against the input password
        if (!$this->checkPassword($password)) {
            //Else errors
            return("Password Incorrect");
        };

        $this->setMemberId($user["memberId"]);
        $this->setFirstName($user["firstName"]);
        $this->setLastName($user["lastName"]);
        $this->setEmail($user["email"]);
        $this->setPhoneNo($user["phoneNo"]);

        return $this;

    }//end loginUser

    public function getUsernameFromEmail($email)
    {
        if ($this->checkEmail($email) == "Email not found") {
            return false;
        }

        //Collect User from the database
        try {
            //returns multidemnsional array
            $query = "select username from member where email = :email LIMIT 1";
            $resultSet = $this->database->prepare($query);
            $resultSet->bindParam(':email', $email, PDO::PARAM_STR);
            $resultSet->execute();

            if ($resultSet) {
                if ($resultSet->rowCount()) {
                    $row = $resultSet->fetch();

                    return $row['username'];
                } else {
                    return false;
                }
            } else {
                return false;
            }

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return false;
        };
    }

    public function getEmailFromUsername($username)
    {
        if ($this->checkUsername($username) == "Username not found") {
            return false;
        }

        //Collect User from the database
        try {
            //returns multidemnsional array
            $query = "select `email` from `member` where `username` = :username LIMIT 1";
            $resultSet = $this->database->prepare($query);
            $resultSet->bindParam(':username', $username, PDO::PARAM_STR);
            $resultSet->execute();

            if ($resultSet) {
                if ($resultSet->rowCount()) {
                    $row = $resultSet->fetch();

                    return $row['email'];
                } else {
                    return false;
                }
            } else {
                return false;
            }

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return false;
        };
    }

    /**
     * Save user to database
     *
     * @todo Get this working correctly the current INSERT is in correct
     * @return string - Either error messages or saved
     */
    public function save()
    {
        //If this user exists update else create new
        if ($this->checkUsername($this->getUsername()) === 'Username found') {
            $sql = '
                UPDATE
                    `member`
                SET
                    `firstName`      = :firstName
                    `lastName`       = :lastName
                    `email`          = :email
                    `phoneNo`        = :phoneNo
                    `username`       = :username
                    `memberPassword` = :memberPassword
                WHERE
                    `memberId`       = :memberId';

        } else {
            //We DON'T set memberId as it auto-generates
            $sql = '
                INSERT INTO `member`
                    (`firstName`
                    ,`lastName`
                    ,`email`
                    ,`phoneNo`
                    ,`username`
                    ,`memberPassword`
                    )
                VALUES
                    (:firstName
                    ,:lastName
                    ,:email
                    ,:phoneNo
                    ,:username
                    ,:memberPassword
                    )';
        };

        try{
            $query = $this->database->prepare($sql);
            $query->execute(
               array(':username'       => $this->getUsername()
                    ,':email'          => $this->getEmail()
                    ,':memberPassword' => $this->getPassword()
                    ,':firstName'      => $this->getFirstName()
                    ,':lastName'       => $this->getLastName()
                    ,':phoneNo'        => $this->getPhoneNo()
               )
            );
        } catch (PDOException $e) {
            echo $e->getMessage();

            return;
        }

        return("saved");
    }

    /**
     * Set the username for the user
     * @param String $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the FirstName for the user
     * @param String $FirstName
     */
    public function setFirstName($FirstName)
    {
        $this->firstName = $FirstName;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the LastName for the user
     * @param String $username
     */
    public function setLastName($LastName)
    {
        $this->lastName = $LastName;
    }
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the member email
     * @todo Add check for well formedness?
     * @param String $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the member phoneNo
     * @todo Add check for well formedness?
     * @param String $phoneNo
     */
    public function setPhoneNo($phoneNo)
    {
        $this->phoneNo = $phoneNo;
    }
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

    /**
     * Sets the memberId
     * @todo Add check for well formedness?
     * @param String $memberId
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * Set Password, change the value of old when setting an already encrypted password
     * @param string $password
     * @param string $old      - If unset will encrypt the incoming password
     */
    public function setPassword($password, $old = 0)
    {
        if ($old === 0) {
            $password = password_hash($password, PASSWORD_DEFAULT);
        };

        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function displayUsers()
    {
        try {

            $statement = $this->database->query("select username FROM member")->fetchAll();
            //$statement = $this->database->prepare($statement);

            //$result = $statement->execute();

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return ;
        };

        return($statement);
    }

    public function sessionCreate()
    {
        if (!empty($this->username)) {
            $_SESSION['account'] = array('username'=>$this->getUsername(), 'access'=>$this->getAccessLevel());
            $_SESSION['accountObject'] = serialize($this);
        }
    }

    public function sessionDestroy()
    {
        if (!isset($_SESSION['account'])) {
            return "No session for account";
        }
        if (!isset($_SESSION['accountObject'])) {
            return "No session for accountObject";
        }
        session_unset('account');
        session_unset('accountObject');
        return "Session Destoryed";
    }

    public function __sleep()
    {
        return array('username'
                    , 'password'
                    , 'email'
                    , 'firstLogin'
                    , 'lastLogin'
                    , 'accessLevel'
                    );
    }

    public function automaticLogin($username, $session = true)
    {
       throw new Exception("Warning. Removed function. High sercurity concern. Code remains in model but won't run.", 500);
       return;
        // if ($this->checkUsername($username) == "Username not found") {
        //     return("Username not found");
        // }

        // //Collect User from the database
        // try {
        //     //returns multidemnsional array
        //     $user = $this->database->query("select * from member where username = '$username'")->fetch();

        // } catch (Exception $e) {
        //     throw new Exception('Database error:', 0, $e);

        //     return;
        // };

        // //Set the password
        // $this->setPassword($user["memberPassword"], "old");

        // $this->setUsername($user["username"]);

        // $this->setEmail($user["email"]);

        // $this->setLastLogin();

        // if ($session) {
        //     $this->sessionCreate();
        // }

        // $this->setAccessLevel($user['ACL']);

        // //If success return this object
        // return($this);
    }

    public function updateUser()
    {
        throw new Exception("Warning. Deprecated function. Use save() instead it handles both new and existing accounts.", 500);
        if ($this->save()) {
            return "Updated";
        } else {
            return "Something went wrong";
        }
    }
    
    public function setDatabase(PDO $database)
    {
        $this->database = $database;
    }
    //end Class
}
