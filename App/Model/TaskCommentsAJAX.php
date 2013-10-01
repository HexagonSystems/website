<?php

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
	include "TaskCommentHandler.php";
	include "../Config/DataBase.php";
	$commentHandler = new TaskCommentsHandler();
	
	switch($_POST['request'])
	{
		case "create":
			if(commonCommentAttributesExist() && createCommentAttributesExist())
			{
				$commentHandler->createComment($_POST['taskId'], $_POST['memberId'], $_POST['tag'], $_POST['content']);
			}else
			{
				echo missingError()." create";
			}
			break;
		case "load":
			if(commonCommentAttributesExist() && loadCommentAttributesExist())
			{
				$commentHandler->loadComments($_POST['taskId'], $_POST['memberId'], $_POST['pageNum'], $_POST['qty']);
			}else
			{
				echo missingError()." load";
			}
			break;	
	}
}

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

function createCommentAttributesExist()
{
	if(isset($_POST['content']) && isset($_POST['tag']))
	{
		return true;
	}else
	{
		return false;
	}
}

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

function missingError()
{
	return "Required attributes not found";
}

?>