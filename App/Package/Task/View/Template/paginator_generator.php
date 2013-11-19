<li><a href="#" class="paginatorArrowJump"><<</a>
</li>
<?php
for($counter = 1; $counter < ($amountOfPages + 1); $counter++)
{
	if($counter > 1)
	{
		echo '<li><a href="#">'.$counter.'</a></li>';
	}else
	{
		echo '<li><a href="#" class="paginator-selected">'.$counter.'</a></li>';
	}
	
}
?>
<li><a href="#" class="paginatorArrowJump">>></a>
</li>
