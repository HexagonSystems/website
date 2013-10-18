/* FUNCTIONS */
/**
 * Load Comments through JSON
 */
function loadComments(tableConfig, pageNum) {
	if (quantity = undefined) {
		quantity = commentsPerPage;
	}
	$.post(ajaxUrl, {
		request : "load",
		taskId : tableConfig['taskId'],
		memberId : tableConfig['memberId'],
		pageNum : pageNum,
		qty : 5
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
 * Prints the comments into the comment table
 */
function printTableDataInTable(tableConfig, pageNum) {
	// If the page of comments isn't already loaded, load it
	if (pageAlreadyLoaded(tableConfig, pageNum) === false
			&& pageNum != tableConfig['last_page']) {
		loadComments(tableConfig, pageNum);
	} else {
		var positionToStartOn = (pageNum - 1)
				* tableConfig['quantity_per_page'];
		var positionToEndOn = positionToStartOn
				+ tableConfig['quantity_per_page'];

		var arrayToLoopOver = tableConfig['content'].concat();

		if (tableConfig['last_page'] > -1
				&& tableConfig['last_page'] == pageNum) {
			arrayToLoopOver = arrayToLoopOver.splice(positionToStartOn);
		} else {
			arrayToLoopOver = arrayToLoopOver.splice(positionToStartOn,
					positionToEndOn);
		}

		emptyTableBody(tableConfig);

		$.each(arrayToLoopOver, function(singleArray) {
			printSingleComment(tableConfig,
					arrayToLoopOver[singleArray]['tag'],
					arrayToLoopOver[singleArray]['title'],
					arrayToLoopOver[singleArray]['content'],
					arrayToLoopOver[singleArray]['memberId'],
					arrayToLoopOver[singleArray]['date'], false);
		});
	}

	assignTableContentAccordion()
}

/**
 * Prints a single comment
 */
function printSingleComment(tableConfig, commentTag, commentTitle,
		commentContent, commentMember, commentDate, commentSlideIn) {
	/* TABLE ROW */
	var tableRow = document.createElement('tr');
	tableRow.className = 'parentOfAccordion';

	/* TAG */
	var tagTD = document.createElement('td');
	var tagAHREF = document.createElement('a');
	tagAHREF.title = commentTag;
	tagAHREF.href = "#";
	tagAHREF.innerHTML = commentTag;
	tagTD.appendChild(tagAHREF);

	/* CONTENT */
	var contentTD = document.createElement('td');

	/* CONTENT TITLE */
	var contentTitle = document.createElement('p');
	if (commentTitle !== undefined && commentTitle !== null
			&& commentTitle !== "") {
		contentTitle.innerHTML = commentTitle;
	} else {
		contentTitle.innerHTML = "Title not set";
	}

	/* CONTENT */
	var contentPreview = document.createElement('span');
	var contentBreaker = document.createElement('span');
	var contentContent = document.createElement('span');
	var max = 40;
	if (commentContent !== undefined && commentContent.length > max) {
		contentPreview.innerHTML = commentContent.substring(0, max);
		contentBreaker.innerHTML = "...";
		contentContent.innerHTML = commentContent.substring(max,
				(contentContent.innerHTML = commentContent.length));
	} else {
		contentPreview.innerHTML = commentContent;
	}

	/* CONTENT FINISH */
	contentTD.appendChild(contentTitle);
	contentTD.appendChild(contentPreview);
	contentTD.appendChild(contentBreaker);
	contentTD.appendChild(contentContent);
	contentTD.className = "actualAccordion";

	/* MEMBER */
	var memberIdTD = document.createElement('td');
	memberIdTD.innerHTML = commentMember;

	/* DATE */
	var dateTD = document.createElement('td');
	dateTD.innerHTML = commentDate;

	/* APPEND EVERYTHING TO TABLE ROW */
	tableRow.appendChild(tagTD);
	tableRow.appendChild(contentTD);
	tableRow.appendChild(memberIdTD);
	tableRow.appendChild(dateTD);

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

/**
 * Creates a comment in the database
 */
function createComment(tableConfig, commentTag, commentTitle, commentContent) {
	commentTag = "@" + commentTag;
	$.post(ajaxUrl, {
		request : "create",
		taskId : tableConfig['taskId'],
		memberId : tableConfig['memberId'],
		title : commentTitle,
		content : commentContent,
		tag : commentTag
	}, function(data) {
		data = $.parseJSON(data);
		response = data.success;
		if (response == true) {
			// Run function to check for updats, therefore asking the user to
			// refresh
			// Or refresh automatically, or just add the comment in locally,
			// this will ignore the fact if other comments have been added
			// around the same time as well
			// Maybe it could do both, check for new updates, if there arnt any
			// add this locally, if there are refresh or ask the user to refresh
			var tempArray = new Array();
			tempArray['tag'] = commentTag;
			tempArray['title'] = commentTitle;
			tempArray['content'] = commentContent;
			tempArray['memberId'] = 1;
			tempArray['date'] = data.data.date;
			tableConfig['content'].unshift(tempArray);
			printSingleComment(tableConfig, commentTag, commentTitle,
					commentContent, tempArray['memberId'], tempArray['date'],
					true);
			assignTableContentAccordion()
		} else {
			alert(data);
		}
	});
}

/**
 * Adds hours into the database
 */
function addHours(tableConfig, workedDate, workedHours, workedComment) {
	$.post(ajaxBase + "", {
		request : "addHours",
		taskId : tableConfig['taskId'],
		memberId : tableConfig['memberId'],
		workedDate : "03/10/2013",
		workedHours : workedHours,
		workedComment : workedComment
	}, function(data) {
		data = $.parseJSON(data);
		response = data.success;
		if (response == true) {
			// alert(response);
			commentJSON = data.comment.data;
			var tempArray = new Array();
			tempArray['tag'] = commentJSON.tag;
			tempArray['title'] = commentJSON.title;
			tempArray['content'] = commentJSON.content;
			tempArray['memberId'] = 1;
			tempArray['date'] = commentJSON.date;
			tableConfig['content'].unshift(tempArray);
			printSingleComment(tableConfig, tempArray['tag'], tempArray['title'],
					tempArray['content'], tempArray['memberId'], tempArray['date'],
					true);
			assignTableContentAccordion()
		} else {
			alert("Failed");
		}
	});
}