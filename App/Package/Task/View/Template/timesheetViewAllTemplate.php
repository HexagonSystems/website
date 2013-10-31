<a data-toggle="modal" href="#modal_createTask"
	class="btn btn-primary btn-sm">Create Task</a>
<?php include_once 'modal_createTask.php'; ?>

<table
	class="table table-rowBorder table-responsive table-hover table-zebra">

	<thead>
		<th class="table-colSmall">Status</th>
		<th class="table-colLarge">Task</th>
		<th class="table-colMedium">Members</th>
		<th class="table-colMedium">Last Update</th>
	</thead>

	<tbody id="tasksContainer" class="tbodyFirstLineAccordion">

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
<script
	src="<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/includes/js/TaskLoaderNEW.js"></script>
<script
	src="<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/includes/js/TableLoaderNEW.js"></script>
<script>
ajaxBase = "<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/";

/* MAIN TASK TABLE CONFIG ARRAY */
mainTaskTable = {
		'print_location'	:	'#tasksContainer',
		'quantity_per_page'	:	5,
		'last_page'			:	-1,
		'memberId'			:	<?php echo unserialize($_SESSION['accountObject'])->getMemberId(); ?>,
		'memberFirstName'	: 	"<?php echo unserialize($_SESSION['accountObject'])->getFirstName(); ?>",
		'content'			:	new Array()
};

/**
 * Comment section paginator on click event
 */
$(function() {
	$(".pagination li a").click(function() {
		loadTasks(mainTaskTable, $(this).text());
	});
});

/**
 * Create comment button
 */
$(function() {
	$("#createTaskButton").click(
			function() {
				createTask(mainTaskTable, $("#modal_taskTitle").val(), $("#modal_taskDscr")
						.val(), $("#modal_taskStatus option:selected").text());
			});
});

/**
 * Page on load
 * 
 * THIS NEEDS TO BE REMOVED
 */
$(document).ready(function() {
	loadTasks(mainTaskTable, 1);
});
</script>
