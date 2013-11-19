<section>
	<h1>Our Work</h1>
	<?php
	
	foreach ($data['projectData'] as $number=>$content)   
	{
		
		$firstName = $data['projectData'][$number]->getAuthorFirstName();
		$lastName = $data['projectData'][$number]->getAuthorLastName();
		$group = $data['projectData'][$number]->getArticleId();

		if ($group == 6)
		{
			$newarr[] =$firstName;
		}
		if ($group == 7)
		{
			$proj[] =$firstName." ".$lastName;
		}
		
	}
	?>
	<div class="col-xs-12 col-sm-12 col-lg-12">
		<a href="index.php?location=projectPage&&action=<?php echo $data['projectData']['6']->getArticleId();?>">
			<div class="group-proj proj-transition">
				<h2 class="project-title"><?php echo $data['projectData']['6']->getTitle();?></h2>			
			</div>
		</a>
		<h4><?php echo $data['projectData']['6']->getTitle();?></h4>									
		<p><?php foreach ($newarr as $name){ echo $name." "; }?></p>									
		<p><?php echo $data['projectData']['6']->getTag(); ?></p>	
	</div><!-- end GROUP project-->
	
	<div class="col-xs-12 col-sm-6 col-lg-3">
		<a href="index.php?location=projectPage&&action=<?php echo $data['projectData'][0]->getArticleId();;?>">
			<div class="individual-proj proj-transition dosmon">
				<h3 class="project-title"><?php echo $data['projectData'][0]->getTitle();?></h3>
			</div>
		</a>
		<h4><?php echo $data['projectData'][0]->getTitle();?></h4>									
		<p><?php echo $data['projectData'][0]->getAuthorFirstName()." ".$data['projectData'][0]->getAuthorLastName();?></p>
		<p><?php echo $data['projectData'][0]->getTag();?></p>
	</div><!-- end single project-->
	
	<div class="col-xs-12 col-sm-6 col-lg-3">
		<a href="index.php?location=projectPage&&action=<?php echo $data['projectData'][1]->getArticleId();?>">
			<div class="individual-proj proj-transition snakes">
				<h3 class="project-title"><?php echo $data['projectData'][1]->getTitle();?></h3>
			</div>
		</a>
		<h4><?php echo $data['projectData'][1]->getTitle();?></h4>									
		<p><?php echo $data['projectData'][1]->getAuthorFirstName()." ".$data['projectData'][1]->getAuthorLastName();?></p>
		<p><?php echo $data['projectData'][1]->getTag();?></p>
	</div><!-- end single project-->
	
	<div class="col-xs-12 col-sm-6 col-lg-3">
		<a href="index.php?location=projectPage&&action=<?php echo $data['projectData'][2]->getArticleId();?>">
			<div class="individual-proj proj-transition">
				<h3 class="project-title"><?php echo $data['projectData'][2]->getTitle();?></h3>
			</div>
		</a>
		<h4><?php echo $data['projectData'][2]->getTitle();?></h4>									
		<p><?php echo $data['projectData'][2]->getAuthorFirstName()." ".$data['projectData'][2]->getAuthorLastName();?></p>
		<p><?php echo $data['projectData'][2]->getTag();?></p>
	</div><!-- end single project-->
	
	<div class="col-xs-12 col-sm-6 col-lg-3">
		<a href="index.php?location=projectPage&&action=<?php echo $data['projectData'][8]->getArticleId();?>">
			<div class="individual-proj proj-transition poker">
				<h3 class="project-title"><?php echo $data['projectData'][8]->getTitle();?></h3>
			</div>
		</a>
		<h4><?php echo $data['projectData'][8]->getTitle();?></h4>									
		<p><?php foreach ($proj as $name){ echo $name." "; }?></p>
		<p><?php echo $data['projectData'][8]->getTag();?></p>
	</div><!-- end single project-->
	
<section>