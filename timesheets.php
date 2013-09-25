<?php
   //This is where the homepage will be. It will not be a router. It will import header/footer/nav/
   include "/includes/head.php";
   include "/includes/nav.php";
?>
	<div id="content">
		<section class="hexagon-searchBox">
		<img src="img/searchIcon.png" alt="Search">
		<input type="text" class="hexagon-searchBox"><button class="hexagon-smallFunctionButton">Search</button>
	</section>
		
		<div class="hexagon-solidTabBlockMedium hexagon-fillWidth hexagon-clickable">
			<div id="tabs" class="pure-menu pure-menu-open pure-menu-horizontal">
				<ul class="hexagon-fillWidth hexagon-colorGrey">
					<li><a href="#tabs-1">Tasks</a></li>
				</ul>
				<section id="tabs-1">
					<a href="timesheet_singleView.php">
					<article>
						<header>Task 001</header> <span>Alex, Alex, Alex</span>
						<p>Alex has added 2hrs to the project.</p>
						<footer>Posted at 2:20pm 17/09/2013</footer>
						</article>
					<article>
					</a>
					<header>Task 002</header> <span>Alex</span>
					<p>Just finished the menu, about to start the time sheets</p>
					<footer>Posted at 1:05pm 15/09/2013</footer>
					</article>
					<article>
					<header>Task 003</header> <span>Alex, Alex</span>
					<p>Alex has added 4hrs to the project.</p>
					<footer>Posted at 1:05pm 15/09/2013</footer>
					</article>
					<article>
					<header>Task 4</header> <span>Alex</span>
					<p>Alex has added 2hrs to the project.</p>
					<footer>Posted at 2:20pm 17/09/2013</footer>
					</article>
					<article>
					<header>Task 5</header> <span>Alex</span>
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