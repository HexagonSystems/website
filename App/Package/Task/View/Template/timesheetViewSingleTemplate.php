<header class="page-header relative" id="taskHeader">
	<h3>
		<span id="taskTitleLocation"><?php echo $data['task']->getTitle(); ?></span>
		<small id="taskStatusLocation"><?php echo $data['task']->getStatus(); ?> </small>
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
<button class="btn btn-primary btn-sm" id="buttonSlideIn">Testing Add
	Comment With Slide In</button>
<?php include_once 'modal_comment.php'; ?>
<?php include_once 'modal_hours.php'; ?>
<?php include_once 'modal_editTask.php'; ?>
<?php include_once 'modal_pickSearchMethod.php'; ?>


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

<div class="text-center">
	<ul class="pagination">
		<li><a href="">&laquo;</a></li>
		<li><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#">&raquo;</a></li>
	</ul>
</div>

<script
	src="<?php echo AppBaseSTRIPPED; ?>Package/Task/includes/js/TaskCommentsLoaderNEW.js"></script>
<script
	src="<?php echo AppBaseSTRIPPED; ?>Package/Task/includes/js/TableLoaderNEW.js"></script>
<script
	src="<?php echo AppBaseSTRIPPED; ?>Package/Task/includes/js/TaskLoaderNEW.js"></script>
<script>
ajaxBase = "<?php echo AppBaseSTRIPPED; ?>Package/Task/";


mainTaskCommentsTable = {
		'print_location'	:	'#commentsContainer',
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
$(function() {
	$(".pagination li a").click(function() {
		printTableDataInTable(mainTaskCommentsTable, $(this).text());
	});
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
	printTableDataInTable(mainTaskCommentsTable, 1);

	$("#addHoursDatePicker").datepicker();
	$("#addHoursDatePicker").datepicker('setDate', new Date());
    $( "#addHoursDatePicker" ).datepicker( "option", "dateFormat", "dd-M-yy" );
});
</script>
