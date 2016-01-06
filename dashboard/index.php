<?php
require_once('/../config/config.php');
require_once(ROOT_PATH . "includes/init.php");
$pagename = "dashboard";
checkSession();
checkIfAdminIsLoggedOn();
?>
<!DOCTYPE html>
<html>
	<body>
		<?php include(ROOT_PATH . "includes/partials/message.html.php"); ?>
        <?php include(ROOT_PATH . "includes/templates/header.php");?>
		<div class="wrapper">
			<?php 
			//als docent ingelogd is sidebar-docent anders sidebar-leerling
			if(checkRole($_SESSION['gebruiker_id']) == 2){
				include(ROOT_PATH . "includes/templates/sidebar-docent.php"); 
			}else{
				include(ROOT_PATH . "includes/templates/sidebar-leerling.php"); 
			}
			?>
			<div class="page-wrapper">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-default">
							  <div class="panel-heading"><h3 class="panel-title">Panel heading without title</h3></div>
							  <div class="panel-body">
							    Panel content
							  </div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="panel panel-default">
							  <div class="panel-heading"><h3 class="panel-title">Categorieverdeling van alle examens</h3></div>
							  <div class="panel-body">
							    <?php 
									include(ROOT_PATH . "includes/partials/categorieverdeling.html.php"); 
								?>
							  </div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="panel panel-default">
							  <div class="panel-heading"><h3 class="panel-title">Panel heading without title</h3></div>
							  <div class="panel-body">
							    Panel content
							  </div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="panel panel-default">
							  <div class="panel-heading"><h3 class="panel-title">Panel heading without title</h3></div>
							  <div class="panel-body">
							    Panel content
							  </div>
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
		<?php include(ROOT_PATH . "includes/templates/footer.php");?>
	</body>
</html>

