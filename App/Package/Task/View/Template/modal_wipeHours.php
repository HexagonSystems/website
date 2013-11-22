

  <!-- Modal -->
  <div class="modal fade" id="modal_wipeHours" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Wipe Hours <small> This will set your hours to 0 for the specified date</small></h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form" id="wipeHoursDate">
		<div class="form-group">
			<label for="wipeHoursDatePicker" class="col-lg-2 control-label">Date</label>
			<div class="col-lg-10">
				<input type="text" class="form-control" id="wipeHoursDatePicker" name="wipeHoursDatePicker">
			</div>
		</div>
		<div class="form-group">
			<label for="wipeHoursComment" class="col-lg-2 control-label">Comment</label>
			<div class="col-lg-10">
				<textarea id="wipeHoursComment" name="wipeHoursComment" cols="5" class="boxsizingBorder width100" placeholder="What is the reason for wiping the hours?"></textarea>
			</div>
		</div>
	</form>
	<div id="wipeTaskHoursErrorMessage"></div>
				<!-- end error -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="wipeHoursButton" type="button" class="btn btn-primary">Wipe Hours</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  
  <script>


function validateModalWipeHours() {
	if( $("#wipeHoursDate").valid() )
	{
		$("#modal_wipeHours").modal('hide');
		return true;
	}else
	{
		return false;
	}
}

$(document).ready(function(){
	var wipeHoursContainer = $('div.wipeTaskHoursErrorMessage');
	
	errorLabelContainer: $("#wipeHoursDate div.wipeTaskHoursErrorMessage")
	$("#wipeHoursDate").validate({
		
		errorContainer: container,
		errorLabelContainer: $("div", wipeHoursContainer),
		wrapper: 'p',
		rules: {
			wipeHoursDatePicker: {
				required: true,
				date: true 
			},
			wipeHoursComment: {
				maxlength: 255
			}
		},
		messages: {
			wipeHoursDatePicker: {
				required: 'What date would you like to wipe?.',
				date: 'Please only enter in a date.'
			},
			wipeHoursComment: {
				maxlength: 'I appreciate the effort, but try and make it shorter (<=255)'
			}
		}
	});
});
</script>