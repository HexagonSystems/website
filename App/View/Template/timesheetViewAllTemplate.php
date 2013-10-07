<a data-toggle="modal" href="#modal_createTask"
	class="btn btn-primary btn-sm">Add Update</a>
<?php include_once 'modal_createTask.php'; ?>

<table
	class="table table-rowBorder table-responsive table-hover table-zebra">

	<thead>
		<th class="table-colSmall">Status</th>
		<th class="table-colLarge">Task</th>
		<th class="table-colMedium">Members</th>
		<th class="table-colMedium">Last Update</th>
	</thead>

	<tbody>
		<?php
		foreach($data['task'] as $tempTask) {
			include AppBase.'/View/Template/timesheetViewAll_singleTableRowTemplate.php';
		}
		?>

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
ajaxUrl = "<?php echo AppBaseSTRIPPED; ?>Model/TaskAJAX.php";
taskId = <?php echo $data['task']->getId(); ?>

</script>
<script src="<?php echo AppBaseSTRIPPED; ?>/includes/js/TaskLoader.js"></script>
<script src="<?php echo AppBaseSTRIPPED; ?>/includes/js/TableLoader.js"></script>