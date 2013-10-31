<header class="page-header relative">
	<h3>Searching for:</h3>
</header>
<div class="panel panel-default">
	<div class="panel-heading">Search</div>
	<div class="panel-body">
		<form class="form-horizontal" role="form" action="#" method="GET">
			<input type="hidden" name="location" value="timesheetPage"> <input
				type="hidden" name="action" value="search">
			<div class="form-group">
				<label for="tag_value" class="col-lg-2 control-label">Tag</label>
				<div class="col-lg-8">
					<input type="text" class="form-control" name="tag_value"
						id="tag_value" value="<?php if(isset($_GET['tag_value'])) { echo $_GET['tag_value']; }?>">
				</div>
				<?php
				$searchType = 'tag';
				include 'timesheetViewSearched_IdorTextTemplate.php';
				?>
			</div>
			<div class="form-group">
				<label for="task_value" class="col-lg-2 control-label">Task</label>
				<div class="col-lg-8">
					<input type="text" class="form-control" name="task_value" id="task_value" value="<?php if(isset($_GET['task_value'])) { echo $_GET['task_value']; }?>">
				</div>
				<?php
				$searchType = 'task';
				include 'timesheetViewSearched_IdorTextTemplate.php';
				?>
			</div>
			<div class="form-group">
				<label for="addHoursDate" class="col-lg-2 control-label">Member</label>
				<div class="col-lg-8">
					<input type="text" class="form-control" name="member_value" id="member_value" value="<?php if(isset($_GET['member_value'])) { echo $_GET['member_value']; }?>">
				</div>
				<?php
				$searchType = 'member';
				include 'timesheetViewSearched_IdorTextTemplate.php';
				?>
			</div>

			<div class="form-group">
				<label for="addHoursDate" class="col-lg-2 control-label">Search for</label>
				<div class="col-lg-10">
					<select class="form-control" id="createTaskStatus" name="searchFor">
						<!-- Will need to load these from database -->
						<option value="tag">Tags</option>
						<option value="task">Tasks</option>
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

<table id="testtable"
	class="table table-rowBorder table-responsive table-hover table-zebra">

	<thead>
		<tr>
			<?php 
			if($this->data['searchResult'])
			{
				foreach(array_keys($this->data['searchResult']['data'][0]->toArray()) as $key)
				{
					include 'timesheetViewSearched_tableHeaderTemplate.php';
				}
			}else
			{
				echo "No results found";
			}

			?>
		</tr>
	</thead>

	<tbody id="commentsContainer" class="tbodyFirstLineAccordion">
		<?php 
		if($this->data['searchResult'])
		{
			foreach($this->data['searchResult']['data'] as $currentObject)
			{
				echo '<tr>';
				$currentObject = $currentObject->toArray();
				foreach(array_keys($this->data['searchResult']['data'][0]->toArray()) as $key)
				{
					include 'timesheetViewSearched_tableBodyTemplate.php';
				}
				echo '</tr>';
			}
		}
		?>
	</tbody>
</table>
