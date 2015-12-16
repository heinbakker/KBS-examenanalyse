<?php

foreach($leerlingen as $leerling) {
	echo "<tr>";
	foreach($leerling as $key => $value) {
		if($key != 'gebruiker_id') {
			echo '<td>'  
			. $value  
			. '</td>';
		}
	}
	echo 
		'<td>
			<button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#' . $leerling["leerling_id"] . '">
				Bewerken
			</button>
		</td>
	</tr>';
}