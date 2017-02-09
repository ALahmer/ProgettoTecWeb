<?php
	include "php/gestoreHeader.php";
	require_once('php/classi/servizio.class.php');

	controlloAdmin();

	$var = array(
        '{messaggi}'=>'',
        '{tbody}'=>''
    );
	try{
		if(isset($_GET['cancella']) && is_numeric($_GET['cancella'])){
			servizio::deleteServizio($_GET['cancella']);
		} elseif(isset($_POST['inserisci'])) {
			unset($_POST['inserisci']);
			$nome = $_POST['nome'];
			$costo = $_POST['costo'];
			$unita = $_POST['unita'];
			$errore = "";
			if(!isset($nome) || !servizio::checkNome($nome)) {
				$errore = $errore . "<li>errore nel nome</li>";
			}
			if (!isset($costo) || !servizio::checkCosto($costo)) {
				$errore = $errore . "<li>errore nel costo</li>";
			}
			if(empty($errore)) {
				servizio::addServizio($nome,$costo,$unita);
				$var['{messaggi}'] = "servizio aggiunto con successo";
			} else {
				$var['{messaggi}'] = "<ul>" . $errore . "</ul>";
			}
		}
		$listaServizi = servizio::getListaServizi();
		foreach($listaServizi as $servizio){
			$var['{tbody}'] = $var['{tbody}'].'<tr>
						<td headers="c1">'.$servizio->getNome().'</td>
						<td headers="c2" axis="euro">'.$servizio->getCosto().' €</td>
						<td headers="c3">'.$servizio->getUnita().'</td>
						<td headers="c4" axis="opt"><a href="?cancella='.$servizio->getIDServizio().'">Elimina</a></td></tr>';  
		}
	}
    catch(mysqli_sql_exception $e){
        $var['{messaggi}'] = "<ul><li>Il servizio al momento non disponibile, Server error: 500</li></ul>";
    }
	$body = preparePage("html/gestioneServizi.html",$var);
	writePage("Servizi - Residence Al Mugo",$body,"Gestione Servizi","residence albergo siror di primiero san martino di castrozza al mugo aggiungi lista servizi servizio appartamento",login());
?>