<nav>
	<?php
		if(($_SESSION) == null)
		{
			//This would normally come from the database
			$item1 = array("location"=>"/index.php?location=home", "link" => "Home");
			$item2 = array("location"=>"/index.php?location=about", "link" => "About");
			$item4 = array("location"=>"/index.php?location=contact", "link" => "Contact");
			$item5 = array("location"=>"/index.php?location=login&action=logUserIn", "link" => "Log In");
			$item6 = array("location"=>"/index.php?location=register&action=registerUser", "link" => "Register");
			$menu = array($item1, $item2, $item4, $item5, $item6);
		}
		else
		{
			$item1 = array("location"=>"/index.php?location=home", "link" => "Home");
			$item2 = array("location"=>"/index.php?location=about", "link" => "About");
			$item3 = array("location"=>"/index.php?location=contact", "link" => "Contact");
			$item4 = array("location"=>"?location=login&action=logOutUser", "link" => "Log Out");
			$menu = array($item1, $item2, $item3, $item4);
		}
	?>
	<ul class="menu">
		<!-- display links for all menu items -->
		<?php foreach($menu as $menuItem) : ?>
		<li>
			<a href="<?php echo SITE_ROOT.$menuItem["location"]; ?>">
				<?php echo $menuItem["link"]; ?>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php echo "\n" ?>
