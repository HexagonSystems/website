<section>
	<h1>Edit Article</h1>
	<form action="index.php?location=adminPage&&action=saveChanges" method="post" class="col-sm-12 col-lg-12 form-horizontal"> 
		
		<div class="form-group">
			<label for="title" class="col-sm-2 control-label">Title</label> 
			<div class="col-sm-10">
				<input class="form-control" type="text" id="title" name="title" value="<?php echo $data['proj']->getTitle(); ?>" title="Title" required />
			</div>
		</div><!-- end title group -->
		
		<div class="form-group">
			<label for="content" class="col-sm-2 control-label">Content</label> 
			<div class="col-sm-10">
				<textarea class="form-control" rows="4" type="text" id="content" name="content" title="Content" required /><?php echo $data['proj']->getContent(); ?></textarea> 
			</div>
		</div><!-- end content group -->
		
		<div class="form-group">
			<label for="category" class="col-sm-2 control-label">Category Code</label> tooltip about catagory
			<div class="col-sm-10">
				<input class="form-control" type="text" id="category" name="category" value="<?php echo $data['proj']->getCategory(); ?>" title="Category" required />
			</div>
		</div><!-- end category group -->
		
		<div class="form-group">
			<label for="status" class="col-sm-2 control-label">Status</label>
			<div class="col-sm-10">
				<select name='status' id='status' class="form-control"> 
				<option value=""></option>
					<?php
					$currentStatus = $data['proj']->getStatus();
					echo $currentStatus;
					foreach($data['select'] as $statusOption)
					{
						if($statusOption == $currentStatus)
						{
							echo '<option value="'.$statusOption.'" selected="selected">'.$statusOption.'</option>';
						} 
						else 
						{
							echo '<option value="'.$statusOption.'">'.$statusOption.'</option>';
						}
					}
					?>
				</select>
			</div>
		</div><!-- end status group -->
		
		<div class="form-group">
			<label for="tag" class="col-sm-2 control-label">Tag</label> 
			<div class="col-sm-10">
				<input class="form-control" type="text" id="tag" name="tag" value="<?php echo $data['proj']->getTag(); ?>" title="Tag" />
			</div>
		</div><!-- end tag group -->
		
		<div class="form-group">
			<label for="date" class="col-sm-2 control-label">Date</label> 
			<div class="col-sm-10">
				<input class="form-control" type="text" id="date" name="date" value="<?php echo $data['proj']->getDate(); ?>" title="Date" required />
			</div>
		</div><!-- end date group -->
		
		<?php 	
		if (!empty($data['files'][0])) {
		?>
		<div class="form-group">
			<label for="files" class="col-sm-2 control-label">Files</label>
			<div class="col-sm-10">
				<?php 	
				foreach($data['files'][0] as $index=>$file)
				{
					echo $file['title']. "<br/>";
				}
				?>
			</div>
		</div><!-- end list of files group -->
		<?php 	
		} //end if files
		?>
		<div class="form-group">
			<div class="col-lg-offset-2">
				<div class="pull-left">
					<input class="btn btn-default" name="action" type="submit" value="Cancel" />
				</div>
				<div class="pull-right">
					<input name="articleId" type="hidden" value="<?php echo $data['proj']->getArticleId();?>" />
					<input class="btn btn-default" name="action" type="submit" value="Save" />
				</div>
			</div>
		</div>
	</form><!-- end form -->
</section>