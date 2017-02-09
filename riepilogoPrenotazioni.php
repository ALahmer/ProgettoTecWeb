<?php
	include "php/gestoreHeader.php";
	require_once('php/classi/prenotazione.class.php');

	controlloLogin();//se non c'è l'utente loggato, fa andare al login

	$var = array('{messaggi}'=> '','{tbody}' => '');
    
	try{
		if(isset($_GET['cancella']) && is_numeric($_GET['cancella'])){
			$prenotazione = prenotazione::prenotazione($_GET['cancella']);
			if($prenotazione)
				$prenotazione->cancella();
		}

		$prenotazioni = prenotazione::getPrenotazioni($_SESSION['utente']->getID());
		foreach($prenotazioni as $prenotazione){
			$var['{tbody}'] = $var['{tbody}'].'<tr>
						<td headers="c1">'.$prenotazione->getAppartamento().'</td>
						<td headers="c2" axis="data da">'.$prenotazione->getData_partenza().'</td>
						<td headers="c3" axis="data a">'.$prenotazione->getData_arrivo().'</td>
						<td headers="c4" axis="opt"><a href="?cancella='.$prenotazione->getIDprenotazione().'">Elimina</a></td></tr>';  
		}
		if(isset($_SESSION['prenotazioneRiuscita'])){
			$var['{messaggi}']="Prenotazione dell'appartamento ".$_SESSION['prenotazioneRiuscita']." riuscita con successo!";
			unset($_SESSION['prenotazioneRiuscita']);
		}
	}
    catch(mysqli_sql_exception $e){
        $var['{messaggi}'] = "<ul><li>Il servizio al momento non disponibile, Server error: 500</li></ul>";
    }
        
	$body = preparePage("html/vediPrenotazioni.html",$var);
	writePage("Riepilogo prenotazioni - Residence Al Mugo",$body,"Riepilogo","residence albergo siror di primiero san martino di castrozza al mugo riepilogo prenotazioni appartamento appartamenti",login());
?>