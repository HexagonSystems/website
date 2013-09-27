<?php
class User
{
    private $database;
    private $username;
    private $password;
    private $email;
    private $firstLogin;
    private $lastLogin;
    private $accessLevel;

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

            $this->createUser($userArray["username"], $userArray["password"], $userArray["email"], $userArray["accessLevel"]);

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
     * @param Int    $accessLevel
     */
    public function createUser($username, $password, $email, $accessLevel)
    {
        if ($this->checkUsername($username) == "Username not found") {

            $this->setUsername($username);

            if ($this->checkEmail($email) == "Email not found") {

                $this->setEmail($email);

            } else {
                return($this->checkEmail($email));

            }

            $this->setPassword($password);

            $this->setFirstLogin();
            //$this->setLastLogin(time());

            $this->setAccessLevel($accessLevel);

            //If success return this object
            return($this);

        } else {
            return($this->checkUsername($username));
        }

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
            $user = $this->database->query("select username from users where username = '$username'")->fetch();

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
            $user = $this->database->query("select $type from users where $type = '$data'")->fetch();

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
            $user = $this->database->query("select email from users where email = '$email'")->fetch();

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
        $password = sha1($password);

        if ($this->getPassword() === $password) {
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
            $user = $this->database->query("select * from users where username = '$username'")->fetch();

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return;
        };

        //Set the password
        $this->setPassword($user["userPassword"], "old");

        if (!$this->checkPassword($password)) {
            //Else errors
            return("Password Incorrect");
        };

        $this->setAccessLevel($user['ACL']);

        switch ($user['ACL']) {
            case 1:
                //Else errors
                return("verify");
            break;
            case 2:
                return("User Is Suspended");
            break;
            case 5:
                $this->setUsername($user["username"]);
                $this->setEmail($user["email"]);
                $this->setLastLogin();
                $_SESSION['activeUser'] = $this->getUsername();
                //If success return this object
                return($this);
            break;
        };
    }//end loginUser

    public function automaticLogin($username, $session = true)
    {
        if ($this->checkUsername($username) == "Username not found") {
            return("Username not found");
        }

        //Collect User from the database
        try {
            //returns multidemnsional array
            $user = $this->database->query("select * from users where username = '$username'")->fetch();

        } catch (Exception $e) {
            throw new Exception('Database error:', 0, $e);

            return;
        };

        //Set the password
        $this->setPassword($user["userPassword"], "old");

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
            $query = "select username from users where email = :email LIMIT 1";
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
            $query = "select `email` from `users` where `username` = :username LIMIT 1";
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
//        $query = "UPDATE `users` SET
//                    `email` = :email,
//                    `userPassword` = :password,
//                    `ACL` = :acl,
//                    `lastLoginDate` = :lastLoginDate
//                    WHERE `username` = :username";
//
//        $email			= $this->getEmail();
//        $password		= $this->getPassword();
//        $acl			= $this->getAccessLevel();
//        $lastLoginDate	= $this->getlastLogin();
//        $username		= $this->getUsername();
//
//
//        $resultSet = $this->database->prepare($query);
//        $resultSet->bindParam(':email'			,	$email			, PDO::PARAM_STR);
//        $resultSet->bindParam(':password'		,	$password		, PDO::PARAM_STR);
//        $resultSet->bindParam(':acl'			, 	$acl			, PDO::PARAM_INT);
//        $resultSet->bindParam(':lastLoginDate'	,	$lastLoginDate	, PDO::PARAM_STR);
//        $resultSet->bindParam(':username'		, 	$username		, PDO::PARAM_STR);
//
//        try {
//            $resultSet->execute();
//        } catch (Exception $e) {
//            return "Something went wrong";
//        }

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
        if ($this->checkUsername($this->getUsername()) === 'Username found') {
            try {
                $sql = '
                    UPDATE `users`
                    SET    `ACL` = :ACL, `username` = :username, `email` = :email, `userPassword` = :userPassword
                    WHERE  `username` = :username';
                $query = $this->database->prepare($sql);
                $query->execute(
                   array(':username' => $this->getUsername()
                        , ':email' => $this->getEmail()
                        , ':userPassword' => $this->getPassword()
                        , ':ACL' => $this->getAccessLevel()
                   )
                );
            } catch (PDOException $e) {
                echo $e->getMessage();

                return;
            }
        } else {

            try {

                $statement = "INSERT INTO `users` (`username`, `email`, `userPassword`, `ACL`)
                                           VALUES (:username, :email, :userPassword, :ACL)";

                $query = $this->database->prepare($statement);

                $query->execute(
                    array(':username' => $this->getUsername()
                        , ':email' => $this->getEmail()
                        , ':userPassword' => $this->getPassword()
                        , ':ACL' => $this->getAccessLevel()
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
     * Sets the users email
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
            $password = sha1($password);
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
                $user = $this->database->query("select lastLoginDate from users where username = '$username'")->fetch();
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

            $statement = $this->database->query("select username, ACL FROM users WHERE ACL < 10")->fetchAll();
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
