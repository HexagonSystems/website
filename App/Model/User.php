<?php
class User
{
    private $database;
    private $username;
    private $password;
    private $email;

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
     *
     * @param String $username
     * @param String $password
     * @param String $email
     * @param return Object|String Return this object or an error string
     */
    public function createUser($username, $password, $email)
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

        $this->setPassword($password);

        $this->setFirstLogin();

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
     * Tests data exists in user in the Database
     * @param  String    $data the value we're searching for
     * @param  String    $type the field we're checking
     * @return String    'Data (not) found'
     * @throws Exception
     * @deprecated since version 1 Removed for sercurity reason will throw warnings
     */
    public function checkExists($data, $type)
    {
        throw new Exception("Warning! Deprecated function", 500);
        try {
            $user = $this->database->query("select $type from member where $type = '$data'")->fetch();

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return;
        };

        if ($user !== false) {
            return("Data found");
        } else {
            return("Data not found");
        }

    }// end checkExists

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

        return $this;
        //As we don't use ACL here it's turned off.
        //
        //return $this->setAccessLevel($user['ACL']);
        // switch ($user['ACL']) {
        //     case 1:
        //         //Else errors
        //         return("verify");
        //     break;
        //     case 2:
        //         return("User Is Suspended");
        //     break;
        //     case 5:
        //         $this->setUsername($user["username"]);
        //         $this->setEmail($user["email"]);
        //         $this->setLastLogin();
        //         $_SESSION['activeUser'] = $this->getUsername();
        //         //If success return this object
        //         return($this);
        //     break;
        // };

    }//end loginUser

    public function automaticLogin($username, $session = true)
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

        //Set the password
        $this->setPassword($user["memberPassword"], "old");

        $this->setUsername($user["username"]);

        $this->setEmail($user["email"]);

        $this->setLastLogin();

        if ($session) {
            $this->sessionCreate();
        }

        $this->setAccessLevel($user['ACL']);

        //If success return this object
        return($this);
    }

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

    public function updateUser()
    {
        //throw new Exception('Warning UpdateUser() Deprecated! Use save() instead');
       // $query = "UPDATE `member` SET
       //             `email` = :email,
       //             `memberPassword` = :password,
       //             `ACL` = :acl,
       //             `lastLoginDate` = :lastLoginDate
       //             WHERE `username` = :username";

       // $email			= $this->getEmail();
       // $password		= $this->getPassword();
       // $acl			= $this->getAccessLevel();
       // $lastLoginDate	= $this->getlastLogin();
       // $username		= $this->getUsername();


       // $resultSet = $this->database->prepare($query);
       // $resultSet->bindParam(':email'			,	$email			, PDO::PARAM_STR);
       // $resultSet->bindParam(':password'		,	$password		, PDO::PARAM_STR);
       // $resultSet->bindParam(':acl'			, 	$acl			, PDO::PARAM_INT);
       // $resultSet->bindParam(':lastLoginDate'	,	$lastLoginDate	, PDO::PARAM_STR);
       // $resultSet->bindParam(':username'		, 	$username		, PDO::PARAM_STR);

       // try {
       //     $resultSet->execute();
       // } catch (Exception $e) {
       //     return "Something went wrong";
       // }

        if ($this->save()) {
            return "Updated";
        } else {
            return "Something went wrong";
        }


    }

    /**
     * Save user to database
     *
     * @todo Get this working correctly the current INSERT is in correct
     * @return string - Either error messages or saved
     */
    public function save()
    {
        /**
         *  Full texts  
         *  memberId
         *  firstName
         *  lastName
         *  email
         *  phoneNo
         *  username
         *  memberPassword
         */
        if ($this->checkUsername($this->getUsername()) === 'Username found') {
            try {
                $sql = '
                    UPDATE
                        `member`
                    SET
                        `memberId`       = :memberId
                        `firstName`      = :firstName
                        `lastName`       = :lastName
                        `email`          = :email
                        `phoneNo`        = :phoneNo
                        `username`       = :username
                        `memberPassword` = :memberPassword
                    WHERE
                        `username` = :username';
                $query = $this->database->prepare($sql);
                $query->execute(
                   array( ':username' => $this->getUsername()
                        , ':email' => $this->getEmail()
                        , ':memberPassword' => $this->getPassword()
                   )
                );
            } catch (PDOException $e) {
                echo $e->getMessage();

                return;
            }
        } else {

            try {

                $statement = '
                        INSERT INTO
                            `member`
                            (`memberId`
                            ,`firstName`
                            ,`lastName`
                            ,`email`
                            ,`phoneNo`
                            ,`username`
                            ,`memberPassword`
                            )
                        VALUES
                            (:memberId
                            ,:firstName
                            ,:lastName
                            ,:email
                            ,:phoneNo
                            ,:username
                            ,:memberPassword
                            )';

                $query = $this->database->prepare($statement);

                $query->execute(
                    array(':username' => $this->getUsername()
                        , ':email' => $this->getEmail()
                        , ':memberPassword' => $this->getPassword()
                   )
                );

            } catch (Exception $e) {
                throw new Exception('Database error:', 0, $e);

                return;
            };
        };

        return("saved");
    }

    /**
     * Set the username for the user
     * @todo go private on this I don't think it should be a public method
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

    //First Login
    public function setFirstLogin()
    {
        $this->firstLogin =  date('Y-m-d H:i:s');
    }
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    //Last Login
    public function setLastLogin()
    {
        $this->lastLogin = date('Y-m-d H:i:s');
        $this->save();
    }

    public function getlastLogin($username = null)
    {
        if ($username != null) {
            try {
                $user = $this->database->query("select lastLoginDate from member where username = '$username'")->fetch();
            } catch (Exception $e) {
                throw new Exception('Database error:', 0, $e);

                return;
            };

            return($user['lastLoginDate']);
        }

        return $this->lastLogin;
    }

    /**
     * Sets the access level for the user
     * @todo Add Sanity checks in here and return values
     * @param Int $accessLevel
     */
    public function setAccessLevel($accessLevel)
    {
        $this->accessLevel = $accessLevel;
    }
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }

    public function displayUsers()
    {
        try {

            $statement = $this->database->query("select username, ACL FROM member WHERE ACL < 10")->fetchAll();
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
        if (isset($_SESSION['account'])) {
            session_unset('account');
        }
        if (isset($_SESSION['accountObject'])) {
            session_unset('accountObject');
        }
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

    public function setDatabase(PDO $database)
    {
        $this->database = $database;
    }
    //end Class
}
