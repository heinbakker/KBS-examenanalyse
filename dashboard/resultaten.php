<?php
require_once('/../config/config.php');
require_once(ROOT_PATH . "includes/init.php");
$pagename = "resultaten";
session_start();

if (!isset($_SESSION['gebruiker_id'])) {
	$_SESSION['message'] = 'Je bent niet ingelogd.';
	header('Location: ' . BASE_URL);
}

if (isset($_SESSION['timeout']) && $_SESSION['timeout'] + SESSION_TIME < time()) {
	// sessie destroyen als sessie verlopen is.
	session_destroy();
	session_start();
	$_SESSION['message'] = 'Sessie is verlopen.';
	header('Location: ' . BASE_URL);
} else {
	//als sessie niet verlopen is sessie verlengen
	$_SESSION['timeout'] = time();
}
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
							  <div class="panel-heading">Dit is een voorbeeld hoe resultaten weergegeven zouden kunnen worden.</div>
							  <div class="panel-body">
							    <div class="demo-container">
									<div id="placeholder" class="demo-placeholder"></div>
									<div id="menu">
										<button style="display: none;" id="example-1"></button><!--deze regel niet weghalen-->
										
									</div>
								</div>
							  </div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="panel panel-default">
							  <div class="panel-heading">Voortgang weergegeven in een cijfer van de gemaakte examens.</div>
							  <div class="panel-body">
							    <?php 
									include(ROOT_PATH . "includes/partials/cijfersgemaakteexamens.html.php"); 
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
	<script type="text/javascript">
	$(function() {

		// Example Data
		var data = [
		<?php
		$rand = rand(2,4);
		for($x = 0;$x < $rand;$x++){
		?>
			{ label: "Examen <?php echo $x;?>",  data: <?php echo rand(1,100);?>}
			<?php
			if($rand != $x){
				echo",";
			}
			?>
		<?php
		}
		?>
		];
		
		var placeholder = $("#placeholder");

		
		

		$("#example-1").click(function() {

			placeholder.unbind();

			$("#title").text("Resultaten");
			$("#description").text("The pie can be tilted at an angle.");

			$.plot(placeholder, data, {
				series: {
					pie: { 
						show: true,
						radius: 1,
						tilt: 0.5,
						label: {
							show: true,
							radius: 1,
							formatter: labelFormatter,
							background: {
								opacity: 0.8
							}
						},
						combine: {
							color: "#999",
							threshold: 0.1
						}
					}
				},
				legend: {
					show: false
				}
			});

			setCode([
				"$.plot('#placeholder', data, {",
				"    series: {",
				"        pie: {",
				"            show: true,",
				"            radius: 1,",
				"            tilt: 0.5,",
				"            label: {",
				"                show: true,",
				"                radius: 1,",
				"                formatter: labelFormatter,",
				"                background: {",
				"                    opacity: 0.8",
				"                }",
				"            },",
				"            combine: {",
				"                color: '#999',",
				"                threshold: 0.1",
				"            }",
				"        }",
				"    },",
				"    legend: {",
				"        show: false",
				"    }",
				"});",
			]);
		});

		

		// Show the initial default chart

		$("#example-1").click();

		// Add the Flot version string to the footer

		$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
	});

	// A custom label formatter used by several of the plots

	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}

	//

	function setCode(lines) {
		$("#code").text(lines.join("\n"));
	}

	</script>
</html>

