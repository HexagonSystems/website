
<th></th>
<?php
foreach($data['timesheetData']->getDateArray() as $date)
{
	include 'timesheetDisplayHours_TableHeaderDataTemplate.php';
}
?>
<th>Total</th>
