
<h3>Timesheets</h3>

<div class="panel panel-default">
	<div class="panel-heading">Search</div>
	<div class="panel-body">
		<form class="form-horizontal" role="form" action="#" method="GET">
			<input type="hidden" name="location" value="timesheetPage"> <input
				type="hidden" name="action" value="displayHours">

			<div class="form-group">
				<label for="addHoursDate" class="col-lg-2 control-label">Start Date</label>
				<div class="col-lg-8 inline">
					<input type="text" class="form-control" name="startDate"
						id="displayHours_datePicker" value="1383260400">
				</div>
				<button type="submit" class="btn btn-default">Search</button>
			</div>

		</form>
	</div>
</div>

<?php 
foreach($data['timesheetData']->toArray() as $tableUser => $tableData)
{
	include 'timesheetDisplayHours_TableTemplate.php';
}
?>

<?php 
$datePickerDate = "";
if(isset($_GET['startDate']))
{
	$date = str_replace('-','.',$_GET['startDate']);
	$datePickerDate = date('Y-m-d',strtotime($date));
}else
{
	$datePickerDate = "-7";
}
?>

<script>
    $(document).ready(function () {
        $("#displayHours_datePicker").datepicker();
        
       	$("#displayHours_datePicker").datepicker('setDate', '-7');
        $( "#displayHours_datePicker" ).datepicker( "option", "dateFormat", "dd-M-yy" );
    });
</script>
