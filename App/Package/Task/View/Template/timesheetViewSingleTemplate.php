<header class="page-header relative" id="taskHeader">
	<h3>
		<span id="taskTitleLocation"><?php echo $data['task']->getTitle(); ?>
		</span> <small id="taskStatusLocation"><?php echo $data['task']->getStatus(); ?>
		</small>
	</h3>
	<article>
		Members
		<ul>
			<?php
			if(count($data['task']->getMembers()) == 0)
			{
				echo "<li>Add hours to Task to be shown here</li>";
			}else
			{
				foreach($data['task']->getMembers() as $member) {
					echo "<li>$member</li>";
				}
			}?>
		</ul>
	</article>
	<article id="taskDescriptionLocation">
		<?php echo $data['task']->getContent(); ?>
	</article>
</header>

<!-- Button trigger modal -->
<div class="panel panel-default hidden-print">
	<div class="panel-heading">Task Controls</div>
	<div role="form" class="form-inline panel-body">
		<div class="form-group inline col-xs-12 col-sm-3 col-lg-3">
			<a data-toggle="modal" href="#modal_comment"
				class="btn btn-primary btn-sm form-control">Add Update</a>
		</div>
		<div class="form-group inline col-xs-12 col-sm-3 col-lg-3">
			<a data-toggle="modal" href="#modal_hours"
				class="btn btn-primary btn-sm form-control">Add Hours</a>
		</div>
		<div class="form-group inline col-xs-12 col-sm-3 col-lg-3">
			<a data-toggle="modal" href="#modal_wipeHours"
				class="btn btn-primary btn-sm form-control">Wipe Hours</a>
		</div>
		<div class="form-group inline col-xs-12 col-sm-3 col-lg-3">
			<a data-toggle="modal" href="#modal_editTask"
				class="btn btn-primary btn-sm form-control">Edit Task</a>
		</div>
	</div>
</div>
<?php include_once 'modal_comment.php'; ?>
<?php include_once 'modal_hours.php'; ?>
<?php include_once 'modal_wipeHours.php'; ?>
<?php include_once 'modal_editTask.php'; ?>
<?php include_once 'modal_pickSearchMethod.php'; ?>

<table id="testtable"
	class="table table-rowBorder table-hover table-zebra table-responsive-dropLast2Col">

	<thead>
		<tr>
			<th class="table-colSmall">Tag</th>
			<th class="table-colLarge">Update</th>
			<th class="table-colMedium">Posted By</th>
			<th class="table-colMedium">Posted on</th>
		</tr>
	</thead>

	<tbody id="commentsContainer" class="tbodyFirstLineAccordion">
		<!-- Comments will be loaded here through AJAX -->
	</tbody>
</table>

<div class="text-center">
	<ul class="pagination" id="taskCommentPaginator">
		<?php 
		if($data['task']->getCommentCount() != null)
		{
			$amountOfPages = ceil($data['task']->getCommentCount() / 5);
			include 'paginator_generator.php';
		}else
		{
			echo "There was trouble loading the paginator";
		}
		?>
	</ul>
</div>

<script
	src="<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/includes/js/TaskCommentsLoaderNEW.js"></script>
<script
	src="<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/includes/js/TableLoaderNEW.js"></script>
<script
	src="<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/includes/js/TaskLoaderNEW.js"></script>
<script>
ajaxBase = "<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/";

/* CONFIG FOR TASK COMMENTS TABLE */
mainTaskCommentsTable = {
		'print_location'	:	'#commentsContainer',
		'paginatorLocation'	:	"#taskCommentPaginator",
		'quantity_per_page'	:	5,
		'last_page'			:	-1,
		'memberId'			:	<?php echo unserialize($_SESSION['accountObject'])->getMemberId(); ?>,
		'memberFirstName'	: 	"<?php echo unserialize($_SESSION['accountObject'])->getFirstName(); ?>",
		'taskId'			:	<?php echo $data['task']->getId(); ?>,
		'content'			:	new Array()
};

/**
 * Create comment button
 * 
 * First checks if the form is valid, then submits the createComment() function which contacts the database through an AJAX call.
 */
$(function() {
	$("#createCommentButton").click(
			
		function() {
			if( validateModalComment() )
			{
				createComment(mainTaskCommentsTable, $("#inputTaskTag").val(), $("#inputTaskTitle").val(), $("#inputTaskContent").val());
			}
		});		
});

