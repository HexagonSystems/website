<td>
<?php if(is_array($currentObject[$key]))
{
	echo '<a href="index.php?location=timesheetPage&action=single&param='.$currentObject[$key]['id'].'">'.$currentObject[$key]['value'].'</a>';
}else
{
	echo $currentObject[$key];
}

?>
</td>
