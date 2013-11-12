<header class="page-header relative" id="taskHeader">
	<h3>
		<span id="taskTitleLocation"><?php echo $data['task']->getTitle(); ?>
		</span> <small id="taskStatusLocation"><?php echo $data['task']->getStatus(); ?>
		</small>
	</h3>
	<article>
		Members
		<ul>
			<?php foreach($data['task']->getMembers() as $member) {
				echo "<li>$member</li>";
		} ?>
		</ul>
	</article>
	<article id="taskDescriptionLocation">
		<?php echo $data['task']->getContent(); ?>
	</article>
</header>

<!-- Button trigger modal -->
<a data-toggle="modal" href="#modal_comment"
	class="btn btn-primary btn-sm">Add Update</a>
<a data-toggle="modal" href="#modal_hours"
	class="btn btn-primary btn-sm">Add Hours</a>
<a data-toggle="modal" href="#modal_editTask"
	class="btn btn-primary btn-sm">Edit Task</a>
<?php include_once 'modal_comment.php'; ?>
<?php include_once 'modal_hours.php'; ?>
<?php include_once 'modal_editTask.php'; ?>
<?php include_once 'modal_pickSearchMethod.php'; ?>

<div class="table-responsive">
	<table id="testtable"
		class="table table-rowBorder table-responsive table-hover table-zebra">

		<thead>
			<th class="table-colSmall">Tag</th>
			<th class="table-colLarge">Update</th>
			<th class="table-colMedium">Posted By</th>
			<th class="table-colMedium">Posted on</th>
		</thead>

		<tbody id="commentsContainer" class="tbodyFirstLineAccordion">
			<!-- Comments will be loaded here through AJAX -->
		</tbody>
	</table>
</div>

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
 * NEEDS TO BE REMOVED
 */
$(function() {
	$("#createCommentButton").click(
			function() {
				createComment(mainTaskCommentsTable, $("#inputTaskTag").val(), $("#inputTaskTitle").val(), $("#inputTaskContent").val());
			});
});

/**
 * Add hours button
 * 
 */
$(function() {
	$("#addHoursButton").click(
			function() {
				// run script to add hours through ajax
				addHours(mainTaskCommentsTable, $("#addHoursDatePicker").val(), $(
						"#addHoursHours").val(), $("#addHoursComment").val());
			});
});

/**
 * Comment section paginator on click event
 */
 $(document).on('click', ".pagination li a", function () {
		event.preventDefault();
		if($(this).text() == "<<")
		{
			loadComments(mainTaskCommentsTable, 1);
		}else if($(this).text() == ">>")
		{
			loadComments(mainTaskCommentsTable, parseInt($(this).parent().prev().find(">:first-child").text()) + 1);
		}else
		{
			loadComments(mainTaskCommentsTable, parseInt($(this).text()) + 1);
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
 $(function() {
		$("#editTaskSubmitButton").click(function() {
			$("#editTaskForm").submit();
		});
	});
$(function() {
	$("#editTaskForm").submit(
			function(event) {
				editTask(editTaskArray, mainTaskCommentsTable['taskId'], $("#modal_taskTitle").val(), $("#modal_taskDscr")
						.val(), $("#modal_taskStatus option:selected").text());
				event.preventDefault();
			});
});

/**
 * Page on load
 */
$(document).ready(function() {
	loadComments(mainTaskCommentsTable, 1);

	$("#addHoursDatePicker").datepicker();
	$("#addHoursDatePicker").datepicker('setDate', new Date());
    $( "#addHoursDatePicker" ).datepicker( "option", "dateFormat", "dd-M-yy" );
});
</script>
