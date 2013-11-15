<h3>
	<?php echo $data['timesheetData']->getMembersName($tableUser); ?>
</h3>
<div class="table-responsive">
	<table
		class="table table-rowBorder table-responsive table-hover table-zebra">

		<thead>
			<th></th>
			<?php foreach($data['timesheetData']->getDateArray() as $date)
			{
				include 'timesheetDisplayHours_TableHeaderTemplate.php';
			}
			?>
			<th>Total</th>
		</thead>

		<tbody class="tbodyFirstLineAccordion">
			<?php 
			foreach($tableData as $tableTaskId => $tableInnerData)
			{
				echo '<tr>';
				echo "<th><a href='index.php?location=timesheetPage&action=single&param=$tableTaskId'>".$data['timesheetData']->getTaskName($tableTaskId)."</a></th>";
				include 'timesheetDisplayHours_TableBodyTemplate.php';
				echo "<td>".$data['timesheetData']->getTaskTotal($tableUser, $tableTaskId)."</td>";
				echo '</tr>';
			}
			$totalsArray = $data['timesheetData']->getTotalsArray();
			echo '<tr>';
			echo "<th>Total</th>";
			foreach($totalsArray[$tableUser]['date'] as $date => $hours)
			{

				//include 'timesheetDisplayHours_TableBodyTemplate.php';
				echo "<td>".$hours."</td>";

			}

			echo "<td>".$totalsArray[$tableUser]['total']."</td>";
			echo '</tr>';
			?>
		</tbody>
	</table>
</div>
