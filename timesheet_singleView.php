<?php
   //This is where the homepage will be. It will not be a router. It will import header/footer/nav/
   include "/includes/head.php";
   include "/includes/nav.php";
?>
	<div id="content" class="hexagon-sizeForDesktop">
		<h4 class="hexagon-displayInline">Task_001</h4>
		<div class="hexagon-float-right">
			<button class="hexagon-smallFunctionButton">Leave</button>
			<button class="hexagon-smallFunctionButton">Edit</button>
		</div>
		<section class="hexagon-sideNote">
			<header>Members</header>
			<ul>
				<li><a href="timesheet_times.php">Alex Robinson</a></li>
			</ul>
		</section>
		<article class="hexagon-marginMedium">
		This is the project description.
		This is the project description. This is the project description. This
		is the project description. This is the project description. This is
		the project description. This is the project description. This is the
		project description. This is the project description.</article>
		<div class="hexagon-solidTabBlockMedium">
			<div id="tabs" class="pure-menu pure-menu-open pure-menu-horizontal">
				<ul class="hexagon-fillWidth hexagon-colorGrey">
					<li><a href="#tabs-1">Updates</a></li>
					<li><a href="#tabs-2">Time Sheets</a></li>
				</ul>
				<section id="tabs-1">
					<article>
					<header>Updated time</header> <span>Automatically posted</span>
					<p>Alex has added 2hrs to the project.</p>
					<footer>Posted at 2:20pm 17/09/2013</footer>
					</article>
					<article>
					<header>Updated Git</header> <span>Alex</span>
					<p>Just finished the menu, about to start the time sheets</p>
					<footer>Posted at 1:05pm 15/09/2013</footer>
					</article>
					<article>
					<header>Updated time</header> <span>Automatically posted</span>
					<p>Alex has added 4hrs to the project.</p>
					<footer>Posted at 1:05pm 15/09/2013</footer>
					</article>
					
				</section>
				<section id="tabs-2">
					<article>
					<header>Updated time</header> <span>Automatically posted</span>
					<p>Alex has added 2hrs to the project.</p>
					<footer>Posted at 2:20pm 17/09/2013</footer>
					</article>
					<article>
					<header>Updated time</header> <span>Automatically posted</span>
					<p>Alex has added 4hrs to the project.</p>
					<footer>Posted at 1:05pm 15/09/2013</footer>
					</article>
				</section>
			</div>
		</div>
	</div>
	<?php
   include "/includes/footer.php";
?>