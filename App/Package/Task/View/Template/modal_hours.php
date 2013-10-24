

  <!-- Modal -->
  <div class="modal fade" id="modal_hours" tabindex="-1" role="dialog" aria-labelledby="addTaskInput" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Add Hours</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form" id="addHoursDate">
		<div class="form-group">
			<label for="addHoursDate" class="col-lg-2 control-label">Date</label>
			<div class="col-lg-10">
				<input type="date" class="form-control" id="addHoursDatePicker">
					  </script>
			</div>
		</div>
		<div class="form-group">
			<label for="addHoursHours" class="col-lg-2 control-label">Hours</label>
			<div class="col-lg-10">
				<input type="text" class="form-control" id="addHoursHours"
					placeholder="1">
			</div>
		</div>
		<div class="form-group">
			<label for="addHoursComment" class="col-lg-2 control-label">Comment</label>
			<div class="col-lg-10">
				<textarea id="addHoursComment" cols="5" class="boxsizingBorder width100" placeholder="What did you do during this time?"></textarea>
			</div>
		</div>
	</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="addHoursButton" type="button" class="btn btn-primary" data-dismiss="modal">Add Hours</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->