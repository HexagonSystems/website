<section>
	<h1>Add A File</h1>
	<h3><?php echo $data['proj']->getTitle(); ?></h3>
	<p><?php echo $data['proj']->getContent(); ?></p>
	
	<?php 
	// loops through the $data['files'] array to output the names of associated files
	if (!empty($data['files'][0])) {
	?>
		<label class="col-sm-2 text-right">Files</label>
		<div class="col-sm-10">
			<?php 	
			foreach($data['files'][0] as $index=>$file)
			{
				echo "<p>". $file['title']. "<br/>";
				echo $file['content']. "</p>";
			}
			?>
		</div><!-- end list of files -->
		<?php 	
		} //end if files
		?>
	
	<form action="index.php?location=adminPage&&action=saveChanges" method="post" class="col-sm-12 col-lg-12 form-horizontal" enctype="multipart/form-data"> 
		<div class="form-group">
			<label for="title" class="col-sm-2 control-label">Title</label> 
			<div class="col-sm-10 col-lg-10">
				<input class="form-control" type="text" id="title" name="title" title="Title" required />
			</div>
		</div><!-- end title group -->
		
		<div class="form-group">
			<label for="file" class="col-sm-2 control-label">File to Upload</label> 
			<div class="col-sm-10 col-lg-10">
				<input type="file" id="file" name="file" title="File to Upload" required />
				<span class="help-block">The accepted file types are: .gif .jpeg .png .txt .pdf .zip .doc .xls .docx .xlsx</span>
			</div>
		</div><!-- end file group -->
		
		<div class="form-group">
			<div class="col-lg-12">
				<div class="col-lg-2 pull-right">
					<input name="articleId" type="hidden" value="<?php echo $data['proj']->getArticleId();?>" />
					<input name="memberId" type="hidden" value="<?php echo unserialize($_SESSION['accountObject'])->getMemberId();?>" />
					<input name="projectName" type="hidden" value="<?php echo $data['proj']->getTitle();?>" />
					<input class="btn btn-primary btn-lg btn-block" name="action" type="submit" value="Upload" />
				</div>
			</div>
		</div><!-- end button group -->
	</form><!-- end form -->
</section>