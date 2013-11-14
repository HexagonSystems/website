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
		<?php 

		if($data['taskCount']['success'] == true){
			$amountOfTasks = $data['taskCount']['data'][0];

			$amountOfPages = ceil($amountOfTasks / 5);
			include 'paginator_generator.php';
		}else
		{
			//echo $data['taskCount']['message'];
			echo "There was trouble loading the paginator";
		}

		?>
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
 $(document).on('click', ".pagination li a", function () {
		event.preventDefault();

		
		if($(this).text() == "<<")
		{
			$(this).parent().siblings().children().css('backgroundColor', 'white');
			$(this).parent().next().children().css('backgroundColor', '#eee');
			loadTasks(mainTaskTable, 1);
		}else if($(this).text() == ">>")
		{
			$(this).parent().siblings().children().css('backgroundColor', 'white');
			$(this).parent().prev().children().css('backgroundColor', '#eee');
			loadTasks(mainTaskTable, parseInt($(this).parent().prev().find(">:first-child").text()) + 1);
		}else
		{
			$(this).parent().siblings().children().css('backgroundColor', 'white');
			$(this).css('backgroundColor', '#eee');
			loadTasks(mainTaskTable, parseInt($(this).text()) + 1);
		}
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
 */
$(document).ready(function() {
	loadTasks(mainTaskTable, 1);
});
</script>
