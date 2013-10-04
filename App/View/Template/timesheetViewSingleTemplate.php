<header class="page-header relative">
	<h3>
		<?php echo $data['task']->getTitle(); ?>
		<small><?php echo $data['task']->getStatus(); ?> </small>
	</h3>
	<article>
		Members
		<ul>
			<?php foreach($data['task']->getMembers() as $member) {
				echo "<li>$member</li>";
		} ?>
		</ul>
	</article>
	<article>
		<?php echo $data['task']->getContent(); ?>
	</article>
</header>

<!-- Button trigger modal -->
<a data-toggle="modal" href="#modal_comment" class="btn btn-primary btn-sm">Add
	Update</a>
<a data-toggle="modal" href="#modal_hours"
	class="btn btn-primary btn-sm">Add Hours</a>
<button class="btn btn-primary btn-sm">Edit Task</button>
<?php include_once 'modal_comment.php'; ?>
<?php include_once 'modal_hours.php'; ?>


<table id="testtable"
	class="table table-rowBorder table-responsive table-hover table-zebra">

	<thead>
		<th class="table-colSmall">Tag</th>
		<th class="table-colLarge">Update</th>
		<th class="table-colMedium">Posted By</th>
		<th class="table-colMedium">Posted on</th>
	</thead>

	<tbody id="commentsContainer">
		<?php /*foreach($data['comments'] as $tempTask) {
		include AppBase.'/View/Template/timesheetViewSingle_singleCommentRowTemplate.php';
		}*/ ?>
	</tbody>
</table>

<div class="text-center">
	<ul class="pagination">
		<li><a href="#">&laquo;</a></li>
		<li><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#">&raquo;</a></li>
	</ul>
</div>

<script>
ajaxUrl = "<?php echo AppBaseSTRIPPED; ?>Model/TaskCommentsAJAX.php";


var arrayOfComments = new Array();
var COMMENTS_PER_PAGE = 5;
var COMMENTS_PRINT_LOCATION = "#commentsContainer";

/**
 * Load Comments through JSON
 */
 function loadComments(pageNum){
		if(quantity = undefined)
		{
			quantity = commentsPerPage;
		}
		$.post( ajaxUrl, { request: "load", taskId: <?php echo $data['task']->getId(); ?>, memberId: 1, pageNum: pageNum,  qty: 5 }, 
	    		function( nakedJson )
	    	    {
			 		var jsonObject = $.parseJSON(nakedJson);
			 		updateCommentArray(jsonObject, pageNum);
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
		
		emptyCommentSection();
		
		for(var counter = positionToStartOn; counter < positionToEndOn; counter++)
		{
			/* TABLE ROW */
			var tableRow = document.createElement('tr');
			
			/* TAG */
			var tagTD = document.createElement('td');
			var tagAHREF = document.createElement('a');
			tagAHREF.title = arrayOfComments[counter]['tag'];
			tagAHREF.href = "#";
			tagAHREF.innerHTML = arrayOfComments[counter]['tag'];
			tagTD.appendChild(tagAHREF);

			/* CONTENT */
			var contentTD = document.createElement('td');
			contentTD.innerHTML = arrayOfComments[counter]['content'];

			/* MEMBER */
			var memberIdTD = document.createElement('td');
			memberIdTD.innerHTML = arrayOfComments[counter]['memberId'];

			/* DATE */
			var dateTD = document.createElement('td');
			dateTD.innerHTML = arrayOfComments[counter]['date'];

			/* APPEND EVERYTHING TO TABLE ROW */
			tableRow.appendChild(tagTD);
			tableRow.appendChild(contentTD);
			tableRow.appendChild(memberIdTD);
			tableRow.appendChild(dateTD);

			$(tableRow).appendTo(COMMENTS_PRINT_LOCATION);
			$(tableRow).show();
		}
	}

	
}

function pageAlreadyLoaded(pageNum)
{
	var positionToStartOn = ( pageNum - 1 ) * COMMENTS_PER_PAGE;
	var positionToEndOn = positionToStartOn + COMMENTS_PER_PAGE - 1;

	if(arrayOfComments[positionToStartOn] === undefined || arrayOfComments[positionToStartOn] === null)
	{
		return false;
	}

	if(arrayOfComments[positionToEndOn] === undefined || arrayOfComments[positionToEndOn] === null)
	{
		return false;
	}

	/* USED FOR TESTING */
	// alert(arrayOfComments[positionToStartOn]['content']);
	// alert(arrayOfComments[positionToEndOn]['content']);

	return true;
}

/**
 * Empties the comment section
 */
function emptyCommentSection()
{
	$(COMMENTS_PRINT_LOCATION).children().remove();
}

/**
 * Updates the Comment Array
 *
 * @param jsonObject
 * @param pageNumber
 * @param quantity
 */
function updateCommentArray(jsonObject, pageNum) {
	var positionToStartOn = ( pageNum - 1 ) * COMMENTS_PER_PAGE;
	var positionToEndOn = positionToStartOn + COMMENTS_PER_PAGE;
	
	for(var counter = 0; counter < COMMENTS_PER_PAGE; counter++)
	{
		tempCommentArray = jsonObject[counter];
		positionToAdd = positionToStartOn + counter;
		arrayOfComments[positionToAdd] = jsonObject[counter];
	}

	printCommentsInTable(pageNum);
}

/**
 * Creates a comment in the database
 */
function createComment(commentTag, commentContent)
{
	commentTag = "@" + commentTag;
	 $.post( ajaxUrl, { request: "create", taskId: <?php echo $data['task']->getId(); ?>, memberId: 1, content: commentContent,  tag: commentTag}, 
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
 * Adds hours into the database
 */
function addHours(workedDate, workedHours)
{
	 $.post( ajaxUrl, { request: "addHours", taskId: <?php echo $data['task']->getId(); ?>, memberId: 1, workedDate: "03/10/2013", workedHours: workedHours}, 
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
    		createComment($("#inputTaskTag").val(), $("#inputTaskContent").val());
         });
});

/**
 * Add hours button
 */
 $(function() {
	    $("#addHoursButton").click( function()
	         {
	         	// run script to add hours through ajax
	         	var tempCommentString = "Alex has added " + $("#addHoursHours").val() + " hours  worked on " + document.getElementById("addHoursDate").value + "<br />";
	         	tempCommentString += $("#addHoursComment").val();
	         	addHours(document.getElementById("addHoursDate").value, $("#addHoursHours").val());
	    		createComment("HoursAdded", tempCommentString);
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
</script>
