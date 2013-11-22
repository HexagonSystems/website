
<div class="form-group">
	<label for="modal_taskTitle" class="col-lg-2 control-label">Title</label>
	<div class="col-lg-10">
		<input type="text" class="form-control" id="modal_taskTitle" name="modal_taskTitle" placeholder="Task title" value="<?php echo $taskTitle; ?>" maxlength="45">
	</div>
</div>
<div class="form-group">
	<label for="modal_taskDscr" class="col-lg-2 control-label">Description</label>
	<div class="col-lg-10">
		<textarea id="modal_taskDscr" name="modal_taskDscr" cols="5" class="boxsizingBorder form-control width100" placeholder="Provide a brief description for the task"><?php echo $taskDescription; ?></textarea>
	</div>
</div>
<div class="form-group">
	<label for="modal_taskStatus" class="col-lg-2 control-label">Status</label>
	<div class="col-lg-10">
		<select class="form-control" id="modal_taskStatus" name="modal_taskStatus">
			<!-- Will need to load these from database -->
			<?php 

			if(isset($data['allTaskStatus']))
			{
				if($data['allTaskStatus']['success'] == true)
				{
					foreach($data['allTaskStatus']['data'] as $statusOption)
					{
						if(isset($taskStatus))
						{
							if($statusOption == $taskStatus){
								echo '<option value="'.$statusOption.'" selected="selected">'.$statusOption.'</option>';
							} else {
								echo '<option value="'.$statusOption.'">'.$statusOption.'</option>';
							}
						}else
						{
							echo '<option value="'.$statusOption.'">'.$statusOption.'</option>';
						}

					}
				}else
				{
					echo $data['allTaskStatus']['message'];
					/* DISPLAY ERROR */
				}
			}

			?>
		</select>
	</div>
</div>

<script>
var container = $('div.editTaskErrorMessage');

function validateModalTaskFields(modalId, formId) {
	if( $("#" + formId).valid() )
	{
		$("#" + modalId).modal('hide');
		return true;
	}else
	{
		return false;
	}
}

function setUpTaskValidation(formId, errorId, container) {
	errorLabelContainer: $("#" + formId + " div." + errorId)
	$("#" + formId).validate({
		
		errorContainer: container,
		errorLabelContainer: $("div", container),
		wrapper: 'p',
		rules: {
			modal_taskTitle: {
				required: true,
				maxlength: 255 
			},
			modal_taskDscr: {
				required: true,
				maxlength: 255 
			}
		},
		messages: {
			modal_taskTitle: {
				maxlength: 'This is a Title not a description (<=255)'
			},
			modal_taskDscr: {
				required: 'Please enter your description.',
				maxlength: 'I appreciate the effort, but try and make it shorter (<=255)!'
			}
		}
	});
}
</script>