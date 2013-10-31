<?php 

$searchById = false;
if(isset($_GET[$searchType . '_searchBy']))
{
	if($_GET[$searchType . '_searchBy'] == 'id')
	{
		$searchById = true;
	}
}

?>
<div class="radio-inline">
	<label> <input type="radio" name="<?php echo $searchType; ?>_searchBy" value="id" <?php if($searchById){ echo "checked"; }?>>
		Id
	</label>
</div>
<div class="radio-inline">
	<label> <input type="radio" name="<?php echo $searchType; ?>_searchBy" value="text" <?php if(!$searchById){ echo "checked"; }?>> Txt
	</label>
</div>
