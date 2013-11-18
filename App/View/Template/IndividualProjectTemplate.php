<section>
<?php
	$date=$data['projectData']['0']->getDate();
	$finishDate = date('M Y', strtotime($date));
?>
	<h1><?php echo $data['projectData']['0']->getTitle();?></h1>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="indiImage"> </div>
		</div><!-- end column group -->
	</div><!-- end row -->
	
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-12">
			<br/>
			<h2>Contributors</h2>
			<p><?php
			$i=0;
			$x=1;
			foreach($data['projectData'] as $number=>$content)
			{ 
				if ($i == 1)
				{
					echo $data['projectData'][$number]->getAuthorFirstName()." ".$data['projectData'][$number]->getAuthorLastName()."</p><p>";
					$i=0;
				}
				elseif ($x ==5)
				{
					echo $data['projectData'][$number]->getAuthorFirstName()." ".$data['projectData'][$number]->getAuthorLastName();
					$i++;
				}
				else 
				{
					echo $data['projectData'][$number]->getAuthorFirstName()." ".$data['projectData'][$number]->getAuthorLastName().", ";
					$i++;
				}
				$x++;
			}
			?></p>
			<h2>Language</h2>
			<p><?php echo $data['projectData']['0']->getTag();?></p>
			<h2>Completed</h2>
			<p><?php echo $finishDate;?></p>
		</div><!-- end column group -->
		
		<div class="col-lg-8 col-md-8 col-sm-12">
			<br/>
			<h2>Project Brief</h2>
			<P><?php echo $data['projectData']['0']->getContent();?></p>
			<p>Pellentesque auctor mi ac sodales sollicitudin. Duis nec risus orci. Morbi ornare ut tellus quis malesuada. In id quam nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus sed auctor velit. Cras accumsan augue vel nulla dapibus, a porttitor dolor elementum. Praesent consequat, dolor malesuada convallis dictum, nulla tortor blandit felis, nec faucibus sapien velit sit amet felis. Phasellus commodo nec justo nec pulvinar. Quisque iaculis, metus ut tempus gravida, urna mi volutpat sapien, a rutrum tortor urna et nisl. Ut sed ullamcorper quam. </p>
		</div> <!-- end column group -->
	</div> <!-- end row -->
	
	<div class="row">
		<div class="col-sm-12 col-lg-12">
			<br/>
			<h3>Project Files</h3>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Title</th>
							<th>Download</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($data['fileData'] as $number=>$content)
						{ 
							$fileDate = $data['fileData'][$number]['date'];
							$date = date('d M Y', strtotime($fileDate));
							$filename = $data['fileData'][$number]['content'];
						
							echo "<tr>";
							echo "<td>".$data['fileData'][$number]['title']."</td>";
							echo "<td><a href='index.php?location=projectPage&&action=".$filename."'>".$filename."</a></td>";
							echo "<td>".$date."</td>";
							echo "</tr>";
						}?>
					</tbody>
				</table><!-- end table -->
			</div><!-- end responsive table -->
		</div><!-- end column group -->
	</div><!-- end row -->
<section>