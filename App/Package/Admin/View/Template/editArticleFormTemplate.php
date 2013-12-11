<?php
	$help_file = "helpAdminEdit.php";
	include_once 'modal_help.php';
?>
<section>
	<h1>Edit Article
		<a data-toggle="modal" href="#modal_help" class="btn btn-sm">
			<span class="glyphicon glyphicon-question-sign"></span> Help
		</a>
	</h1>
	<form action="index.php?location=adminPage&&action=saveChanges" method="post" class="col-sm-12 col-lg-12 form-horizontal" id="editArticleForm" > 
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
			<label for="category" class="col-sm-2 control-label">Category Code</label>
			<div class="col-sm-10">
				<input class="form-control" type="text" id="category" name="category" value="<?php echo $data['proj']->getCategory(); ?>" title="Category" required />
				<span class="help-block">Categories: 1 = Bio, 2 = Project, 3 = File</span>
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
			<div class="col-lg-12">
				<div class="pull-right">
					<input name="articleId" type="hidden" value="<?php echo $data['proj']->getArticleId();?>" />
					<input class="btn btn-primary btn-lg" name="action" type="submit" value="Save" />
				</div>
			</div>
		</div>
		<div id="errors">
		</div><!-- end error -->
	</form><!-- end form -->
</section>

<script>
/**
*
* Validate the editArticleForm
* @author Tara Stevenson
*/
var container = $('div.error');

$(document).ready(function(){
	errorLabelContainer: $("#editArticleForm div.error")
	$("#editArticleForm").validate({
		
		errorContainer: container,
		errorLabelContainer: $("div", container),
		wrapper: 'p',
		rules: {
			title: {
				required: true,
				letterswithbasicpunc: true 
			},
			content: {
				required: true
			},
			category: {
				required: true,
				digits: true
			},
			tag: {
				required: true
			},
			date: {
				required: true
			}
		},
		messages: {
			title: {
				required: 'Please enter the title.',
				letterswithbasicpunc: 'Please enter letters only.'
			},
			content: {
				required: 'Please enter your description.'
			},
			category: {
				required: 'Please enter the category this article belongs to.',
				digits: 'Please enter numbers only.'
			},
			tag: {
				required: 'Please enter a tag for the article.'
			},
			date: {
				required: 'Please enter the date published.'
			}
		}
	});
});

</script>