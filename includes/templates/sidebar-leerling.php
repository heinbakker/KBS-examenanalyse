<div class="sidebar-wrapper">
	<ul class="sidebar-nav list-unstyled">
		<li>
			<a class="<?php if ($pagename == "dashboard"){echo "active-nav";}?>" href="<?php echo BASE_URL; ?>dashboard/">Dashboard</a>
		</li>
		<li>
			<a class="<?php if ($pagename == "examenresultatentoevoegen"){echo "active-nav";}?>" href="<?php echo BASE_URL; ?>dashboard/examenresultatentoevoegen.php">Examenresultaten toevoegen</a>
		</li>
		<li>
			<a class="<?php if ($pagename == "resultaten"){echo "active-nav";}?>" href="<?php echo BASE_URL; ?>dashboard/resultaten.php">Resultaten en feedback</a>
		</li>
	</ul>
	<ul class="sidebar-nav settings-nav list-unstyled">
		<li>
			<a class="<?php if ($pagename == "settings"){echo "active-nav";}?>" href="<?php echo BASE_URL; ?>dashboard/settings.php">Settings</a>
		</li>
		<li>
			<a href="<?php echo BASE_URL; ?>includes/logout.php">Uitloggen</a>
		</li>
	</ul>
</div>