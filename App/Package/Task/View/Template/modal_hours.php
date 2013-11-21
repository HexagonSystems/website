

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
			<label for="addHoursDatePicker" class="col-lg-2 control-label">Date</label>
			<div class="col-lg-10">
				<input type="text" class="form-control" id="addHoursDatePicker" name="addHoursDatePicker">
			</div>
		</div>
		<div class="form-group">
			<label for="addHoursHours" class="col-lg-2 control-label">Hours</label>
			<div class="col-lg-10">
				<input type="text" class="form-control" id="addHoursHours" name="addHoursHours"
					placeholder="1">
			</div>
		</div>
		<div class="form-group">
			<label for="addHoursComment" class="col-lg-2 control-label">Comment</label>
			<div class="col-lg-10">
				<textarea id="addHoursComment" name="addHoursComment" cols="5" class="boxsizingBorder width100" placeholder="What did you do during this time?"></textarea>
			</div>
		</div>
	</form>
	<div id="addTaskHoursErrorMessage"></div>
				<!-- end error -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="addHoursButton" type="button" class="btn btn-primary">Add Hours</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  
  <script>
var container = $('div.addTaskHoursErrorMessage');

function validateModalHours() {
	if( $("#addHoursDate").valid() )
	{
		$("#modal_hours").modal('hide');
		return true;
	}else
	{
		return false;
	}
}

$(document).ready(function(){
	errorLabelContainer: $("#addHoursDate div.addTaskHoursErrorMessage")
	$("#addHoursDate").validate({
		
		errorContainer: container,
		errorLabelContainer: $("div", container),
		wrapper: 'p',
		rules: {
			addHoursDatePicker: {
				required: true,
				date: true 
			},
			addHoursHours: {
				required: true,
				digits: true,
				max: 24,
				min: 0
			},
			addHoursComment: {
				maxlength: 255
			}
		},
		messages: {
			addHoursDatePicker: {
				required: 'What date did you work?.',
				date: 'Please only enter in a date.'
			},
			addHoursHours: {
				required: 'Please enter how many hours you worked.',
				digits: 'Psst, hours are numbers. (Try a positive whole number)',
				max: 'There are only 24 hours in a day!',
				min: 'You worked less than 0 hours... Riiight...'
			},
			addHoursComment: {
				maxlength: 'I appreciate the effort, but try and make it shorter (<=255)'
			}
		}
	});
});
</script>