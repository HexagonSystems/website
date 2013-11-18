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
function loadTasks(tableConfig, pageNum, forceLoad) {
	var arrayToLoopOver = printTableDataInTable(tableConfig, pageNum);
	if (!(arrayToLoopOver) || forceLoad) {
		$.post(ajaxBase + "Model/TaskAJAX.php", {
			request : "load",
			memberId : tableConfig['memberId'],
			pageNum : pageNum,
			qty : tableConfig['quantity_per_page']
		}, function(nakedJson) {
			nakedJson = $.parseJSON(nakedJson);
			response = nakedJson.success;
			if (response == true || response == "true") {
				var jsonObject = nakedJson.data;
				var arrayToLoopOver = updateTableContentArray(tableConfig,
						jsonObject, pageNum);
				if (arrayToLoopOver) {
					printTaskTableData(tableConfig, arrayToLoopOver);
				} else {
					loadTasks(tableConfig, pageNum);
				}
			}
		});
	} else {
		printTaskTableData(tableConfig, arrayToLoopOver);
	}
}

/**
 * Loads the newest Tasks
 * 
 * This has not been fully implemented yet
 * 
 * @param tableConfig
 */
function loadNewestTasks(tableConfig) {
	$.post(ajaxBase + "Model/TaskAJAX.php", {
		request : "loadNewest",
		memberId : tableConfig['memberId'],
		lastLoaded : tableConfig['content'][0]['id'],
	}, function(data) {
		nakedJson = $.parseJSON(nakedJson);
		response = nakedJson.success;
		if (response == true || response == "true") {
			var jsonObject = nakedJson.data;
			updateTableContentArray(tableConfig, jsonObject, pageNum);
			var arrayToLoopOver = printTableDataInTable(tableConfig, pageNum,
					true);
			if (arrayToLoopOver) {
				printTaskTableData(tableConfig, arrayToLoopOver);
			} else {
				loadNewestTasks(tableConfig);
			}
		}
	});
}

/**
 * Creates a task in the database
 */
function createTask(tableConfig, taskTitle, taskDescription, taskStatus) {
	$.post(ajaxBase + "Model/TaskAJAX.php", {
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
			/* ASSUME FIRST NAME IS CORRECT */
			var fakeMemberArray = {};
			fakeMemberArray[tableConfig['memberId']] = "-";
			var jsonString = JSON.stringify(fakeMemberArray);
			fakeJSONObject = JSON.parse(jsonString)

			var arrayToUnshift = new Array();
			arrayToUnshift['id'] = task.id;
			arrayToUnshift['title'] = task.title;
			arrayToUnshift['content'] = task.content;
			arrayToUnshift['status'] = task.status;
			arrayToUnshift['members'] = fakeJSONObject;
			arrayToUnshift['lastUpdate'] = comment;

			tableConfig['content'].unshift(arrayToUnshift);

			printSingleTask(tableConfig, task.id, task.title, task.content,
					task.status, fakeJSONObject,
					tableConfig['memberFirstName'], comment.date, true);

			assignTableContentAccordion();
		} else {
			alert("success = " + data.success + " " + data);
		}
	});
}

/**
 * Updates a tasks information in the database
 */
function editTask(tableConfig, taskId, taskTitle, taskDescription, taskStatus) {
	$.post(ajaxBase + "Model/TaskAJAX.php", {
		request : "edit",
		memberId : tableConfig['memberId'],
		taskId : taskId,
		title : taskTitle,
		content : taskDescription,
		status : taskStatus
	}, function(data) {
		data = $.parseJSON(data);
		response = data.success;
		if (response == true || response == "true") {
			$(tableConfig['titleLocation']).text(taskTitle);
			$(tableConfig['contentLocation']).text(taskDescription);
			$(tableConfig['statusLocation']).text(taskStatus);
		} else {
			alert("Soemthing went wrong");
		}
	});
}

/**
 * Prints the comments into the comment table
 */
