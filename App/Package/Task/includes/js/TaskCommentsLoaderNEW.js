/* FUNCTIONS */
/**
 * Load Comments through JSON
 */
function loadComments(tableConfig, pageNum, forceLoad) {
	var arrayToLoopOver = printTableDataInTable(tableConfig, pageNum);
	if (!(arrayToLoopOver) || forceLoad) {
		$.post(ajaxBase + "Model/TaskCommentsAJAX.php", {
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
				var arrayToLoopOver = printTableDataInTable(tableConfig,
						pageNum, true);
				if (arrayToLoopOver) {
					printCommentTableData(tableConfig, arrayToLoopOver);
				} else {
					loadComments(tableConfig, pageNum);
				}

			}
		});
	} else {
		printCommentTableData(tableConfig, arrayToLoopOver);
	}

}

/**
 * Loads the newest comments for a specific Task
 * 
 * If 5 comments are returned the current tableConfig['content'] array is wiped
 * and filled with the new 5 comments. If < 5 comments are loaded, they are
 * simply added to the start of the array and printed to the screen. This is to
 * provide a work around if the user hasn't loaded the page in quite some time,
 * this method will given them a clean way of loading all of the new comments.
 * 
 * @param tableConfig
 */
function loadNewestComments(tableConfig) {
	$.post(ajaxBase + "Model/TaskCommentsAJAX.php", {
		request : "loadNewest",
		taskId : tableConfig['taskId'],
		memberId : tableConfig['memberId'],
		lastLoaded : tableConfig['content'][0]['date'],
		qty : 5
	}, function(nakedJson) {
		console.log("Finished loading newest comments");
		console.log(nakedJson);
		nakedJson = $.parseJSON(nakedJson);
		response = nakedJson.success;
		if (response == true || response == "true") {
			var jsonData = nakedJson.data;
			jsonData.reverse();
			console.log("About to print");
			$.each(jsonData, function(id) {
				tableConfig['content'].unshift(jsonData[id]); // Add to the
				// start of the
				// array
				printSingleComment(tableConfig, jsonData[id]['tag'],
						jsonData[id]['title'], jsonData[id]['content'],
						jsonData[id]['memberId'], jsonData[id]['date'], true);
			});
			assignCommentTagClick();
			assignTableContentAccordion();
			if (jsonData.length >= 5) {
				tableConfig['content'] = tableConfig['content'].slice(0, 5);
				findLastPage(tableConfig, 1);
			}

			/* PAGINATOR */
			var countResponse = nakedJson.count;
			console.log("About to handle countResponse");
			if (countResponse.success == true) {
				$(tableConfig['paginatorLocation']).html(
						countResponse.data.html);
			} else {
				console.log("Paginator update failed");
			}
		}
	});

	/*
	 * var tempArray = new Array(); tempArray['tag'] = commentTag;
	 * tempArray['title'] = commentTitle; tempArray['content'] = commentContent;
	 * tempArray['memberId'] = tableConfig['memberId']; tempArray['date'] =
	 * data.data.date; tableConfig['content'].unshift(tempArray);
	 * printSingleComment(tableConfig, commentTag, commentTitle, commentContent,
	 * tempArray['memberId'], tempArray['date'], true);
	 * assignTableContentAccordion(); assignCommentTagClick();
	 */
}

/**
 * Prints the comments into the comment table
 */
