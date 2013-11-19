
<!-- Modal -->
<div class="modal fade" id="modal_createTask"
	tabindex="-1" role="dialog" aria-labelledby="addTaskInput"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create Task</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form">
				<?php 
				$taskTitle = "";
				$taskDescription = "";
				$taskStatus = "";
				include 'modal_TaskFields.php';
				
				?>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button id="createTaskButton" type="button" class="btn btn-primary"
					data-dismiss="modal">Create Task</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
