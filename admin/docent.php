<?php

// de admin pagina, dit wordt natuurlijk nog uitgebreid.
require_once('/../config/config.php');
require_once(ROOT_PATH . "includes/init.php");
require_once(ROOT_PATH . 'includes/admin_functions.php');

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	//****************  DOCENT TOEVOEGEN ******************//

	if(isset($_POST['submit_docent'])) {
		if (isset($_POST['voornaam'], $_POST['achternaam'], $_POST['afkorting'], $_POST['emailadres'])) {
			//checken of er geen lege waarden zijn ingevoerd
			if ($_POST['voornaam'] == "" OR $_POST['achternaam'] == "" OR $_POST['afkorting'] == "" OR $_POST['emailadres'] == "") {
				$_SESSION['message'] = "Je moet alle gegevens invullen!";
			} 
			else {
				// overbodige ingevoerde spaties weghalen met functie trim
				$voornaam = filter_var(trim($_POST['voornaam']), FILTER_SANITIZE_STRING);

				$achternaam = filter_var(trim($_POST['achternaam']), FILTER_SANITIZE_STRING);
				$tussenvoegsel = filter_var($_POST['tussenvoegsel'], FILTER_SANITIZE_STRING);//tussenvoegsel mag spatie bevatten
				$docent_afkorting = filter_var(trim($_POST['afkorting']), FILTER_SANITIZE_STRING);
				$emailadres = filter_var(trim($_POST['emailadres']), FILTER_VALIDATE_EMAIL);
				if (!$emailadres) {
					$_SESSION['message'] = 'Voer een geldig e-mailadres in.';
				}
				else {	
					$role = 2; // is leraar
					$account_activated = 0; //account is nog niet geactiveerd, dit wordt pas gedaan als gebruiker eerste keer inlogt.
					$generated_password = generate_random_password();
					$wachtwoord = password_hash($generated_password, PASSWORD_BCRYPT);
					$email_code = md5($voornaam + microtime());

					//returned $generated_password

					$gegevens = [ 
						"voornaam" => $voornaam,
						"tussenvoegsel" => $tussenvoegsel,
						"achternaam" => $achternaam,
						"emailadres" => $emailadres,
						"email_code" => $email_code,
						"generated_password" => $generated_password,
						"wachtwoord" => $wachtwoord,
						"account_activated" => $account_activated,
						"role" => $role,
						"docent_afkorting" => $docent_afkorting,
					];

					//checken of email en afkorting uniek zijn
					if(checkIfUserExists($gegevens["emailadres"]) === FALSE){
						//email adres niet in gebruik, dus gebruiker kan worden toegevoegd.
						// gegevens inserten
						addUser($gegevens);
						addTeacher($gegevens["emailadres"], $gegevens["docent_afkorting"]);
						//nog niet af!!
						//wachtwoord mailen naar gebruiker
						$mail_content = createTempPasswordMail($gegevens);
						sendMail($mail_content);
					} else {
						//email adres in gebruik gebruiker wordt op de hoogte gesteld dat dit email adres bezet is.
						$_SESSION['message'] = "Email adres is al in gebruik";
					}
				}
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Examen Analyse</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="theme-color" content="#1BBC9B">
		<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
		<link rel="stylesheet" href="../assets/css/style.css" type="text/css" media="all">
		<link rel="stylesheet" href="../assets/css/dashboard.css" type="text/css" media="all">
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	</head>
	<body>
		<?php include(ROOT_PATH . "includes/partials/message.html.php"); ?>
		<div class="stickymenu">
			<div class="titlemenu">
				<div class="logoimg">
					<img src="../images/dashboard/logo_fruytier.png" alt="logo_fruytier">
					<img src="../images/dashboard/logo.png" alt="logo">
				</div>
				<div class="apptitle">
					<h1>EXAMENANALYSE</h1>
				</div>
			</div>
			<div class="usermenu">
				<div class="headicon">
					<img src="../images/dashboard/maleicon.png" alt="headicon">
				</div>
				<div class="username">
					<h3><?php $data = getUserData($_SESSION['gebruiker_id']);echo $data['voornaam']." ".$data['tussenvoegsel']." ".$data['achternaam'];?></h3>
				</div>
				<div class="settings">
					<img src="../images/dashboard/settings.png" alt="settings">
					<ul class="submenu">
						<li>
							<a href="#" class="submenuitem">Settings</a>
						</li>
						<li>
							<a href="../includes/logout.php" class="submenuitem">Uitloggen</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="sidemenu">
			<ul>
				<a href="/" class="menulink">
					<li class="menuheading">
						Dashboard
					</li>
				</a>
				<a href="#" class="menulink">
					<li class="menuitem">
						Leerling(en) toevoegen
					</li>
				</a>
				<a href="<?php echo BASE_URL; ?>admin/docent.php" class="menulink">
					<li class="menuitem">
						Docent Toevoegen
					</li>
				</a>
				<a href="#" class="menulink">
					<li class="menuitem">
						Klas toevoegen
					</li>
				</a>
				<a href="#" class="menulink">
					<li class="menuitem">
						Examens toevoegen
					</li>
				</a>
				<a href="#" class="menulink itembottom">
					<li class="menuitem">
						Settings
					</li>
				</a>
				<a href="../includes/logout.php" class="menulink itembottom">
					<li class="menuitem">
						Uitloggen
					</li>
				</a>
			</ul>
		</div>
		<div class="contentblock">
			<div class="content">
				<h2>Voeg leraar toe</h2>
				<form action="" method="POST">
		  			Voornaam <input type="text" name="voornaam"><br>
		  			Tussenvoegsel <input type="text" name="tussenvoegsel"><br>
		  			Achternaam <input type="text" name="achternaam"><br>
		  			Afkorting <input type="text" name="afkorting"><br>
		  			Emailadres <input type="text" name="emailadres"><br>
		  			<input type="submit" name="submit_docent" value="Voeg toe">
				</form>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<script src="<?php echo BASE_URL; ?>assets/js/alert_message.js"></script>
	</body>
</html>
