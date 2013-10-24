<h3>
	<?php echo $data['timesheetData']->getMembersName($tableUser); ?>
</h3>
<table
	class="table table-rowBorder table-responsive table-hover table-zebra">

	<thead>
	<th></th>
		<?php foreach($data['timesheetData']->getDateArray() as $date)
		{
			include 'timesheetDisplayHours_TableHeaderTemplate.php';
		}

		?>
	</thead>

	<tbody class="tbodyFirstLineAccordion">
		<?php 
		foreach($tableData as $tableTaskId => $tableInnerData)
		{
			echo '<tr>';
			echo "<th><a href='index.php?location=timesheetPage&action=single&param=$tableTaskId'>".$data['timesheetData']->getTaskName($tableTaskId)."</a></th>";
			include 'timesheetDisplayHours_TableBodyTemplate.php';
			echo '</tr>';
		}
		?>
	</tbody>
</table>
