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
	// wrap in a try/catch
	require_once '../../../../App/Config/Config.php';
	$taskHandler = new TaskHoursHandler();
	$taskHandler->setDatabase(\DataBase::getConnection());

	$returnValue = "";
	switch($_POST['request'])
	{
		case "addHours":
			if(commonAttributesExist() && addHoursAttributesExists())
			{
				$returnValue = $taskHandler->addHours($_POST['taskId'], $_POST['memberId'], $_POST['memberFirstName'], $_POST['workedDate'], $_POST['workedHours'], $_POST['workedComment']);

				if($returnValue['success'] == true)
				{
					$tempTaskComment = $returnValue['comment']['data'];
					$returnValue['comment']['data'] = $tempTaskComment->toArray();
				}
			}else
			{
				$returnValue = produceError("Missing attributes for adding hours request");
			}
			break;
		case "wipeHours":
			if(commonAttributesExist() && wipeHoursAttributesExists())
			{
				$returnValue = $taskHandler->wipeHoursForDate($_POST['taskId'], $_POST['memberId'], $_POST['memberFirstName'], $_POST['workedDate'], $_POST['workedComment']);
					
				if($returnValue['success'] == true)
				{
					$tempTaskComment = $returnValue['comment']['data'];
					$returnValue['comment']['data'] = $tempTaskComment->toArray();
				}
			}else
			{
				$returnValue = produceError("Missing attributes for wiping hours request");
			}
			break;
		case "hoursContribution":
			if(commonAttributesExist() && hoursContributionAttributesExists())
			{
				$returnValue = $taskHandler->getMembersContributionToTask($_POST['taskId']);
			}else
			{
				$returnValue = produceError("Missing attributes for adding hours request");
			}
			break;
		default: $returnValue = produceError("Invalid request");

	}

	echo json_encode($returnValue);
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
 * If statements are nested to assist with debugging if the need should arise
 *
 * @return boolean
 */
function addHoursAttributesExists()
{
	if(isset($_POST['taskId']))
	{
		if(isset($_POST['workedDate']))
		{
			if(isset($_POST['workedHours']))
			{
				if(isset($_POST['memberFirstName']))
				{
					if(isset($_POST['memberId']))
					{
						if(isset($_POST['workedComment']))
						{
							return true;
						}else
						{
							return false;
						}
					}else
					{
						return false;
					}
				}
				{
					return false;
				}
			}else
			{
				return false;
			}
		}else
		{
			return false;
		}
	}else
	{
		return false;
	}
}

/**
 * Checks for the required post data for wiping hours
 *
 * If statements are nested to assist with debugging if the need should arise
 *
 * @return boolean
 */
function wipeHoursAttributesExists()
{
	if(isset($_POST['taskId']))
	{
		if(isset($_POST['workedDate']))
		{
			if(isset($_POST['memberFirstName']))
			{
				if(isset($_POST['memberId']))
				{
					if(isset($_POST['workedComment']))
					{
						return true;
					}else
					{
						return false;
					}
				}else
				{
					return false;
				}
			}
			{
				return false;
			}
		}else
		{
			return false;
		}
	}else
	{
		return false;
	}
}

/**
 * Checks for the required post data for getting the hours contributed to a Task
 *
 * @return boolean
 */
function hoursContributionAttributesExists()
{
	if(isset($_POST['taskId']))
	{
		return true;
	}else
	{
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
	return $errorHolder;
}

?>