<section>
	
	
	<?php
		$i=0;
		echo "<div class='row'>";
		foreach($data['resultSet'] as $row => $column)
		{
			if($i==0)
			{
				echo "<article class='col-xs-12 col-sm-6 col-lg-6'>";
				echo "<h1>" . $column["title"] . "</h1></br>";
				echo "<p>" . $column["content"] . "</p></br>";
				echo "</div>";
				echo "<div class='row'>";
				$i=1;
				echo "</article>";
			}
			else
			{
				if($i == 4)
				{
					echo "</div>";
					echo "<div class='row'>";
					$i=2;
				}
				echo "<article class='col-xs-12 col-sm-6 col-lg-6'>";
				echo "<h2>" . $column["title"] . "</h2></br>";
				echo "<p>" . $column["content"] . "</p></br>";
				echo "</article>";
			}
			
			$i++;
			
		}
		echo "</div>";
	?>
<section>
