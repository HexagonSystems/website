
<!-- Modal -->
<div class="modal fade" id="modal_createTask" tabindex="-1" role="dialog" aria-labelledby="modal_createTaskTitle"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modal_createTaskTitle">Create Task</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form" id="createTaskForm">
				<?php 
				$taskTitle = "";
				$taskDescription = "";
				$taskStatus = "";
				include 'modal_TaskFields.php';
				
				?>
				</form>
				<div id="createTaskErrorMessage"></div>
				<!-- end error -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button id="createTaskButton" type="button" class="btn btn-primary">Create Task</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
function validateModalEditTask() {
	return validateModalTaskFields("modal_createTask", "createTaskForm");
}

$(document).ready(function(){
	setUpTaskValidation("createTaskForm", "createTaskErrorMessage", "div.createTaskErrorMessage");
});
</script>