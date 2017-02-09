<?php
	include "php/gestoreHeader.php";
	require_once('php/classi/prenotazione.class.php');

	controlloAdmin();

	$var = array('{messaggi}'=> '','{tbody}' => '');

	try{
		if(isset($_GET['checkin']) && is_numeric($_GET['checkin'])) {
			$prenotazione = prenotazione::prenotazione($_GET['checkin']);
			$prenotazione->setStato('arrivo');
		}
		elseif(isset($_GET['checkout']) && is_numeric($_GET['checkout'])) {
			$prenotazione = prenotazione::prenotazione($_GET['checkout']);
			$prenotazione->setStato('partenza');
		}
		
		if(isset($_POST['filtra']) && isset($_POST['da']) && isset($_POST['a'])){
			if(!prenotazione::checkdata($_POST['da']) || !prenotazione::checkdata($_POST['a']))
				$var['{messaggi}'] = "<ul><li>Il formato della data non è valido</li></ul>";
			else
				$prenotazioni = prenotazione::getPrenotazioniData(prenotazione::formattaData($_POST['da']),prenotazione::formattaData($_POST['a']));
		}
		if(!isset($prenotazioni)) $prenotazioni = prenotazione::getPrenotazioni(NULL);

		foreach($prenotazioni as $prenotazione){
			$var['{tbody}'] = $var['{tbody}'].'<tr>
						<td headers="c1">'.$prenotazione->getAppartamento().'</td>
						<td headers="c2">'.$prenotazione->getData_partenza().'</td>
						<td headers="c3">'.$prenotazione->getData_arrivo().'</td>
						<td headers="c4">'.$prenotazione->getStato() . '</td>
						<td headers="c5"><a href="?checkin='.$prenotazione->getIDprenotazione().'">Checkin</a><a href="?checkout='.$prenotazione->getIDprenotazione().'">Checkout</a><a href="preventivi.php?fattura='.$prenotazione->getIDprenotazione().'">Fattura</a></td></tr>';  
		}//checkin->imposta stato arrivo ,checkout->imposta stato partenza,fattura
    }
    catch(mysqli_sql_exception $e){
        $var['{messaggi}'] = "<ul><li>Il servizio al momento non disponibile, Server error: 500</li></ul>";
    }
        
	$body = preparePage("html/gestionePrenotazioni.html",$var);
	writePage("Gestione prenotazioni - Residence Al Mugo",$body,"Gestione Prenotazioni","residence albergo siror di primiero san martino di castrozza al mugo gestione prenotazioni check in checkin check out checkout fattura",login());
?>