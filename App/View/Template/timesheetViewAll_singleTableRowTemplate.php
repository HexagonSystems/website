

<tr>
	<td><?php 
		switch($tempTask->getStatus()){
			case "In Progress": echo '<span class="label label-primary">';
								break;
			case "Needs Attention": echo '<span class="label label-danger">';
								break;
			case "Complete": echo '<span class="label label-success">';
								break;
		}
	?><?php echo $tempTask->getStatus(); ?></span></td>
	<td>
		<div class="table-tdIn">
			<a href="index.php?location=timesheetPage&action=single&param=<?php echo $tempTask->getId(); ?>"><?php echo $tempTask->getTitle(); ?></a>
			<p><?php echo $tempTask->getContent() ?></p>
		</div>
	</td>
	<td><?php echo implode(", ", $tempTask->getMembers()); ?></td>
	<td><?php 
		$lastUpdate = $tempTask->getLastUpdate();
		echo $lastUpdate['memberId'].' on '.$lastUpdate['postedDate'];
	?></td>
</tr>
