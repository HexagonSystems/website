<section class="print-page">
	<?php include 'timesheetDisplayHours_TableHeaderHeadTemplate.php'; ?>

	<div class="table-responsive table-print">
		<table class="table table-rowBorder table-hover table-zebra">
			<thead>
				<?php include 'timesheetDisplayHours_TableHeaderTemplate.php'; ?>
			</thead>

			<tbody>
				<?php 
				$counter = 0;
				foreach($tableData as $tableTaskId => $tableInnerData)
				{
					echo "<tr>";
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

</section>
