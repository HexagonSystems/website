<?php
namespace Task;
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
	include "../Config/DataBase.php";
	include "../Config/Config.php";
	$commentHandler = new TaskCommentsHandler();

	switch($_POST['request'])
	{
		case "create":
			if(commonCommentAttributesExist() && createCommentAttributesExist())
			{
				$response = $commentHandler->createComment($_POST['taskId'], $_POST['memberId'], $_POST['tag'], $_POST['title'], $_POST['content']);
				if($response['success'] == true)
				{
					$tempComment = $response['data'];
					$response['data'] = $tempComment->toArray();
				}
				$response = json_encode($response);
				echo $response;
			}else
			{
				produceError("Missing attributes for create comments request");
			}
			break;
		case "load":
			if(commonCommentAttributesExist() && loadCommentAttributesExist())
			{
				$response = $commentHandler->loadComments($_POST['taskId'], $_POST['memberId'], $_POST['pageNum'], $_POST['qty']);
				if($response['success'] == true)
				{
					for($counter = 0; $counter < sizeof($response['data']); $counter++)
					{
						$tempTaskComment = $response['data'][$counter];
						if($tempTaskComment instanceof TaskComment)
						{
							$response['data'][$counter] = $tempTaskComment->toArray();
						}else
						{
							$response['data'][$counter] = "Error";
						}
					}
				}

				$response = json_encode($response);
				echo $response;
			}else
			{
				produceError("Missing attributes for load comments request");
			}
			break;
		case "addHours":
			if(commonCommentAttributesExist() && addHoursAttributesExists())
			{
				$commentHandler->addHours($_POST['taskId'], $_POST['memberId'], $_POST['workedDate'], $_POST['workedHours'], $_POST['workedComment']);
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
			if(isset($_POST['workedComment']))
			{
				return true;
			}else
			{
				produceError("Worked comment is missing");
			}
		}else
		{
			produceError("Worked hours is missing");
			return false;
		}
	}else
	{
		produceError("Worked date is missing");
		return false;
	}
}

/**
 * Returns the standard error message
 *
 * @return string
 */
function produceError($error)
{
	$errorHolder = array();
	$errorHolder['success'] = false;
	$errorHolder['error'] = $error;
	$errorHolder = json_encode($errorHolder);
}

?>