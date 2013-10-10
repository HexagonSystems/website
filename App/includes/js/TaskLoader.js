/* CONFIG */
var tableContent = new Array();
var COMMENTS_PER_PAGE = 5;
var lastPage = -1;
var TABLE_CONTENT_PRINT_LOCATION = "#tasksContainer";

/* FUNCTIONS */
/**
 * Load Comments through JSON
 */
function loadTableData(pageNum) {
	if (quantity = undefined) {
		quantity = COMMENTS_PER_PAGE;
	}
	$.post(ajaxUrl, {
		request : "load",
		memberId : 1,
		pageNum : pageNum,
		qty : COMMENTS_PER_PAGE
	}, function(nakedJson) {
		var jsonObject = $.parseJSON(nakedJson);
		updateTableContentArray(jsonObject, pageNum);
	});
}

/**
 * Creates a comment in the database
 */
function createTask(taskTitle, taskDescription, taskStatus) {
	$.post(ajaxUrl, {
		request : "create",
		memberId : 1,
		title : taskTitle,
		content : taskDescription,
		status : taskStatus
	}, function(data) {
		data = $.parseJSON(data);
		response = data.success;
		if (response == true || response == "true") {
			task = data.task.data;
			hours = data.hours.data;
			comment = data.comment.data;
			printSingleTask(task.id, task.title, task.content, task.status, task.members,
		comment.memberId, comment.date, true);
		} else {
			alert("success = " + data.success + " " + data);
		}
	});
}

/**
 * Prints the comments into the comment table
 */
function printTableDataInTable(pageNum) {
	
	// If the page of comments isn't already loaded, load it
	if (pageAlreadyLoaded(pageNum, tableContent) === false && pageNum != lastPage) {
		loadTableData(pageNum);
	} else {
		var positionToStartOn = (pageNum - 1) * COMMENTS_PER_PAGE;
		var positionToEndOn = positionToStartOn + COMMENTS_PER_PAGE;

		var arrayToLoopOver = tableContent.concat();

		if (lastPage > -1 && lastPage == pageNum) {
			arrayToLoopOver = arrayToLoopOver.slice(positionToStartOn);
		} else {
			arrayToLoopOver = arrayToLoopOver.slice(positionToStartOn, positionToEndOn);
		}

		emptyTableBody();
		
		$.each(arrayToLoopOver, function(singleArray) {
			printSingleTask(arrayToLoopOver[singleArray]['id'],
					arrayToLoopOver[singleArray]['title'],
					arrayToLoopOver[singleArray]['content'],
					arrayToLoopOver[singleArray]['status'],
					arrayToLoopOver[singleArray]['members'], arrayToLoopOver[singleArray]['lastUpdate'].memberId, arrayToLoopOver[singleArray]['lastUpdate'].postedDate, false);
		});
	}

	assignTableContentAccordion()
}

/**
 * Prints a single comment
 */
function printSingleTask(taskId, taskTitle, taskDscr, taskStatus, taskMembers,
		taskLastUpdateMemberId, taskLastUpdateDate, commentSlideIn) {
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
	var taskTitleAHREF = document.createElement('a');
	taskTitleAHREF.title = taskTitle;
	taskTitleAHREF.href = "index.php?location=timesheetPage&action=single&param=" + taskId;
	taskTitleAHREF.innerHTML = taskTitle + "<br />";

	/* Description */
	var taskDscrTD = document.createElement('td');

	var taskDscrPreview = document.createElement('span');
	var taskDscrBreaker = document.createElement('span');
	var taskDscrContent = document.createElement('span');
	var max = 40;
	if (taskDscr !== undefined && taskDscr.length > max) {
		taskDscrPreview.innerHTML = taskDscr.substring(0, max);
		taskDscrBreaker.innerHTML = "...";
		taskDscrContent.innerHTML = taskDscr.substring(max,
				(taskDscrContent.innerHTML = taskDscr.length));
	} else {
		taskDscrPreview.innerHTML = taskDscr;
	}

	/* CONTENT FINISH */
	taskDscrTD.appendChild(taskTitleAHREF);
	taskDscrTD.appendChild(taskDscrPreview);
	taskDscrTD.appendChild(taskDscrBreaker);
	taskDscrTD.appendChild(taskDscrContent);
	taskDscrTD.className = "actualAccordion";
	
	/* Members */
	var taskMembersTD = document.createElement('td');
	$.each(taskMembers, function(member) {
		taskMembersTD.innerHTML += member + " ";
	});

	/* Last Update */
	var taskLastUpdateTD = document.createElement('td');
	taskLastUpdateTD.innerHTML = taskLastUpdateMemberId + " on " + taskLastUpdateDate;
	
	/* APPEND EVERYTHING TO TABLE ROW */
	tableRow.appendChild(taskStatusTD);
	tableRow.appendChild(taskDscrTD);
	tableRow.appendChild(taskMembersTD);
	tableRow.appendChild(taskLastUpdateTD);

	if (commentSlideIn) {
		$(tableRow).hide().prependTo(TABLE_CONTENT_PRINT_LOCATION).fadeIn(
				'slow');
		if (tableContent.length = COMMENTS_PER_PAGE) {
			$(TABLE_CONTENT_PRINT_LOCATION).find('>:last-child').remove();
		}

	} else {
		$(tableRow).appendTo(TABLE_CONTENT_PRINT_LOCATION);
		$(tableRow).show();
	}
}

/**
 * Comment section paginator on click event
 */
$(function() {
	$(".pagination li a").click(function() {
		printTableDataInTable($(this).text());
	});
});

/**
 * Create comment button
 */
$(function() {
	$("#createTaskButton").click(
			function() {
				createTask($("#createTaskTitle").val(), $("#createTaskDscr")
						.val(), $("#createTaskStatus option:selected").text());
			});
});

/**
 * Page on load
 */
$(document).ready(function() {
	printTableDataInTable(1);
});