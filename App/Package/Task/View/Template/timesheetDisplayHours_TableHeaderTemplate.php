<th><?php
if(isset($_GET['timeFrame']))
{
	switch($_GET['timeFrame'])
	{
		case 'year': $date = date('M-Y', strtotime($date));
		break;
		case 'month': $tempDate = date('j', strtotime($date));
		$printDate = date('j - ', strtotime($date));
		if(intval($tempDate) < 28)
		{
			$printDate .= date('j', strtotime('+6 days', strtotime("$date")));
		}else
		{
			$printDate .= date('t', strtotime($date));
		}
		$date = $printDate;

		break;
		case 'week':$date = date('D', strtotime($date));
		break;
		default: $date = date('d-m-Y', strtotime($date));
	}
}
echo $date;
?></th>
