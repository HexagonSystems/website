<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button"
				class="pull-left navbar-btn btn-sidebar visible-xs"
				data-toggle="offcanvas" data-target="offcanvas">
				<span class="sr-only">Tasks</span> <span
					class="glyphicon glyphicon-chevron-right"></span>
			</button>
			<button type="button" class="navbar-toggle" data-toggle="collapse"
				data-target=".topmenu">
				<span class="sr-only">Toggle navigation</span> <span
					class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Hexagon Systems</a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<nav class="collapse navbar-collapse topmenu">
			<ul class="nav navbar-nav">
				<li class="active"><a href="index.php?location=indexPage">Home</a></li>
				<li><a href="index.php?location=aboutPage">About</a></li>
				<li><a href="index.php?location=projectPage">Projects</a></li>
				<li><a href="index.php?location=contactPage">Contact</a></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle"
					data-toggle="dropdown">Alex's Timesheets<b class="caret"></b>
				</a>
					<ul class="dropdown-menu">
						<li><a href="index.php?location=timesheetPage&action=all">Tasks</a></li>
						<li role="presentation" class="divider"></li>
						<li><a href="index.php?location=timesheetPage&action=report">Hours report</a></li>
						<li role="presentation" class="divider"></li>
						<li><a href="index.php?location=timesheetPage&action=search">Search</a></li>
					</ul>
				</li>
				<li role="presentation" class="divider"></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle"
					data-toggle="dropdown">Timesheets <b class="caret"></b>
				</a>
					<ul class="dropdown-menu">
						<li><a href="#">Home</a></li>
						<li role="presentation" class="divider"></li>
						<li><a href="#">About</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><span class="navbar-brand"><?php echo unserialize($_SESSION['accountObject'])->getFirstName(); ?>
				</span></li>
				<li><a href="index.php?location=login&action=logout">Logout</a></li>
			</ul>
		</nav>
		<!-- /.navbar-collapse -->
	</div>
</header>
<!-- begin container wrap -->
<div id="content" class="container">