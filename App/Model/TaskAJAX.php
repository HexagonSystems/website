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
	include "Task.php";
	include "TaskHandler.php";
	include "TaskDA.php";
	include "../Config/DataBase.php";
	$commentHandler = new TaskHandler();
	
	switch($_POST['request'])
	{
		case "create":
			if(commonCommentAttributesExist() && createCommentAttributesExist())
			{
				$response = $commentHandler->createComment($_POST['taskId'], $_POST['memberId'], $_POST['tag'], $_POST['title'], $_POST['content']);
				$response = json_encode($response);
				echo $response;
			}else
			{
				returnError("Missing attributes for create comments request");
			}
			break;
		case "load":
			if(commonCommentAttributesExist() && loadCommentAttributesExist())
			{
				$response = $commentHandler->loadComments($_POST['taskId'], $_POST['memberId'], $_POST['pageNum'], $_POST['qty']);
				for($counter = 0; $counter < sizeof($response); $counter++)
				{
					$tempTaskComment = $response[$counter];
					if($tempTaskComment instanceof TaskComment)
					{
						$response[$counter] = $tempTaskComment->toArray();
					}else
					{
						$response[$counter] = "Error";
					}
					
				}
				$response = json_encode($response);
				echo $response;
			}else
			{
				returnError("Missing attributes for load comments request");
			}
			break;
		case "addHours":
			if(commonCommentAttributesExist() && addHoursAttributesExists())
			{
				$commentHandler->addHours($_POST['taskId'], $_POST['memberId'], $_POST['workedDate'], $_POST['workedHours']);
			}else
			{
				returnError("Missing attributes for adding hours request");
			}
			break;
	}
}

/**
 * Checks that the required common post data has been passed through
 * 
 * @return boolean
 */
function commonCommentAttributesExist()
{
	if(isset($_POST['taskId']) && isset($_POST['memberId']))
	{
		return true;
	}else
	{
		return false;
	}
}

/**
 * Checks for the requied post data for creating a comment
 * 
 * @return boolean
 */
function createCommentAttributesExist()
{
	if(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['tag']))
	{
		return true;
	}else
	{
		return false;
	}
}

/**
 * Checks for the required post data for loading comments
 * 
 * @return boolean
 */
function loadCommentAttributesExist()
{
	if(isset($_POST['pageNum']) && isset($_POST['qty']))
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