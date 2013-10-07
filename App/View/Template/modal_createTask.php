

  <!-- Modal -->
  <div class="modal fade" id="modal_createTask" tabindex="-1" role="dialog" aria-labelledby="addTaskInput" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Create Task</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form">
		<div class="form-group">
			<label for="addHoursDate" class="col-lg-2 control-label">Title</label>
			<div class="col-lg-10">
				<input type="text" class="form-control" id="createTaskTitle" placeholder="Task title">
					  </script>
			</div>
		</div>
		<div class="form-group">
			<label for="createTaskDscr" class="col-lg-2 control-label">Description</label>
			<div class="col-lg-10">
				<textarea id="createTaskDscr" cols="5" class="boxsizingBorder form-control width100" placeholder="Provide a brief description for the task"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="createTaskStatus" class="col-lg-2 control-label">Status</label>
			<div class="col-lg-10">
				<select class="form-control">
				<!-- Will need to load these from database -->
					<option>In Progress</option>
					<option>Needs attention</option>
				</select>
			</div>
		</div>
	</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="createTaskButton" type="button" class="btn btn-primary" data-dismiss="modal">Create Task</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->