function printTableDataInTableOLD(tableConfig, pageNum) {
	// If the page of comments isn't already loaded, load it
	if (pageAlreadyLoaded(tableConfig, pageNum) === false
			&& pageNum >= tableConfig['last_page']) {
		loadComments(tableConfig, pageNum);
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
	tagAHREF.className = "commentTag";
	tagAHREF.innerHTML = commentTag;
	$(tagAHREF).attr("data-toggle", "modal");
	tagTD.appendChild(tagAHREF);

	/* CONTENT */
	var contentTD = document.createElement('td');

	/* CONTENT TITLE */
	var contentTitle = document.createElement('strong');
	if (commentTitle !== undefined && commentTitle !== null
			&& commentTitle !== "") {
		contentTitle.innerHTML = commentTitle;
	} else {
		contentTitle.innerHTML = "Title not set";
	}
	contentTitle.innerHTML += "<br/><br/>";

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

	/* CONTENT RESPONSIVE */
	var contentResponsive = document.createElement('small');
	contentResponsive.innerHTML = "<br/><br/>Posted by " + commentMember
			+ " on " + commentDate;
	
	var responsiveTag = document.createElement('i');
	responsiveTag.appendChild(tagAHREF.cloneNode(true));
	
	responsiveTag.className = "visible-xs";
	contentResponsive.className = "visible-xs";
	
	responsiveTag.className += " pull-right label side-border width-auto margin-left-m";
	
	/* CONTENT FINISH */
	contentTD.appendChild(responsiveTag);
	contentTD.appendChild(contentTitle);
	contentTD.appendChild(contentPreview);
	contentTD.appendChild(contentBreaker);
	contentTD.appendChild(contentContent);
	contentTD.appendChild(contentResponsive);

	contentTD.className = "actualAccordion";

	/* MEMBER */
	var memberIdTD = document.createElement('td');
	memberIdTD.innerHTML = commentMember;

	/*
	 * var taskMemberSingleAHREF = document.createElement('a');
	 * taskMemberSingleAHREF.title = taskMembers[member];
	 * taskMemberSingleAHREF.href = "#"; taskMemberSingleAHREF.innerHTML =
	 * taskMembers[member]; taskMembersTD.appendChild(taskMemberSingleAHREF);
	 * taskMembersTD.innerHTML += ", "; memberIdTD.appendChild(taskMembersTD);
	 */

	/* DATE */
	var dateTD = document.createElement('td');
	dateTD.innerHTML = commentDate;

	/* APPEND EVERYTHING TO TABLE ROW */
	tableRow.appendChild(tagTD);
	tableRow.appendChild(contentTD);
	tableRow.appendChild(memberIdTD);
	tableRow.appendChild(dateTD);

	if (commentSlideIn) {
		console.log("Sliding in comment " + commentTitle);
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
	$.post(ajaxBase + "Model/TaskCommentsAJAX.php", {
		request : "create",
		taskId : tableConfig['taskId'],
		memberId : tableConfig['memberId'],
		title : commentTitle,
		content : commentContent,
		tag : commentTag
	}, function(data) {
		console.log(data);
		data = $.parseJSON(data);
		response = data.success;
		if (response == true) {
			loadNewestComments(tableConfig);
		} else {
			alert(data);
		}
	});
}
/**
 * Adds hours into the database
 */
function addHours(tableConfig, workedDate, workedHours, workedComment) {
	$.post(ajaxBase + "Model/TaskHoursAJAX.php", {
		request : "addHours",
		taskId : tableConfig['taskId'],
		memberId : tableConfig['memberId'],
		memberFirstName : tableConfig['memberFirstName'],
		workedDate : workedDate,
		workedHours : workedHours,
		workedComment : workedComment
	}, function(data) {
		data = $.parseJSON(data);
		response = data.success;
		if (response == true) {
			loadNewestComments(tableConfig);
		} else {
			alert(data);
		}
	});
}

function assignCommentTagClick() {
	/**
	 * Add hours button
	 * 
	 */
	$(function() {
		$(".commentTag").click(function() {
			tempTagString = $(this).text();
			// $(".searchModalButton").prop('href')
			$('.searchModalButton').attr('href', function(i, a) {
				return a + "&tag_value=" + tempTagString
			});
			// document.getElementByClass('searchModalButton').href += "&" +
			// tempTagString;
			$('#modal_pickSearchMethod').modal('show');
		});
	});
}

function printCommentTableData(tableConfig, arrayToLoopOver) {
	emptyTableBody(tableConfig);

	$.each(arrayToLoopOver, function(singleArray) {
		printSingleComment(tableConfig, arrayToLoopOver[singleArray]['tag'],
				arrayToLoopOver[singleArray]['title'],
				arrayToLoopOver[singleArray]['content'],
				arrayToLoopOver[singleArray]['memberId'],
				arrayToLoopOver[singleArray]['date'], false);
	});

	assignTableContentAccordion();

	assignCommentTagClick();
}