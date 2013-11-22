<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

require_once('App/Config/Config.php');
require_once("App/Router.php");

if (strpos($_SERVER['REQUEST_URI'],'index.php') === false) {
	header('Location: '.SITE_ROOT.'index.php');
}

Router::route(DataBase::getConnection());

?>