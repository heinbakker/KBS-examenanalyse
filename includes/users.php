<?php
//voorbeeld
//functies met betrekking tot gebruikers

function passTest($pass, $pass_confirm) {
    if (!empty($pass)) { //checkt of de string empty is
        if (7 < strlen($pass)){ //checkt of het wachtwoord wel 8 of meer karakters is
            if (strcspn($pass, '0123456789') != strlen($pass)){ //checkt of het wachtwoord nummers bevat
                if (strcspn($pass, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ') != strlen($pass)) { //checkt of het wachtwoord een hoofdletter heeft
                	if($pass_confirm == $pass) {
                    return TRUE;
                	}
                	else {
                		$_SESSION['message'] = "Wachtwoorden komen niet overeen";
                	}
                }
                else {
                    $_SESSION['message'] = "Wachtwoord moet een hoofdletter bevatten";
                }
            }
            else {
                $_SESSION['message'] = "Wachtwoorden moet een cijfer bevatten";                    
            }
        }
        else {
            $_SESSION['message'] = "Wachtwoord moet minimaal 8 of meer karakters bevatten";
        }
    }
    else {
        $_SESSION['message'] = "Wachtwoord veld is leeg";
    }
}

