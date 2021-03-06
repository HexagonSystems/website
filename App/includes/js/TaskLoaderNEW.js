/*
 * TableTasktableConfig array will include:
 * 	- memberId
 * 	- quantity_per_page
 * 	- print_location
 *  - last_page
 *  - content
 */

/* FUNCTIONS */
/**
 * Load Comments through JSON
 */
function loadTableData(tableConfig, pageNum) {
	if (quantity = undefined) {
		quantity = COMMENTS_PER_PAGE;
	}
	$.post(ajaxBase + "/Model/TaskAJAX.php", {
		request : "load",
		memberId : tableConfig['memberId'],
		pageNum : pageNum,
		qty : tableConfig['quantity_per_page']
	}, function(nakedJson) {
		nakedJson = $.parseJSON(nakedJson);
		response = nakedJson.success;
		if (response == true || response == "true") {
			var jsonObject = nakedJson.data;
			updateTableContentArray(tableConfig, jsonObject, pageNum);
		}
	});
}

/**
 * Creates a comment in the database
 */
function createTask(tableConfig, taskTitle, taskDescription, taskStatus) {
	$.post(ajaxBase + "/Model/TaskAJAX.php", {
		request : "create",
		memberId : tableConfig['memberId'],
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
			printSingleTask(tableConfig, task.id, task.title, task.content, task.status,
					task.members, comment.memberId, comment.date, true);
		} else {
			alert("Create task failed");
		}
	});
}

/**
 * Prints the comments into the comment table
 */
function printTableDataInTable(tableConfig, pageNum) {

	// If the page of comments isn't already loaded, load it
	if (pageAlreadyLoaded(tableConfig, pageNum) === false
			&& pageNum != tableConfig['last_page']) {
		loadTableData(tableConfig, pageNum);
	} else {
		var positionToStartOn = (pageNum - 1) * tableConfig['quantity_per_page'];
		var positionToEndOn = positionToStartOn + tableConfig['quantity_per_page'];

		var arrayToLoopOver = tableConfig['content'].concat();

		if (tableConfig['last_page'] > -1 && tableConfig['last_page'] == pageNum) {
			arrayToLoopOver = arrayToLoopOver.slice(positionToStartOn);
		} else {
			arrayToLoopOver = arrayToLoopOver.slice(positionToStartOn,
					positionToEndOn);
		}

		emptyTableBody(tableConfig);

		$.each(arrayToLoopOver, function(singleArray) {
			printSingleTask(tableConfig, arrayToLoopOver[singleArray]['id'],
					arrayToLoopOver[singleArray]['title'],
					arrayToLoopOver[singleArray]['content'],
					arrayToLoopOver[singleArray]['status'],
					arrayToLoopOver[singleArray]['members'],
					arrayToLoopOver[singleArray]['lastUpdate'].memberId,
					arrayToLoopOver[singleArray]['lastUpdate'].postedDate,
					false);
		});
	}

	assignTableContentAccordion()
}

/**
 * Prints a single comment
 */
function printSingleTask(tableConfig, taskId, taskTitle, taskDscr, taskStatus, taskMembers,
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
	taskTitleAHREF.href = "index.php?location=timesheetPage&action=single&param="
			+ taskId;
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
	taskLastUpdateTD.innerHTML = taskLastUpdateMemberId + " on "
			+ taskLastUpdateDate;

	/* APPEND EVERYTHING TO TABLE ROW */
	tableRow.appendChild(taskStatusTD);
	tableRow.appendChild(taskDscrTD);
	tableRow.appendChild(taskMembersTD);
	tableRow.appendChild(taskLastUpdateTD);

	if (commentSlideIn) {
		$(tableRow).hide().prependTo(tableConfig['print_location']).fadeIn(
				'slow');
		if (tableContent.length = tableConfig['quantity_per_page']) {
			$(tableConfig['print_location']).find('>:last-child').remove();
		}

	} else {
		$(tableRow).appendTo(tableConfig['print_location']);
		$(tableRow).show();
	}
}