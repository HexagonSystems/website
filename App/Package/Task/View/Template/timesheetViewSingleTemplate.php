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
<a data-toggle="modal" href="#modal_comment"
	class="btn btn-primary btn-sm">Add Update</a>
<a data-toggle="modal" href="#modal_hours"
	class="btn btn-primary btn-sm">Add Hours</a>
<button class="btn btn-primary btn-sm">Edit Task</button>
<button class="btn btn-primary btn-sm" id="buttonSlideIn">Testing Add Comment With Slide In</button>
<?php include_once 'modal_comment.php'; ?>
<?php include_once 'modal_hours.php'; ?>


<table id="testtable"
	class="table table-rowBorder table-responsive table-hover table-zebra">

	<thead>
		<th class="table-colSmall">Tag</th>
		<th class="table-colLarge">Update</th>
		<th class="table-colMedium">Posted By</th>
		<th class="table-colMedium">Posted on</th>
	</thead>

	<tbody id="commentsContainer" class="tbodyFirstLineAccordion">
		<!-- Comments will be loaded here through AJAX -->
	</tbody>
</table>

<div class="text-center">
	<ul class="pagination">
		<li><a href="">&laquo;</a></li>
		<li><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#">&raquo;</a></li>
	</ul>
</div>

<script
	src="<?php echo AppBaseSTRIPPED; ?>/includes/js/TaskCommentsLoaderNEW.js"></script>
<script
	src="<?php echo AppBaseSTRIPPED; ?>/includes/js/TableLoaderNEW.js"></script>
<script>
ajaxBase = "<?php echo AppBaseSTRIPPED; ?>";


mainTaskCommentsTable = {
		'print_location'	:	'#commentsContainer',
		'quantity_per_page'	:	5,
		'last_page'			:	-1,
		'memberId'			:	1,
		'taskId'			:	<?php echo $data['task']->getId(); ?>,
		'content'			:	new Array()
};


/**
 * Create comment button
 * 
 * NEEDS TO BE REMOVED
 */
$(function() {
	$("#createCommentButton").click(
			function() {
				createComment(mainTaskCommentsTable, $("#inputTaskTag").val(), $("#inputTaskTitle").val(), $("#inputTaskContent").val());
			});
});

/**
 * Add hours button
 * 
 */
$(function() {
	$("#addHoursButton").click(
			function() {
				// run script to add hours through ajax
				addHours(mainTaskCommentsTable, document.getElementById("addHoursDate").value, $(
						"#addHoursHours").val(), $("#addHoursComment").val());
			});
});

/**
 * Comment section paginator on click event
 */
$(function() {
	$(".pagination li a").click(function() {
		printTableDataInTable(mainTaskCommentsTable, $(this).text());
	});
});

/**
 * jQuery Datepicker
 */

/**
 * Page on load
 * 
 * NEEDS TO BE REMOVED
 */
$(document).ready(function() {
	printTableDataInTable(mainTaskCommentsTable, 1);
	document.getElementById('addHoursDate').valueAsDate = new Date();
});
</script>
