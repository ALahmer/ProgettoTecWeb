<?php
	include "php/gestoreHeader.php";
	require_once('php/classi/prenotazione.class.php');
	require_once('php/classi/utente.class.php');

	controlloAdmin();

	$var = array(
	'{messaggi}'=>'',
	'{nome}' => '',
	'{cognome}' =>'',
	'{cf}' =>'',
	'{piva}' =>'',
	'{appartamento}' =>'',
	'{listaServizi}'=>'',
	'{numeroPersone}'=>'');

	try{
		if(isset($_GET['fattura']) && is_numeric($_GET['fattura'])){
			$costoTotale = 0;
			//echo('lol');
			$prenotazione = prenotazione::prenotazione($_GET['fattura']);
			//var_dump($prenotazione);
			$utente = utente::getUserByID($prenotazione->getUtente());
			//var_dump($utente);
			$var['{nome}']=$utente->getNome();
			$var['{cognome}']=$utente->getCognome();
			$var['{cf}']=$utente->getCf();
			$var['{piva}']=$utente->getPiva();
			$var['{appartamento}']=$prenotazione->getAppartamento();
			$var['{numeroPersone}']=$prenotazione->getNumPersone();
			$var['{listaServizi}'] = $var['{listaServizi}'].'<tr>
					<td headers="c1">1</td>
					<td headers="c2">Affitto appartamento '.$prenotazione->getAppartamento().' </td>
					<td headers="c3" axis="euro">'.$prenotazione->getCosto().' €</td>
				</tr>';
			$costoTotale += $prenotazione->getCosto(); 
			$i=2;
			$serviziApplicati = $prenotazione->getServizi();
			foreach($serviziApplicati as $servizio){
				$costoServizio = 0;
				if($servizio->getUnita()=='persona') $costoServizio = $prenotazione->getNumPersone() * $servizio->getCosto();
				elseif($servizio->getUnita()=='appartamento') $costoServizio = $servizio->getCosto();
				elseif($servizio->getUnita()=='giornaliero') $costoServizio = $prenotazione->giorniPrenotati() * $servizio->getCosto();

				$var['{listaServizi}'] = $var['{listaServizi}'].'<tr>
					<td headers="c1">'.$i.'</td>
					<td headers="c2">'.$servizio->getNome().'</td>
					<td headers="c3" axis="euro">'.$costoServizio.' €</td>
				</tr>';
				$i++;
				$costoTotale += $costoServizio;
			}
		}
		$var['{listaServizi}'] = $var['{listaServizi}']. '<tr class="totale">
		<td headers="c1">'.$i.'</td>
		<td headers="c2">Totale</td><td headers="c3" axis="euro">'.$costoTotale.' €</td>
		</tr>';
    }
    catch(mysqli_sql_exception $e){
        $var['{messaggi}'] = "<ul><li>Il servizio al momento non disponibile, Server error: 500</li></ul>";
    }
        
	$body = preparePage("html/calcoloCosto.html",$var);
	writePage("Fattura - Residence Al Mugo",$body,1,"residence albergo siror di primiero san martino di castrozza al mugo gestione prenotazioni fattura costo prenotazione",login());
?>