
<div class="form-group">
	<label for="modal_taskTitle" class="col-lg-2 control-label">Title</label>
	<div class="col-lg-10">
		<input type="text" class="form-control" id="modal_taskTitle"
			placeholder="Task title" value="<?php echo $taskTitle; ?>">
		</script>
	</div>
</div>
<div class="form-group">
	<label for="modal_taskDscr" class="col-lg-2 control-label">Description</label>
	<div class="col-lg-10">
		<textarea id="modal_taskDscr" cols="5"
			class="boxsizingBorder form-control width100"
			placeholder="Provide a brief description for the task"><?php echo $taskDescription; ?></textarea>
	</div>
</div>
<div class="form-group">
	<label for="modal_taskStatus" class="col-lg-2 control-label">Status</label>
	<div class="col-lg-10">
		<select class="form-control" id="modal_taskStatus">
			<!-- Will need to load these from database -->
			<option>In Progress</option>
			<option>Needs attention</option>
		</select>
	</div>
</div>
