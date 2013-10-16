<?php

$ERROR_MISSING_ATTRIBUTES = "Required attributes not found";
/**
 * Additional security will need to be implemented
 */

/**
 * The request will be either:
 * 	- create
 *  - load
 */
if(!isset($_POST['request']))
{
	echo "Error: Please submit a request";
	die();
}else
{
	// wrap in a try/catch
	require_once "../Config/DataBase.php";
	require_once "../Config/Config.php";
	$taskHandler = new TaskHandler();
	$taskHandler->setDatabase(DataBase::getConnection());

	switch($_POST['request'])
	{
		case "addHours":
			if(commonAttributesExist() && addHoursAttributesExists())
			{
				$returnValue = $taskHandler->addHours($_POST['taskId'], $_POST['memberId'], $_POST['workedDate'], $_POST['workedHours'], $_POST['workedComment']);
			}else
			{
				produceError("Missing attributes for adding hours request");
			}
			break;
	}
}

/**
 * Checks that the required common post data has been passed through
 *
 * @return boolean
 */
function commonAttributesExist()
{
	if(isset($_POST['memberId']))
	{
		return true;
	}else
	{
		return false;
	}
}

/**
 * Checks for the required post data for adding hours
 *
 * @return boolean
 */
function addHoursAttributesExists()
{
	if(isset($_POST['workedDate']))
	{
		if(isset($_POST['workedHours']))
		{
			return true;
		}else
		{
			echo "Missing workedHours";
			return false;
		}
	}else
	{
		echo "Missing workedDate";
		return false;
	}
}

/**
 * Returns the standard error message
 *
 * @return string
 */
function missingError($error)
{
	$errorHolder = array();
	$errorHolder['success'] = false;
	$errorHolder['error'] = $error;
}

?>