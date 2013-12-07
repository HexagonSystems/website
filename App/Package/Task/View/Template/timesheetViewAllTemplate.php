<header class="page-header relative">
	<h3>
		Project Tasks <small>Click 'Create Task' to create a new Task</small>
		<a data-toggle="modal" href="#modal_help" class="btn btn-sm"> <span
			class="glyphicon glyphicon-question-sign"></span> Help
		</a>
	</h3>

</header>

<div class="panel panel-default">
	<div class="panel-heading">Task Controls</div>

	<div role="form" class="form-inline panel-body">
		<div class="form-group inline col-xs-14 col-sm-2 col-lg-2">
			<a data-toggle="modal" href="#modal_createTask"
				class="btn btn-primary btn-sm form-control">Create Task</a>
		</div>
		<div class="form-group col-xs-6 col-lg-8 col-sm-8">

			<select class="form-control inline" id="taskAll_filter">
				<option value="empty">No Filter</option>
				<?php 
				if(isset($data['allTaskStatus']))
				{
					if($data['allTaskStatus']['success'] == true)
					{
						foreach($data['allTaskStatus']['data'] as $statusOption)
						{
							if(isset($taskStatus))
							{
								if($statusOption == $taskStatus){
								echo '<option value="'.$statusOption.'" selected="selected">'.$statusOption.'</option>';
							} else {
					echo '<option value="'.$statusOption.'">'.$statusOption.'</option>';
				}
							}else
							{
								echo '<option value="'.$statusOption.'">'.$statusOption.'</option>';
							}

						}
					}else
					{
						echo $data['allTaskStatus']['message'];
						/* DISPLAY ERROR */
					}
				}

				?>
			</select>
		</div>

		<div class="form-group col-xs-6 col-lg-2 col-sm-2">
			<button class="btn btn-primary form-control" id="filterReload">Filter</button>
		</div>
	</div>
</div>


<?php include_once 'modal_createTask.php'; ?>

<?php 
$help_file = "help-viewAllTasks.php";
include_once 'modal_help.php';
?>

<table
	class="table table-rowBorder table-hover table-zebra table-responsive-dropLast2Col">

	<thead>
		<tr>
			<th class="table-colSmall">Status</th>
			<th class="table-colLarge">Task</th>
			<th class="table-colMedium">Members</th>
			<th class="table-colMedium">Last Update</th>
		</tr>
	</thead>

	<tbody id="tasksContainer" class="tbodyFirstLineAccordion">

	</tbody>

</table>

<div class="text-center">
	<ul class="pagination" id="allTasksPaginator">
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
		'content'			:	new Array(),
		'taskFilter'		:	false,
		'paginatorLocation'	:	"#allTasksPaginator"
};

/**
 * Comment section paginator on click event
 */
 $(document).on('click', ".pagination li a", function ( event ) {
		event.preventDefault();

		
		if($(this).text() == "<<")
		{
			$(this).parent().siblings().children().removeClass("paginator-selected");
			$(this).parent().next().children().addClass("paginator-selected");
			loadTasks(mainTaskTable, 1);
		}else if($(this).text() == ">>")
		{
			$(this).parent().siblings().children().removeClass("paginator-selected");
			$(this).parent().prev().children().addClass("paginator-selected");
			loadTasks(mainTaskTable, parseInt($(this).parent().prev().find(">:first-child").text()));
		}else
		{
			$(this).parent().siblings().children().removeClass("paginator-selected");
			$(this).addClass("paginator-selected");
			loadTasks(mainTaskTable, parseInt($(this).text()));
		}
});

/**
 * Create comment button
 */
$(function() {
	$("#createTaskButton").click(
			function() {
				if( validateModalEditTask() )
				{
					createTask(mainTaskTable, $("#modal_taskTitle").val(), $("#modal_taskDscr")
							.val(), $("#modal_taskStatus option:selected").text());
				}
			});
});

$(function() {
	$("#filterReload").click(
			function() {
				if($("#taskAll_filter option:selected").text() == "No Filter")
				{
					mainTaskTable['taskFilter'] = false;
				}else
				{
					mainTaskTable['taskFilter'] = $("#taskAll_filter option:selected").text();
				}
				
				emptyTableBody(mainTaskTable);
				
				mainTaskTable['content'] = new Array();
				mainTaskTable['last_page'] = -1;

				updatePaginator(mainTaskTable);
				loadTasks(mainTaskTable, 1, true);
			});
});

/**
 * Page on load
 */
$(document).ready(function() {
	loadTasks(mainTaskTable, 1);
});
</script>
