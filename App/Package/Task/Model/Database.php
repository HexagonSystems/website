<?php
namespace Task;

class DataBase
{

    public function __construct()
    {

    }

    public static function getConnection(){
        $user = "root";
        $pass = "root";
        $database = new PDO('mysql:host=localhost;dbname=hexagon', $user, $pass);
        return $database;
    }
}
