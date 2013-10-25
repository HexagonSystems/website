
<h3>Timesheets</h3>

<div class="panel panel-default">
  <div class="panel-heading">Search</div>
  <div class="panel-body">
    <form class="form-horizontal" role="form" action="#" method="GET">
    	<input type="hidden" name="location" value="timesheetPage">
    	<input type="hidden" name="action" value="displayHours">
    	<div class="form-group">
			<label for="addHoursDate" class="col-lg-2 control-label">Start Date</label>
			<div class="col-lg-10">
				<input type="date" class="form-control" name="startDate" id="addHoursDatePicker">
			</div>
		</div>
		
		<div class="form-group">
			<label for="addHoursDate" class="col-lg-2 control-label">User</label>
			<div class="col-lg-10">
				<select class="form-control" id="createTaskStatus" name="user">
				<!-- Will need to load these from database -->
					<option>Alex</option>
					<option>All</option>
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-lg-10">
				<button type="submit">Search</button>
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