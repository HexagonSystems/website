

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="addTaskInput" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Add Task Update</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form" id="addTaskInput">
		<div class="form-group">
			<label for="inputTaskTag" class="col-lg-2 control-label">Tag</label>
			<div class="col-lg-10">
				<input type="text" class="form-control" id="inputTaskTag"
					placeholder="@Tag">
			</div>
		</div>
		<div class="form-group">
			<label for="inputTaskContent" class="col-lg-2 control-label">Comment</label>
			<div class="col-lg-10">
				<textarea id="inputTaskContent" cols="5" class="boxsizingBorder width100"></textarea>
			</div>
		</div>
	</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="createCommentButton" type="button" class="btn btn-primary" data-dismiss="modal">Add Update</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->