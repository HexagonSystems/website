<section>
	<h1> Contact Us</h1>
	<form class="col-sm-12 col-lg-8 form-horizontal" name="contactUsForm" action="index.php?location=contactPage&&action=email"  method="post">
		<div class="form-group">
			<label for="email" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
				<input class="form-control" id="email" name="email" type="email" placeholder="Email" required>
			</div>
		</div>

		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">Name</label>
			<div class="col-sm-10">
				<input class="form-control" id="name" name="name" type="text" placeholder="Name" required>
			</div>
		</div>
		
		<div class="form-group">
			<label for="msg" class="col-sm-2 control-label">Message</label>
			<div class="col-sm-10">
				<textarea class="form-control" id="msg" name="msg" type="textarea" rows="6" placeholder="Message" required></textarea> <!--max length??-->
			</div>
		</div>
		
		<div class="form-group">
			<p class="col-sm-2 control-label"><small>All fields required</small></p>
			<div class="col-sm-10">
				<button type="submit" name="submit" class="btn btn-default btn-block">Submit</button>
			</div>
		</div>
		
		<div class="form-group">
			<span class="col-sm-2 control-label"> </span>
				<div class="col-sm-10">
				<?php
				if (isset($data['note']))
				{
					if ($data['note'] == true){
						echo "<p class='text-success text-center'>Thank you. Your mail was sent.</p>";
					}
					else{
						echo "<p class='text-danger text-center'>Sorry there was an error. Please try again.</p>";
					}
				}
				?>
				</div>
		</div>
		<div id="errors">
		</div>
	</form>
	<?php
	foreach ($data['member'] as $variable=>$content)   
	{
		?>
		<div class="col-xs-12 col-sm-6 col-lg-4">
			<a href="mailto:<?php echo $content->getEmail();?>">
				<p class="text-center"><?php echo $content->getFirstName()." ".$content->getLastName();?></p>
				<p class="text-center"><?php echo $content->getEmail();?></p>
			</a>
		</div>
		<?php
	}
	?> <!-- end foreach-->
</section>