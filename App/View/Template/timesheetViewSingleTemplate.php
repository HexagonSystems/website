<header class="page-header relative">
	<h3>
		<?php echo $data['task']->getTitle(); ?>
		<small><?php echo $data['task']->getStatus(); ?> </small>
	</h3>
	<article>
		Members
		<ul>
			<?php foreach($data['task']->getMembers() as $member) {
				echo "<li>$member</li>";
		} ?>
		</ul>
	</article>
	<article>
		<?php echo $data['task']->getContent(); ?>
	</article>
</header>

<!-- Button trigger modal -->
<a data-toggle="modal" href="#myModal" class="btn btn-primary btn-sm">Add
	Update</a>
<button class="btn btn-primary btn-sm">Edit Task</button>
<?php include_once 'modal_test.php'; ?>


<table
	class="table table-rowBorder table-responsive table-hover table-zebra">

	<thead>
		<th class="table-colSmall">Tag</th>
		<th class="table-colLarge">Update</th>
		<th class="table-colMedium">Posted By</th>
		<th class="table-colMedium">Posted on</th>
	</thead>

	<tbody id="commentsContainer">
		<?php foreach($data['comments'] as $tempTask) {
			include AppBase.'/View/Template/timesheetViewSingle_singleCommentRowTemplate.php';
		} ?>
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
ajaxUrl = "<?php echo AppBaseSTRIPPED; ?>Model/TaskCommentsAJAX.php";
	
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

function loadComments($pageNum){
	$("#commentsContainer").load(ajaxUrl, { request: "load", taskId: <?php echo $data['task']->getId(); ?>, memberId: 1, pageNum: $pageNum,  qty: 5 }, 
			function( secondData )
			{
				if(!secondData)
				{
					alert("Fail");
				}
			});
}

$(function() {
    $(".pagination li a").click( function()
         {
			loadComments($(this).text());
         });
});
 
</script>
