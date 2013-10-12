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

<script>
ajaxUrl = "<?php echo AppBaseSTRIPPED; ?>/Model/TaskCommentsAJAX.php";
taskId = <?php echo $data['task']->getId(); ?>

</script>
<script src="<?php echo AppBaseSTRIPPED; ?>/includes/js/TaskCommentsLoader.js"></script>
<script src="<?php echo AppBaseSTRIPPED; ?>/includes/js/TableLoader.js"></script>
