<a data-toggle="modal" href="#myModal" class="btn btn-primary btn-sm">Create Task</a>

<table
	class="table table-rowBorder table-responsive table-hover table-zebra">

	<thead>
		<th class="table-colSmall">Status</th>
		<th class="table-colLarge">Task</th>
		<th class="table-colMedium">Members</th>
		<th class="table-colMedium">Last Update</th>
	</thead>

	<tbody>
		<?php
		foreach($data['task'] as $tempTask) {
			include AppBase.'/View/Template/timesheetViewAll_singleTableRowTemplate.php';
		}
		?>

	</tbody>

</table>

<div class="text-center">
	<ul class="pagination">
		<li><a href="#">&laquo;</a></li>
		<li><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#">&raquo;</a></li>
	</ul>
</div>

<script
	src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'>	
	</script>
<script
	src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
<script>
/**
 * Create comment button
 */
$(function() {
    $("#createCommentButton").click( function()
         {
    		$.post( ajaxUrl, { request: "create", taskId: <?php echo $data['task']->getId(); ?>, memberId: 1, content: $("#inputTaskContent").val(),  tag: $("#inputTaskTag").val()}, 
    		function( data )
    	    {
			    if(data == "true")
			    {
					loadComments(0);
			    }else
			    {
				    alert(data);
			    }
			 });
         });
});

</script>