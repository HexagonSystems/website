<?php 
foreach($data['timesheetData']->toArray() as $tableUser => $tableData)
{
	include 'timesheetDisplayHours_TableTemplate.php';
}
