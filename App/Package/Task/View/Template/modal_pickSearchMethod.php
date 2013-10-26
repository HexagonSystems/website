
<!-- Modal -->
<div class="modal fade"
	id="modal_pickSearchMethod" tabindex="-1" role="dialog"
	aria-labelledby="addTaskInput" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">Where should we search?</h4>
			</div>
			<div class="modal-body">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<a href="index.php?location=timesheetPage&action=search&task_id=<?php echo $_GET['param']; ?>"
					class="btn btn-primary btn-sm searchModalButton">Find in this Task</a>
					
					<a href="index.php?location=timesheetPage&action=search"
					class="btn btn-primary btn-sm searchModalButton">Find in all Tasks</a>
			</div>
			<div class="modal-footer"></div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
