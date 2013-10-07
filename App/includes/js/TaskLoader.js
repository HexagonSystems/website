/* CONFIG */
var tableContent = new Array();
var COMMENTS_PER_PAGE = 5;
var TABLE_CONTENT_PRINT_LOCATION = "#commentsContainer";

/* FUCNTIONS */
/**
 * Load Comments through JSON
 */
 function loadComments(pageNum){
		if(quantity = undefined)
		{
			quantity = commentsPerPage;
		}
		$.post( ajaxUrl, { request: "load", taskId: taskId, memberId: 1, pageNum: pageNum,  qty: 5 }, 
	    		function( nakedJson )
	    	    {
			 		var jsonObject = $.parseJSON(nakedJson);
			 		
			 		updateTableContentArray(jsonObject, pageNum);
				 }
		 );
}

/**
 * Prints the comments into the comment table
 */
function printCommentsInTable(pageNum) {
	// If the page of comments isn't already loaded, load it
	if(pageAlreadyLoaded(pageNum) === false)
	{
		loadComments(pageNum);
	}else
	{
		var positionToStartOn = ( pageNum - 1 ) * COMMENTS_PER_PAGE;
		var positionToEndOn = positionToStartOn + COMMENTS_PER_PAGE;
		
		emptyTableBody();
		
		for(var counter = positionToStartOn; counter < positionToEndOn; counter++)
		{
			currentComment = tableContent[counter];
			printSingleComment(currentComment['tag'], currentComment['title'], currentComment['content'], currentComment['memberId'], currentComment['date'], false);
		}
	}

	assignTableContentAccordion()
}

/**
 * Prints a single comment
 */
function printSingleComment(commentTag, commentTitle, commentContent, commentMember, commentDate, commentSlideIn)
{
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
	if(commentTitle !== undefined && commentTitle !== null && commentTitle !== "")
	{
		contentTitle.innerHTML = commentTitle;
	}else
	{
		contentTitle.innerHTML = "Title not set";
	}

	/* CONTENT */
	var contentPreview = document.createElement('span');
	var contentBreaker = document.createElement('span');
	var contentContent = document.createElement('span');
	var max = 40;
	if(commentContent !== undefined && commentContent.length > max)
	{
		contentPreview.innerHTML = commentContent.substring(0, max);
		contentBreaker.innerHTML = "...";
		contentContent.innerHTML = commentContent.substring(max, (contentContent.innerHTML = commentContent.length));
	}else
	{
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

	if(commentSlideIn)
	{
		$(tableRow).hide().prependTo(TABLE_CONTENT_PRINT_LOCATION).fadeIn('slow');
		$(TABLE_CONTENT_PRINT_LOCATION).find('>:last-child').remove();
	}else
	{
		$(tableRow).appendTo(TABLE_CONTENT_PRINT_LOCATION);
		$(tableRow).show();
	}


}

/**
 * Creates a comment in the database
 */
function createComment(commentTag, commentTitle, commentContent)
{
	commentTag = "@" + commentTag;
	 $.post( ajaxUrl, { request: "create", taskId: taskId, memberId: 1, title: commentTitle, content: commentContent,  tag: commentTag}, 
		 		function( data )
		 	    {
					    if(data == "true")
					    {
							// Run function to check for updats, therefore asking the user to refresh
							// Or refresh automatically, or just add the comment in locally, this will ignore the fact if other comments have been added around the same time as well
							// Maybe it could do both, check for new updates, if there arnt any add this locally, if there are refresh or ask the user to refresh
					    	var tempArray = new Array();
							tempArray['tag'] = commentTag;
							tempArray['title'] = commentTitle;
							tempArray['content'] = commentContent;
							tempArray['memberId'] = 1;
							tempArray['date'] = "Refresh to view date";
							tableContent.unshift(tempArray);
							printSingleComment(commentTag, commentTitle, commentContent, tempArray['memberId'], tempArray['date'], true);
							assignTableContentAccordion()
					    }else
					    {
						    alert(data);
					    }
					 });
}



/**
 * Adds hours into the database
 */
function addHours(workedDate, workedHours)
{
	 $.post( ajaxUrl, { request: "addHours", taskId: taskId, memberId: 1, workedDate: "03/10/2013", workedHours: workedHours}, 
		 		function( data )
		 	    {
					    if(data == "true")
					    {
							// Run function to check for updats, therefore asking the user to refresh
							// Or refresh automatically, or just add the comment in locally, this will ignore the fact if other comments have been added around the same time as well
							// Maybe it could do both, check for new updates, if there arnt any add this locally, if there are refresh or ask the user to refresh
					    }else
					    {
						    alert(data);
					    }
					 });
}


/**
 * Create comment button
 */
$(function() {
    $("#createCommentButton").click( function()
         {
    		createComment($("#inputTaskTag").val(), $("#inputTaskTitle").val(), $("#inputTaskContent").val());
         });
});

/**
 * Add hours button
 */
 $(function() {
	    $("#addHoursButton").click( function()
	         {
	         	// run script to add hours through ajax
	         	var titleString = "Alex has added " + $("#addHoursHours").val() + " hours  worked on " + document.getElementById("addHoursDate").value;
	         	addHours(document.getElementById("addHoursDate").value, $("#addHoursHours").val());
	    		createComment("HoursAdded", titleString, $("#addHoursComment").val());
	         });
	});

/**
 * Comment section paginator on click event
 */
$(function() {
    $(".pagination li a").click( function()
         {
			printCommentsInTable($(this).text());
         });
});

/**
 * jQuery Datepicker
 */

/**
 * Page on load
 */
$( document ).ready(function() {
	printCommentsInTable(1);
	document.getElementById('addHoursDate').valueAsDate = new Date();
});