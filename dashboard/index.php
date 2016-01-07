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
							  <div class="panel-heading"><h3 class="panel-title">Voortgang weergegeven in een cijfer van de gemaakte examens.</h3></div>
							  <div class="panel-body">
							    <?php 
									include(ROOT_PATH . "includes/partials/cijfersgemaakteexamens.html.php"); 
								?>
							  </div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="panel panel-default">
							  <div class="panel-heading"><h3 class="panel-title">Welkom <?php $data = getUserData($_SESSION['gebruiker_id']);echo $data['voornaam']." ".$data['tussenvoegsel']." ".$data['achternaam'];?></h3></div>
							  <div class="panel-body">
								Beste leerling,<br><br>
								Deze applicatie gaat jou helpen bij het halen van jou examen Nederlands! Door gebruik te maken deze applicatie zul je niet alleen doelgerichter leren, maar ook minder tijd kwijt zijn aan de voorbereiding van het examen. Nadat je in de klas alle onderdelen die op het examen Nederlands hebt behandeld ga je natuurlijk oefenexamens maken. Deze applicatie biedt jou de mogelijkheid om per examen per vraag de score in te voeren. De applicatie gaat voor jou een overzicht maken die per categorie aangeeft hoeveel jij nog moet oefenen. Ook krijg je adviezen welke examenvragen je nog een keer zou kunnen oefenen om beter te worden in een bepaalde categorie. We hopen dat je wat aan deze appliatie zal hebben. 
								<br><br> Veel succes bij het halen van je examens!
							  </div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="panel panel-default">
							  <div class="panel-heading"><h3 class="panel-title">Categorieverdeling van alle examens</h3></div>
							  <div class="panel-body">
							    <?php 
									include(ROOT_PATH . "includes/partials/categorieverdeling.html.php"); 
								?>
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