function printTableDataInTableOLD(tableConfig, pageNum) {

	// If the page of comments isn't already loaded, load it
	if (pageAlreadyLoaded(tableConfig, pageNum) === false
			&& pageNum != tableConfig['last_page']) {
		loadTableData(tableConfig, pageNum);
	} else {
		var positionToStartOn = (pageNum - 1)
				* tableConfig['quantity_per_page'];
		var positionToEndOn = positionToStartOn
				+ tableConfig['quantity_per_page'];

		var arrayToLoopOver = tableConfig['content'].concat();

		if (tableConfig['last_page'] > -1
				&& tableConfig['last_page'] == pageNum) {
			arrayToLoopOver = arrayToLoopOver.slice(positionToStartOn);
		} else {
			arrayToLoopOver = arrayToLoopOver.slice(positionToStartOn,
					positionToEndOn);
		}

		emptyTableBody(tableConfig);
		return arrayToLoopOver;
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
function printSingleTask(tableConfig, taskId, taskTitle, taskDscr, taskStatus,
		taskMembers, taskLastUpdateMemberId, taskLastUpdateDate, commentSlideIn) {
	/* TABLE ROW */
	var tableRow = document.createElement('tr');
	tableRow.className = 'parentOfAccordion';

	/* STATUS */
	var taskStatusTD = document.createElement('td');
	var taskStatusSpan = document.createElement('span');
	var taskStatusClass = "label label-";
	switch (taskStatus) {
	case 'Needs Attention':
		taskStatusClass += "danger";
		break;
	case 'Completed':
		taskStatusClass += "success";
		break;
	case 'In Progress':
		taskStatusClass += "primary";
		break;
	case 'Discontinued':
		taskStatusClass += "default";
		break;
	default:
		taskStatusClass += "warning";
	}

	taskStatusSpan.className = taskStatusClass; /*
												 * SWITCH STATEMENT TO DECIDE
												 * THIS
												 */
	taskStatusSpan.innerHTML = taskStatus;
	taskStatusTD.appendChild(taskStatusSpan);

	/* Members */
	var taskMembersTD = document.createElement('td');
	var taskMemberSingleSpan = document.createElement('span');
	if (taskMembers.length == 0) {
		taskMemberSingleSpan.innerHTML = "-";
		taskMembersTD.appendChild(taskMemberSingleSpan);
	} else {
		$.each(taskMembers, function(member) {
			taskMemberSingleSpan.innerHTML = taskMembers[member];
			taskMembersTD.appendChild(taskMemberSingleSpan);
			taskMembersTD.innerHTML += ", ";
		});
	}

	taskMembersTD.innerHTML = taskMembersTD.innerHTML.slice(0, -2);

	/* Last Update */
	var taskLastUpdateTD = document.createElement('td');

	var taskMemberSingleSpan = document.createElement('span');
	taskMemberSingleSpan.innerHTML = taskLastUpdateMemberId;
	taskLastUpdateTD.appendChild(taskMemberSingleSpan);
	taskLastUpdateTD.innerHTML += " on " + taskLastUpdateDate;

	/* Title */
	var taskTitleAHREF = document.createElement('a');
	taskTitleAHREF.title = taskTitle;
	taskTitleAHREF.href = "index.php?location=timesheetPage&action=single&param="
			+ taskId;

	var taskTitleAHREFInner = document.createElement('strong');
	taskTitleAHREFInner.innerHTML = taskTitle + "<br/>";
	taskTitleAHREF.appendChild(taskTitleAHREFInner);

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

	/* CONTENT RESPONSIVE */
	var contentResponsive = document.createElement('small');

	var contentResponsiveMembers = document.createElement('p');
	contentResponsiveMembers.innerHTML = "<br/><br/>Members: "
			+ taskMembersTD.innerHTML;
	var contentResponsiveLastUpdate = document.createElement('p');
	contentResponsiveLastUpdate.innerHTML = "Last updated by "
			+ taskLastUpdateTD.innerHTML + "<br/><br/>";

	contentResponsive.appendChild(contentResponsiveMembers);
	contentResponsive.appendChild(contentResponsiveLastUpdate);

	var contentResponsiveStatus = taskStatusSpan.cloneNode();

	contentResponsiveStatus.className = "visible-xs";
	contentResponsive.className = "visible-xs";

	contentResponsiveStatus.className += " pull-right width-auto margin-left-m "
			+ taskStatusClass;
	contentResponsiveStatus.innerHTML += "<br/>";

	/* CONTENT FINISH */
	taskDscrTD.appendChild(contentResponsiveStatus);
	taskDscrTD.appendChild(taskTitleAHREF);
	taskDscrTD.appendChild(taskDscrPreview);
	taskDscrTD.appendChild(taskDscrBreaker);
	taskDscrTD.appendChild(taskDscrContent);
	taskDscrTD.className = "actualAccordion";
	taskDscrTD.appendChild(contentResponsive);

	/* APPEND EVERYTHING TO TABLE ROW */
	tableRow.appendChild(taskStatusTD);
	tableRow.appendChild(taskDscrTD);
	tableRow.appendChild(taskMembersTD);
	tableRow.appendChild(taskLastUpdateTD);

	if (commentSlideIn) {
		$(tableRow).hide().prependTo(tableConfig['print_location']).fadeIn(
				'slow');
		if (tableConfig['content'].length > tableConfig['quantity_per_page']) {
			$(tableConfig['print_location']).find('>:last-child').remove();
		}

	} else {
		$(tableRow).appendTo(tableConfig['print_location']);
		$(tableRow).show();
	}
}

function printTaskTableData(tableConfig, arrayToLoopOver) {
	emptyTableBody(tableConfig);
	$.each(arrayToLoopOver, function(singleArray) {
		printSingleTask(tableConfig, arrayToLoopOver[singleArray]['id'],
				arrayToLoopOver[singleArray]['title'],
				arrayToLoopOver[singleArray]['content'],
				arrayToLoopOver[singleArray]['status'],
				arrayToLoopOver[singleArray]['members'],
				arrayToLoopOver[singleArray]['lastUpdate'].memberId,
				arrayToLoopOver[singleArray]['lastUpdate'].postedDate, false);
	});
	assignTableContentAccordion();
}
