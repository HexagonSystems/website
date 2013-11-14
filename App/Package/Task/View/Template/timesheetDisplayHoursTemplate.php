
<h3>
	Timesheets
	<?php 
	if(isset($_GET['timeFrame']))
	{
		switch($_GET['timeFrame'])
		{
			case 'year': $reportTitle = "Displaying hours for a year starting from " . date('M, Y', strtotime($data['startDateFormatted']));
			break;
			case 'month': $reportTitle = "Displaying hours for the month of " . date('M, Y', strtotime($data['startDateFormatted']));
			break;
			case 'week':$reportTitle = "Displaying hours for 7 days starting " . date('d-M-Y', strtotime($data['startDateFormatted']));
			break;
			default: $reportTitle = "Please click 'Search' to display hours";
		}
	}else
	{
		$reportTitle = "Please click 'Search' to display hours";
	}
	?>
	<small> <?php echo $reportTitle; ?>
	</small>
</h3>

<div class="panel panel-default">
	<div class="panel-heading">Search</div>
	<div class="panel-body">
		<form class="form-horizontal" role="form" action="#" method="GET">
			<input type="hidden" name="location" value="timesheetPage"> <input
				type="hidden" name="action" value="report">

			<div class="form-group">
				<label class="col-lg-2 control-label">Display</label>
				<div class="col-lg-8 inline">
					<select class="form-control" name="timeFrame">
						<?php 
						$weekSelected = false;
						$monthSelected = false;
						$yearSelected = false;
						if(isset($_GET['timeFrame']))
						{
							if($_GET['timeFrame'] == 'week')
							{
								$weekSelected = true;
							}else if($_GET['timeFrame'] == 'month')
							{
								$monthSelected = true;
							}else if($_GET['timeFrame'] == 'year')
							{
								$yearSelected = true;
							}
						}
						?>
						<option value="week" <?php if($weekSelected){echo "selected";}?>>Week</option>
						<option value="month" <?php if($monthSelected){echo "selected";}?>>Month</option>
						<option value="year" <?php if($yearSelected){echo "selected";}?>>Year</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="addHoursDate" class="col-lg-2 control-label">Start Date</label>

				<div class="col-lg-8 inline">
					<div class="input-group">
						<input type="text" class="form-control" name="startDate"
							id="displayHours_datePicker"> <span class="input-group-btn">
							<button type="submit"
								class="btn btn-default">Search
							</button>
						</span>
					</div>
				</div>
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
	$datePickerDate = date('m/d/Y',strtotime($date));
}else
{
	$datePickerDate = "-7";
}
?>

<script>
    $(document).ready(function () {
        $("#displayHours_datePicker").datepicker();
        
       	$("#displayHours_datePicker").datepicker('setDate', '<?php echo $datePickerDate; ?>');
        $( "#displayHours_datePicker" ).datepicker( "option", "dateFormat", "dd-M-yy" );
    });
</script>
