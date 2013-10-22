<header class="page-header relative">
	<h3>Searching for: </h3>
</header>


<table id="testtable"
	class="table table-rowBorder table-responsive table-hover table-zebra">

	<thead>
		<tr>
			<?php 
			foreach(array_keys($this->data['searchResult']['data'][0]->toArray()) as $key)
			{
				include 'timesheetViewSearched_tableHeaderTemplate.php';
			}
			?>
		</tr>
	</thead>

	<tbody id="commentsContainer" class="tbodyFirstLineAccordion">
		<?php 
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
		?>
	</tbody>
</table>