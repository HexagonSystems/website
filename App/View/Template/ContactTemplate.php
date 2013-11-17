<section>
	<h1> Contact Us</h1>
	<form class="col-sm-12 col-lg-6" name="contactUsForm" id="contactUsForm" action="mailto:hexagon@gmail.com"  method="post"  enctype="text/plain">

		<label for="email">Email</label>
		<input id="email" name="email" type="email" placeholder="Email" required>

		<label for="name">Name</label>
		<input id="name" name="name" type="text" placeholder="Name" required>

		<label for="msg">Message</label>
		<textarea id="msg" name="msg" type="textarea" rows="6" placeholder="Message" required></textarea> <!--max length??-->

		<div class="formNote">
			<p>All fields required</P>
			<button type="submit">Submit</button>
		</div>
		<div id="errors">
		</div>
	</form>
	<?php
	foreach ($data['member'] as $variable=>$content)   
	{
		?>
		<div class="col-xs-12 col-sm-6 col-lg-6">
			<a href="<?php echo $content->getEmail();?>">
				<p><?php echo $content->getFirstName()." ".$content->getLastName();?></p>
				<p><?php echo $content->getEmail();?></p>
			</a>
		</div>
		<?php
	}
	?> <!-- end foreach-->
</section>