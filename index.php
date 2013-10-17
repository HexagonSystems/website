<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

/**
 * CHANGE THIS TO A CONFIG THAT IS YOU!
 */
require_once('App/Config/Config.php');

require_once("App/Router.php");

Router::route(DataBase::getConnection());

?>