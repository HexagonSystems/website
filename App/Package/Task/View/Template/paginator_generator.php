<li><a href="#" class="paginatorArrowJump"><<</a>
</li>
<?php
for($counter = 1; $counter < ($amountOfPages + 1); $counter++)
{
	echo '<li><a href="#">'.$counter.'</a></li>';
}
?>
<li><a href="#" class="paginatorArrowJump">>></a>
</li>
