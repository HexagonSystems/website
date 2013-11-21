<section>
<h1>Edit Site Information</h1>
<?php
if(isset($_SESSION['fileMsg'])) 
{ 
	echo $_SESSION['fileMsg']; 
	unset($_SESSION['fileMsg']);
}
?>
<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Category</th>
				<th>Title</th>
				<th>Content</th>
				<th>Tag</th>
				<th>Date</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach( $data['proj'] as $no => $content)
		{
			$date = $data['proj'][$no]->getDate();
			$finishDate = date('d M Y', strtotime($date));
			
			$category = $data['proj'][$no]->getCategory();
			if ($category == 1){
				$category = "Bio";
				$action = "<input class='btn btn-default btn-block' name='alter' type='submit' value='Edit'/>";
			}
			if ($category == 2){
				$category = "Project";
				$action = "<input class='btn btn-default btn-block' name='alter' type='submit' value='Edit'/><input class='btn btn-default btn-block' name='alter' type='submit' value='Upload'/>";
			}
			?>
			<tr>
				<td><?php echo $category;?></td>
				<td><?php echo $data['proj'][$no]->getTitle();?></td>
				<td><?php echo $data['proj'][$no]->getContent();?></td>
				<td><?php echo $data['proj'][$no]->getTag();?></td>
				<td><?php echo $finishDate;?></td>
				<td><?php echo $data['proj'][$no]->getStatus();?></td>
				<td>
					<form action="index.php?location=adminPage&&action=alter" method="post">
						<input name="articleId" type="hidden" value="<?php echo $data['proj'][$no]->getArticleId();?>" />
						<?php echo $action; ?>
					</form>
				</td>
			</tr>
		
		<?php
		}
		?>
		</tbody>
	</table>
</div>
</section>