<?php
   //This is where the homepage will be. It will not be a router. It will import header/footer/nav/
   include "/includes/head.php";
   include "/includes/nav.php";
?>
	<div id="content" class="hexagon-sizeForDesktop">
		<h4>Alex's Timesheet</h4>
		
		<select class="hexagon-select hexagon-textCenter">
			<option value="1">Week 1</option>
			<option value="2">Week 2</option>
			<option value="3">Week 3</option>
			<option value="4">Week 4</option>
		</select>
		
		<table class="hexagon-table">
			<thead>
				<tr>
					<th>Project</th>
					<th>Monday</th>
					<th>Tuesday</th>
					<th>Wednesday</th>
					<th>Thursday</th>
					<th>Friday</th>
					<th>Saturday</th>
					<th>Sunday</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-title="Project">Dosmon</td>
					<td data-title="Monday">2 hr</td>
					<td data-title="Tuesday">3 hr</td>
					<td data-title="Wednesday">1 hr</td>
					<td data-title="Thursday">5 hr</td>
					<td data-title="Friday">2 hr</td>
					<td data-title="Saturday">5 hr</td>
					<td data-title="Sunday">4 hr</td>
				</tr>
				<tr>
					<td data-title="Project">Team Website</td>
					<td data-title="Monday">2 hr</td>
					<td data-title="Tuesday">3 hr</td>
					<td data-title="Wednesday">1 hr</td>
					<td data-title="Thursday">5 hr</td>
					<td data-title="Friday">2 hr</td>
					<td data-title="Saturday">5 hr</td>
					<td data-title="Sunday">4 hr</td>
				</tr>
				<tr>
					<td data-title="Project">Maths study</td>
					<td data-title="Monday">2 hr</td>
					<td data-title="Tuesday">3 hr</td>
					<td data-title="Wednesday">1 hr</td>
					<td data-title="Thursday">5 hr</td>
					<td data-title="Friday">2 hr</td>
					<td data-title="Saturday">5 hr</td>
					<td data-title="Sunday">4 hr</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php
   include "/includes/footer.php";
?>