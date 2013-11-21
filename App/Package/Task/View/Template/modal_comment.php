

<!-- Modal -->
<div class="modal fade" id="modal_comment"
	tabindex="-1" role="dialog" aria-labelledby="addTaskInput"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Task Update</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form" id="addTaskInput">
					<div class="form-group">
						<label for="inputTaskTag" class="col-lg-2 control-label">Tag</label>
						<div class="col-lg-10">
							<input type="text" class="form-control" id="inputTaskTag" name="inputTaskTag"
								placeholder="Tag" maxlength="45" required>
						</div>
					</div>
					<div class="form-group">
						<label for="inputTaskTitle" class="col-lg-2 control-label">Title</label>
						<div class="col-lg-10">
							<input type="text" class="form-control" id="inputTaskTitle" name="inputTaskTitle"
								placeholder="Provide a brief description here" maxlength="255"
								required />
						</div>
					</div>
					<div class="form-group">
						<label for="inputTaskContent" name="inputTaskContent" class="col-lg-2 control-label">Comment</label>
						<div class="col-lg-10">
							<textarea id="inputTaskContent" cols="5"
								class="boxsizingBorder width100"
								placeholder="A more detailed description goes here"
								maxlength="255"></textarea>
						</div>
					</div>
				</form>
				<div id="createTaskCommentErrorMessage"></div>
				<!-- end error -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button id="createCommentButton" type="button"
					class="btn btn-primary">Add Update</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
var container = $('div.createTaskCommentErrorMessage');

function validateModalComment() {
	if( $("#addTaskInput").valid() )
	{
		$("#modal_comment").modal('hide');
		return true;
	}else
	{
		return false;
	}
}

$(document).ready(function(){
	errorLabelContainer: $("#addTaskInput div.createTaskCommentErrorMessage")
	$("#addTaskInput").validate({
		
		errorContainer: container,
		errorLabelContainer: $("div", container),
		wrapper: 'p',
		rules: {
			inputTaskTag: {
				required: true,
				letterswithbasicpunc: true,
				maxlength: 45 
			},
			inputTaskTitle: {
				required: true,
				maxlength: 255 
			},
			inputTaskContent: {
				required: true,
				maxlength: 255
			}
		},
		messages: {
			inputTaskTag: {
				required: 'Please enter in a tag.',
				letterswithbasicpunc: 'Please enter letters only.',
				maxlength: 'Please keep your tags short and sweet! (<=45)'
			},
			inputTaskTitle: {
				required: 'Please enter a title.',
				maxlength: 'This is a Title not a description (<=255)!'
			},
			inputTaskContent: {
				required: 'Comments require a comment. Funny that.',
				maxlength: 'I appreciate the effort, but try and make it shorter (<=255)'
			}
		}
	});
});
</script>
