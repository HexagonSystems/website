<?php

class Verify
{
    private $database;

    /**
     * Construct
     */
    public function __construct()
    {

    } //end constructor

    /**
     * Set Database
     * Sets the database PDO object for the object
     *
     * @param PDO $database
     */
    public function setDatabase(PDO $database)
    {
        $this->database = $database;
    }

    /**
     * Generate Link
     * Currently unused function
     * May be deleted in the near future
     *
     * @param  string $hash
     * @param  string $action
     * @return string
     */
    private function generateLink($hash, $action)
    {
        $link = "loginPage.php?location=verify&action=$action&confirm=$hash";

        return $link;
    }

    /**
     * Generate Hash
     * Hashes the given string with BCRYPT
     *
     * @param  string $string
     * @return string
     */
    public function generateHash($string)
    {
        return password_hash($string, PASSWORD_BCRYPT);
    }

    /**
     * Get Time Of Last Hash
     * Checks to see if there is an existing hash for the given action register for the user
     * If there is return the last hash's time
     *
     * @param string $email
     * @parem string $action
     */
    public function getTimeOfLastHash($email, $action)
    {
        $query = "SELECT `time` FROM `verify` WHERE `email` = :email AND `action` = :action ORDER BY `time` DESC LIMIT 1";

        $resultSet = $this->database->prepare($query);

        $resultSet->bindParam(':email', $email, PDO::PARAM_STR);
        $resultSet->bindParam(':action', $action, PDO::PARAM_STR);

        try {
            $resultSet->execute();
        } catch (Exception $e) {
            return false;
        }

        if ($resultSet) {
            if ($resultSet->rowCount()) {
                $row = $resultSet->fetch();

                return $row['time'];
            } else {
                return "null";
            }
        } else {
            return false;
        }
    }

    public function getLashHash($email, $action)
    {
        $query = "SELECT `hash`, `time` FROM `verify` WHERE `email` = :email AND `action` = :action ORDER BY `time` DESC LIMIT 1";
        $resultSet = $this->database->prepare($query);

        $resultSet->bindParam(':email', $email, PDO::PARAM_STR);
        $resultSet->bindParam(':action', $action, PDO::PARAM_STR);

        try {
            $resultSet->execute();
        } catch (Exception $e) {
            return false;
        }

        if ($resultSet) {
            if ($resultSet->rowCount()) {
                $row = $resultSet->fetch();
                $instructions = array();
                $instructions['hash']	= $row['hash'];
                $instructions['time']	= $row['time'];
                $instructions['email']	= $email;
                $instructions['action']	= $action;

                return $instructions;
            } else {
                return "null";
            }
        } else {
            return false;
        }
    }

    public function timeToCreateNewHash($lastHashDate, $action)
    {
        $timeDifference = array(
                "resetPassword"		=> "24 hours",
                "registerAccount"	=> "30 days"
        );

        $currentTime = new DateTime(date('YmdHms'));

        $lastHash = new DateTime(date('YmdHms', strtotime($lastHashDate)));

        if ($currentTime->sub(date_interval_create_from_date_string($timeDifference[$action])) > $lastHash) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Build Hash
     *
     * @param  string  $email
     * @param  string  $action
     * @return boolean || string (the hash that was created)
     */
    public function buildHash($email, $action)
    {
        $time = date('YmdHis');
        $hash = password_hash($email.$time, PASSWORD_BCRYPT);
        if (!$hash) {
            return false;
        }

        $query = "INSERT INTO`verify`(hash, time, email, action)
                                VALUES(:hash, :time, :email, :action)";

        $resultSet = $this->database->prepare($query);

        $resultSet->bindParam(':hash',		$hash,		PDO::PARAM_STR);
        $resultSet->bindParam(':time',		$time,		PDO::PARAM_STR);
        $resultSet->bindParam(':email',		$email,		PDO::PARAM_STR);
        $resultSet->bindParam(':action',	$action,	PDO::PARAM_STR);

        $resultSet->execute();

        if ($resultSet) {
            return $hash;
        } else {
            return false;
        }
    }

    /**
     * Get Hash Instructions
     * Unused function
     *
     * @param string $hash
     */
    public function getHashInstruction($hash)
    {
        $query = "SELECT * FROM **HASHTABLE** WHERE ";
    }

    /**
     * Retrieve Instructions
     * Returns the attributes of the hash that is given
     *
     * @param  string $verifyCode
     * @param  string $email
     * @return Array  || boolean
     */
    public function retreiveInstructions($verifyCode, $email)
    {
        $query = "SELECT `time`, `action` FROM `verify` WHERE `hash` = :hash AND `email` = :email LIMIT 1";

        $resultSet = $this->database->prepare($query);
        $resultSet->bindParam(':hash',		$verifyCode,	PDO::PARAM_STR);
        $resultSet->bindParam(':email',		$email,			PDO::PARAM_STR);

        $resultSet->execute();

        if ($resultSet) {
            if ($resultSet->rowCount()) {
                $row = $resultSet->fetch();
                $instructions = array();
                $instructions['hash']	= $verifyCode;
                $instructions['time']	= $row['time'];
                $instructions['email']	= $email;
                $instructions['action']	= $row['action'];

                return $instructions;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
