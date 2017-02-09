<?php
	include "php/gestoreHeader.php";
	require_once('php/classi/appartamento.class.php');
    require_once('php/classi/prenotazione.class.php');
	require_once('php/classi/appartamento.class.php');
	
	$var = array('{divlista}'=>'','{messaggi}' => '');

	try{
		$listaAppartamenti = array();
		if(isset($_GET['prenota']) && appartamento::checkIDappartamento($_GET['prenota'])){
			if(login()){
				$_SESSION['prenotazioneAttiva'] = $_GET['prenota'];
				header('location:prenotazione.php');
			}
			else{
				$_SESSION['prenotazioneAttiva'] = $_GET['prenota'];
				$_SESSION['pagina'] = 'prenotazione.php';
				header('location:login.php');
			}
		}elseif(isset($_POST['cerca'])){
			if(prenotazione::checkData($_POST['a']) && prenotazione::checkData($_POST['da']) && is_numeric($_POST['npers'])) {
				$listaAppartamenti = appartamento::getListaAppartamenti(prenotazione::formattaData($_POST['da']),prenotazione::formattaData($_POST['a']), $_POST['npers']);
			} else {
				$var['{messaggi}'] = "<li>formato data o numero di persone non corretto</li>";
				$listaAppartamenti = appartamento::getListaAppartamenti(NULL,NULL,NULL);
			}

		}else{
			$listaAppartamenti = appartamento::getListaAppartamenti(NULL,NULL,NULL);
		}

		if($var['{messaggi}']) $var['{messaggi}']="<ul>".$var['{messaggi}']."</ul>"; 

		$var['{divlista}']=$var['{divlista}'].'<dl class="listaImmagini">';
		foreach($listaAppartamenti as $appartamento){
			$var['{divlista}']=$var['{divlista}'].'<dt>Appartamento - '.$appartamento->getIDappartamento().'</dt>
				<dd ><img src="immagini/'.$appartamento->getIDappartamento().'.jpg" alt="Immagine interno appartamento '.$appartamento->getIDappartamento().'" /></dd>
				<dd ><span class="grassetto">Capienza:</span> '.$appartamento->getMax_persone().' persone</dd>
				<dd ><span class="grassetto">Dimensione:</span> '.$appartamento->getDimensione().' mq</dd>
				<dd class="centro"><span class="grassetto">Descrizione:</span> '.$appartamento->getDescrizione().'</dd>
				<dd class="centro"><a href="?prenota='.$appartamento->getIDappartamento().'" class="pulsante">Prenota</a></dd>';
		}
		$var['{divlista}']=$var['{divlista}'].'</dl>';
	}
	catch(mysqli_sql_exception $e){
		$var['{messaggi}'] = "<ul><li>Il servizio al momento non disponibile, Server error: 500</li></ul>";
	}
	

	$body = preparePage("html/prenotazioni.html",$var);
	writePage("Prenotazioni - Residence Al Mugo",$body,"Prenota","residence albergo siror di primiero san martino di castrozza al mugo prenotazioni prenota appartamento",login());
?>