<section>
	<h1>Add A File</h1>
	<?php echo unserialize($_SESSION['accountObject'])->getFirstName(); ?> <!---------------- for adding into memberarticle -------->
	<h3><?php echo $data['proj']->getTitle(); ?></h3>
	<p><?php echo $data['proj']->getContent(); ?></p>

	<label for="files" class="col-sm-2 control-label">Files</label>
	<div class="col-slg-10">
		<?php 				
		if (!isset($data['files'][0]))
		{
			foreach ($data['files'] as $key => $row ) 
			{
				//echo "<pre>";
				//var_dump($data['files']);
				
				echo "<p>". $row[$key]['content']."<br/>"; //$row[1]['content']
			}
		}
		?>
	</div>
	
	<form action="index.php?location=adminPage&&action=saveChanges" method="post" class="col-sm-12 col-lg-12 form-horizontal" enctype="multipart/form-data"> 
		
		<div class="form-group">
			<label for="title" class="col-sm-2 control-label">Title</label> 
			<div class="col-sm-10 col-lg-4 ">
				<input class="form-control" type="text" id="title" name="title" title="Title" required />
			</div>
		</div>
		
		<div class="form-group">
			<label for="file" class="col-sm-2 control-label">File to Upload</label> 
			<div class="col-sm-4">
				<input type="file" id="file" name="file" title="File to Upload" required />
			</div>
		</div><!-- end file group -->
		
		<div class="form-group">
			<div ><!--class="col-lg-offset-3"-->
				<div class=" col-lg-4">
					<input class="btn btn-default btn-block" name="action" type="submit" value="Cancel" />
				</div>
				<div class=" col-lg-4">
					<input name="articleId" type="hidden" value="<?php echo $data['proj']->getArticleId();?>" />
					<input name="projectName" type="hidden" value="<?php echo $data['proj']->getTitle();?>" />
					<input class="btn btn-default btn-block" name="action" type="submit" value="Upload" />
				</div>
			</div>
		</div>
	</form><!-- end form -->
</section>