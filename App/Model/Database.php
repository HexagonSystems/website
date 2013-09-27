<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

class Database
{
    private $host = "localhost";
    private $db = "tow";
    private $user = "towuser";
    private $pass = "towpassword";
    private $database;

    public function __construct()
    {
        $this->database = new PDO("mysql:host=$this->host;dbname=$this->db",$this->user,$this->pass);

        return( $this->database );
    }
}
