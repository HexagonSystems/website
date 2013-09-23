<?php
	session_start();
	include_once("config/config.php");
	include_once("includes/router.php");
	Router::route();
?>