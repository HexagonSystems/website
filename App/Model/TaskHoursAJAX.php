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
		case "create":
			if(commonCommentAttributesExist() && createCommentAttributesExist())
			{
				$response = $taskHandler->createTask($_POST['title'], $_POST['content'], $_POST['memberId'], $_POST['status']);
				/* If everything goes ok, the response should look something like:
				 *  array (
				 		*  	'task' => array ( 'success' => true,
				 				*  						'data' => TaskObject),
				 		*  	'hours' => array( 'success' => true,
				 				*  						'data' => TaskHoursObject),
				 		*  	'comment' => array( 'success' => true,
				 				*  						'data' => TaskCommentObject),
				 		*  	'success' => true )
				*
				*  If something goes wrong I expect the output to look like
				*  array (
						*  	'success' = false,
						*  	'error' => array ( 'location' => 'Location';,
								*  						'message' => 'Error message' ) )
				*/
				if($response['success'] == true)
				{
					$tempTaskObject = $response['task']['data'];
					$response['task']['data'] = $tempTaskObject->toArray();
						
					$tempHoursObject = $response['hours']['data'];
					$response['hours']['data'] = $tempHoursObject->toArray();
						
					$tempCommentObject = $response['comment']['data'];
					$response['comment']['data'] = $tempCommentObject->toArray();
				}
				echo json_encode($response);
			}else
			{
				returnError("Missing attributes for create comments request");
			}
			break;
		case "load":
			if(commonCommentAttributesExist() && loadCommentAttributesExist())
			{
				$response = $taskHandler->loadTasks($_POST['pageNum'], $_POST['qty']);
				if($response['success'] == true)
				{
					for($counter = 0; $counter < sizeof($response['data']); $counter++)
					{
						$tempTask = $response['data'][$counter];
						if($tempTask instanceof Task)
						{
							$response['data'][$counter] = $tempTask->toArray();
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
				returnError("Missing attributes for load comments request");
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
	if(isset($_POST['memberId']))
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
	if(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['status']))
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