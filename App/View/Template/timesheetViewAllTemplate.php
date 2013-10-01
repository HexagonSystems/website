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
		/*
			$status = "In Progress";
		$taskName = "Responsive Menu - Drop Down";
		$taskDscr = "Attempting to re-create our menu, but with a drop down version.";
		$members = "Alex";
		$lastUpdate = "9:50pm 30/09/2013";
		$lastUpdateMember = "Alex";
		include 'timesheetViewAll_singleTableRowTemplate.php';
		$status = "Needs Attention";
		$taskName = "View all tasks with Twitter Bootstrap";
		$taskDscr = "Porting old version of the View all tasks page originally made in Pure CSS into Twitter Bootstrap";
		$members = "Alex";
		$lastUpdate = "9:50pm 30/09/2013";
		$lastUpdateMember = "Alex";
		include 'timesheetViewAll_singleTableRowTemplate.php';
		$status = "In Progress";
		$taskName = "Structure Dosmon";
		$taskDscr = "Structure Dosmon so everything appears to have followed coding standards.";
		$members = "Alex";
		$lastUpdate = "9:50pm 30/09/2013";
		$lastUpdateMember = "Alex";
		include 'timesheetViewAll_singleTableRowTemplate.php';
		$status = "Complete";
		$taskName = "Another made up task";
		$taskDscr = "I really couldn't be bothered coming up wither another task to put here. I just wanted some nice dummy data to get a feel for the new layout.";
		$members = "Alex";
		$lastUpdate = "9:50pm 30/09/2013";
		$lastUpdateMember = "Alex";
		include 'timesheetViewAll_singleTableRowTemplate.php';
		*/
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