/**
 * Add hours button
 * 
 * First checks if the form is valid, then submits the addHours() function which contacts the database through an AJAX call.
 */
$(function() {
	$("#addHoursButton").click(
			function() {
				if( validateModalHours() )
				{
					// run script to add hours through ajax
					addHours(mainTaskCommentsTable, $("#addHoursDatePicker").val(), $(
							"#addHoursHours").val(), $("#addHoursComment").val());
				}
			});
});

/**
 * Wipe hours button
 * 
 * First checks if the form is valid, then submits the addHours() function which contacts the database through an AJAX call.
 */
$(function() {
	$("#wipeHoursButton").click(
			function() {
				if( validateModalWipeHours() )
				{
					// run script to wipe hours through ajax
					wipeHours(mainTaskCommentsTable, $("#wipeHoursDatePicker").val(), $("#wipeHoursComment").val());
				}
			});
});

/**
 * Comment section paginator on click event
 */
 $(document).on('click', ".pagination li a", function ( event ) {
		event.preventDefault();
		if($(this).text() == "<<")
		{
			$(this).parent().siblings().children().removeClass("paginator-selected");
			$(this).parent().next().children().addClass("paginator-selected");
			loadComments(mainTaskCommentsTable, 1);
		}else if($(this).text() == ">>")
		{
			$(this).parent().siblings().children().removeClass("paginator-selected");
			$(this).parent().prev().children().addClass("paginator-selected");
			loadComments(mainTaskCommentsTable, parseInt($(this).parent().prev().find(">:first-child").text()));
		}else
		{
			$(this).parent().siblings().children().removeClass("paginator-selected");
			$(this).addClass("paginator-selected");
			loadComments(mainTaskCommentsTable, parseInt($(this).text()));
		}
		
});



/**
 * Edit Task functions
 */
 editTaskArray = {
			'titleLocation'		: 	"#taskTitleLocation",
			'contentLocation'	:	"#taskDescriptionLocation",
			'statusLocation'	:	"#taskStatusLocation",
			'memberId'			:	<?php echo unserialize($_SESSION['accountObject'])->getMemberId(); ?>,
			'memberFirstName'	: 	"<?php echo unserialize($_SESSION['accountObject'])->getFirstName(); ?>",
			'taskId'			:	<?php echo $data['task']->getId(); ?>,
	};

 /**
  * Edit Task Submit Button
  * 
  * First checks if the form is valid, then calls the form's submit function
  */
 $(function() {
		$("#editTaskSubmitButton").click(function() {
			if( validateModalEditTask() )
			{
				$("#editTaskForm").submit();
			}
		});
	});

 /**
  * Edit Task Form submit() override
  * 
  * First checks if the form is valid, then submits the editTask() function which contacts the database through an AJAX call.
  */
$(function() {
	$("#editTaskForm").submit(
			function(event) {
				if( validateModalEditTask() )
				{
					editTask(editTaskArray, mainTaskCommentsTable['taskId'], $("#modal_taskTitle").val(), $("#modal_taskDscr")
							.val(), $("#modal_taskStatus option:selected").text());
					event.preventDefault();
				}
			});
});


/**
 * Page on load
 */
$(document).ready(function() {
	loadComments(mainTaskCommentsTable, 1); // Load initial page of comments

	$("#addHoursDatePicker").datepicker(); // Initialize the DatePicker
	$("#addHoursDatePicker").datepicker('setDate', new Date()); // Set the date to today's date
    $( "#addHoursDatePicker" ).datepicker( "option", "dateFormat", "dd-M-yy" ); // Set the formatting for the date

    $("#wipeHoursDatePicker").datepicker(); // Initialize the DatePicker
	$("#wipeHoursDatePicker").datepicker('setDate', new Date()); // Set the date to today's date
    $( "#wipeHoursDatePicker" ).datepicker( "option", "dateFormat", "dd-M-yy" ); // Set the formatting for the date

	/*
	The following code was taken from http://stackoverflow.com/questions/18426225/jquery-validation-plugin-validate-date-like-20-august-2013-safari-bug
		From the user Nifty
		On Aug 26 at 6:05
	*/
    /*Override the validator's Date Validation */
    $.validator.methods.date=
    function(value, element) {  
	    try{
	    var _appleDates=$.datepicker.formatDate("mm/dd/yy",$.datepicker.parseDate("dd-M-yy",value));
	    	return (!/Invalid|NaN/.test(new Date(_appleDates).toString()));
	    }catch(e){return false;};
    };
});
</script>
