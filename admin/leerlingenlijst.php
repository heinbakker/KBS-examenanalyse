<?php
// de admin pagina, dit wordt natuurlijk nog uitgebreid.
require_once("/../includes/init.php");

checkSession();
checkIfAdmin();

if(!isset($_GET['klas'])) {
	header('Location: '  . BASE_URL . 'admin/klassenlijst.php');
	exit;
} else {
	
	$klas = $_GET['klas'];

	if($_SERVER["REQUEST_METHOD"] == "POST") {
	    //****************  LEERLING TOEVOEGEN ******************//

	    if(isset($_POST['submit_add_leerling'])) {
	        if (isset($_POST['voornaam'], $_POST['achternaam'], $_POST['leerling_id'], $_POST['emailadres'])) {
	            //checken of er geen lege waarden zijn ingevoerd
	            if ($_POST['voornaam'] == "" OR $_POST['achternaam'] == "" OR $_POST['leerling_id'] == "" OR $_POST['emailadres'] == "") {
	                $_SESSION['message'] = "Je moet alle gegevens invullen!";
	            } 
	            else {
	            	//binnenkomende array ombouwen
	            	unset($_POST['submit_add_leerling']);
	            	$leerlingen = $_POST;
	            	$temp_array = array_values($leerlingen);
	         		$count = count($temp_array[0]) ;

	         		for($j = 0; $j<$count; $j++) {
	         			$i = 0;
	            		foreach($leerlingen as $key => $value){
		            		$leerlingen[$key] = $temp_array[$i][$j];
		            		$i++;
						}
						$gegevens[] = $leerlingen;
					}
	   
	                // overbodige ingevoerde spaties weghalen met functie trim
	                foreach($gegevens as $values => $keys) {
	            	
		                $gegevens[$values]["voornaam"] = filter_var($gegevens[$values]["voornaam"], FILTER_SANITIZE_STRING);
		                $gegevens[$values]["achternaam"] = filter_var(trim($gegevens[$values]["achternaam"]), FILTER_SANITIZE_STRING);
		                $gegevens[$values]["tussenvoegsel"] = filter_var($gegevens[$values]["tussenvoegsel"], FILTER_SANITIZE_STRING);//tussenvoegsel mag spatie bevatten
		                $gegevens[$values]["leerling_id"] = filter_var(trim($gegevens[$values]["leerling_id"]), FILTER_SANITIZE_STRING);
		                $gegevens[$values]["emailadres"] = filter_var(trim($gegevens[$values]["emailadres"]), FILTER_VALIDATE_EMAIL);
		                $gegevens[$values]["klas"] = $klas;	       
		                $gegevens[$values]["role"] = 1; // is leerling
		                $gegevens[$values]["account_activated"] = 0; //account is nog niet geactiveerd, dit wordt pas gedaan als gebruiker eerste keer inlogt.
		                $gegevens[$values]["generated_password"] = generate_random_password();
		                $gegevens[$values]["wachtwoord"] = password_hash($gegevens[$values]["generated_password"], PASSWORD_BCRYPT);
		                $gegevens[$values]["email_code"] = md5($gegevens[$values]["voornaam"] + microtime());
	  				}

	  				$emailcheck = $gegevens[$values]["emailadres"];
	                if (!$emailcheck) {
						$_SESSION['message'] = 'Voer een geldig e-mailadres in';
					}
					else {
						//checken of email en student_id uniek zijn
		                foreach($gegevens as $leerling_gegevens) {
				            if(checkIfUserExists($leerling_gegevens['emailadres']) === FALSE){
				                //email adres niet in gebruik, dus gebruiker kan worden toegevoegd.
				                // gegevens inserten
				                addUser($leerling_gegevens);
				                
				                addStudent($leerling_gegevens["emailadres"], $leerling_gegevens["leerling_id"], $leerling_gegevens["klas"]);

				                //wachtwoord mailen naar gebruiker
				                $mail_content = createTempPasswordMail($leerling_gegevens);
				                sendMail($mail_content);
				            } else {
				                //email adres in gebruik gebruiker wordt op de hoogte gesteld dat dit email adres bezet is.
				              	$_SESSION['message'] = "Email adres " . $leerling_gegevens['emailadres'] . " is al in gebruik";
		                	}
		                }
		            }
	            }
	        }
	    }
	    if(isset($_POST["submit_bewerk_leerling"])) {
			if ($_POST['voornaam'] == "" OR $_POST['achternaam'] == "" OR $_POST['leerling_id'] == "" OR $_POST['emailadres'] == "") {
				$_SESSION['message'] = "Je moet alle gegevens invullen!";
			} 
			else {
				// overbodige ingevoerde spaties weghalen met functie trim
				$voornaam = filter_var(trim($_POST['voornaam']), FILTER_SANITIZE_STRING);
				$achternaam = filter_var(trim($_POST['achternaam']), FILTER_SANITIZE_STRING);
				$tussenvoegsel = filter_var($_POST['tussenvoegsel'], FILTER_SANITIZE_STRING);//tussenvoegsel mag spatie bevatten
				$emailadres = filter_var(trim($_POST['emailadres']), FILTER_VALIDATE_EMAIL);
				$leerling_id = filter_var(trim($_POST['leerling_id']), FILTER_SANITIZE_STRING);
				$gebruiker_id = intval($_POST['gebruiker_id']);
				if (!$emailadres) {
					$_SESSION['message'] = 'Voer een geldig e-mailadres in.';
				}
				else {	
					$gegevens = [ 
						"voornaam" => $voornaam,
						"tussenvoegsel" => $tussenvoegsel,
						"achternaam" => $achternaam,
						"emailadres" => $emailadres,
						"leerling_id" => $leerling_id,
					];

					updateStudent($gegevens,$gebruiker_id);

				}
			}
	    }
	    if(isset($_POST["submit_verwijder_leerling"])) {
	    	$gebruiker_id = intval($_POST['gebruiker_id']);

	    	deleteStudent($gebruiker_id);
	    }
	}
}

$leerlingen = getLeerlingenKlas($klas);

$pagename = "klassen";
?>


<!DOCTYPE html>
	<?php include(ROOT_PATH . "includes/templates/header.php");?>
	<div class="wrapper">
		<?php include(ROOT_PATH . "includes/templates/sidebar-admin.php"); ?>
		<div class="page-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo 'Klas: ' . $klas ?></h3>
                            </div>
                            <div class="panel-body">
								<table class="table  gebruiker-gegevens">
								    <thead>
								      <tr>
								        <th>Leerlingnummer</th>
								        <th>Voornaam</th>
								        <th>Tussenvoegsel</th>
								        <th>Achternaam</th>				        
								        <th>E-mail Adres</th>
								      </tr>
								    </thead>
								    <tbody>					    	
								    	<?php 
								    		if(isset($leerlingen))  
								    			include(ROOT_PATH . "includes/partials/leerlingenlijst.html.php"); 
								    	?>
								    	<tr>
								    		<td style="border: 0;">
									    		<!-- Button trigger leerling toevoegen modal -->
												<button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#leerling-toevoegen">
												  Leerling Toevoegen
												</button>
											</td>
										</tr>
									</tbody>
								</table>

								<!-- Leerling bewerken/verwijderen Modal -->
								<?php
									foreach ($leerlingen as $leerling) {
										foreach ($leerling as $key => $value) {
											include(ROOT_PATH . "includes/partials/modals/leerling_bewerk_modal.html.php");
										}
									}
								?>

								<!-- Leerling toevoegen Modal -->
								<?php					
									include(ROOT_PATH . "includes/partials/modals/leerling_toevoegen_modal.html.php");
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