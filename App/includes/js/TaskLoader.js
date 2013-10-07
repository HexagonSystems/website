/* CONFIG */
var tableContent = new Array();
var COMMENTS_PER_PAGE = 5;
var TABLE_CONTENT_PRINT_LOCATION = "#commentsContainer";

/* FUNCTIONS */

/**
 * Creates a comment in the database
 */
function createTask(taskTitle, taskDescription, taskStatus)
{
	 $.post( ajaxUrl, { request: "create", memberId: 1, title: taskTitle, content: taskDescription,  status: taskStatus}, 
		 		function( data )
		 	    {
					    if(data == "true")
					    {
							// Run function to check for updats, therefore asking the user to refresh
							// Or refresh automatically, or just add the comment in locally, this will ignore the fact if other comments have been added around the same time as well
							// Maybe it could do both, check for new updates, if there arnt any add this locally, if there are refresh or ask the user to refresh
					    	alert("working");
					    }else
					    {
						    alert(data);
					    }
					 });
}

/**
 * Prints a single comment
 */
function printSingleTask(taskId, taskTitle, taskDscr, taskStatus, taskMembers, taskLastUpdate, taskLastUpdateMember)
{
	/* TABLE ROW */
	var tableRow = document.createElement('tr');
	tableRow.className = 'parentOfAccordion';
	
	/* STATUS */
	var taskStatusTD = document.createElement('td');
	var taskStatusSpan = document.createElement('span');
	taskStatusSpan.className = ''; /* SWITCH STATEMENT TO DECIDE THIS */
	taskStatusSpan.innerHTML = taskStatus;
	taskStatusTD.appendChild(taskStatusSpan);

	/* Title */
	var taskTitleTD = document.createElement('td');
	var taskTitleContainer = document.createElement('div');
	var taskTitleAHREF = document.createElement('');
	taskTitleContainer.className = "table-tdIn";
	taskTitleAHREF.title = taskTitle;
	taskTitleAHREF.href = "index.php?location=timesheetPage&action=single&param=" + taskId;
	taskTitleAHREF.innerHTML = taskTitle;
	
	taskTitleContainer.appendChild(taskTitleAHREF);
	taskTitleTD.appendChild(taskTitleContainer);
	
	/* Description */
	var taskDscrTD = document.createElement('td');
	
	var taskDscrPreview = document.createElement('span');
	var taskDscrBreaker = document.createElement('span');
	var taskDscrContent = document.createElement('span');
	var max = 40;
	if(taskDscr !== undefined && taskDscr.length > max)
	{
		taskDscrPreview.innerHTML = taskDscr.substring(0, max);
		taskDscrBreaker.innerHTML = "...";
		taskDscrContent.innerHTML = taskDscr.substring(max, (taskDscrContent.innerHTML = taskDscr.length));
	}else
	{
		taskDscrPreview.innerHTML = commentContent;
	}

	/* CONTENT FINISH */
	taskDscrTD.appendChild(taskDscrTitle);
	taskDscrTD.appendChild(taskDscrPreview);
	taskDscrTD.appendChild(taskDscrBreaker);
	taskDscrTD.appendChild(taskDscrContent);
	taskDscrTD.className = "actualAccordion";
	
	/* Members */
	var taskMembersTD = document.createElement('td');
	taskTD.innerHTML = taskMembers.join(", ");
	
	/* Last Update */


	/* APPEND EVERYTHING TO TABLE ROW */
	tableRow.appendChild(tagTD);
	tableRow.appendChild(contentTD);
	tableRow.appendChild(memberIdTD);
	tableRow.appendChild(dateTD);

	if(commentSlideIn)
	{
		$(tableRow).hide().prependTo(TABLE_CONTENT_PRINT_LOCATION).fadeIn('slow');
		if(tableContent < COMMENTS_PER_PAGE)
		{
			$(TABLE_CONTENT_PRINT_LOCATION).find('>:last-child').remove();
		}
		
	}else
	{
		$(tableRow).appendTo(TABLE_CONTENT_PRINT_LOCATION);
		$(tableRow).show();
	}


}


/**
 * Create comment button
 */
$(function() {
    $("#createTaskButton").click( function()
         {
    		createTask($("#createTaskTitle").val(), $("#createTaskDscr").val(), $("#createTaskStatus option:selected").text());
         });
});

/**
 * Page on load
 */
$( document ).ready(function() {
});