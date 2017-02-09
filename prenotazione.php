<?php
	include "php/gestoreHeader.php";
	require_once('php/classi/appartamento.class.php');
    require_once('php/classi/prenotazione.class.php');
	require_once('php/classi/appartamento.class.php');

    $var = array('{listaServizi}'=>'','{messaggi}'=>'',
    '{appartamento}'=>'');
    if(!isset($_SESSION['prenotazioneAttiva']) || !appartamento::checkIDappartamento($_SESSION['prenotazioneAttiva']))
        header('location:prenotazioni.php');
    elseif(!login()){
        $_SESSION['pagina'] = 'prenotazione.php';
        header("location:login.php");
    }
    else {
    	$var['appartamento'] = $_SESSION['prenotazioneAttiva'];
    }
    try{
        if(isset($_POST['conferma'])){
            $appartamento = appartamento::appartamento($_SESSION['prenotazioneAttiva']);//so per certo che esiste perchè ho già controllato con check id appartamento
            
            $errore="";
            /**aggiunta prenotazione*/
            if(!isset($_POST['da']) || !prenotazione::checkData($_POST['da'])) {
                $errore = $errore . "<li>errore nella data di arrivo</li>";
            }
            if (!isset($_POST['a']) || !prenotazione::checkData($_POST['a'])) {
                $errore = $errore . "<li>errore nella data di partenza</li>";
            }
            if (!$errore && (!prenotazione::checkDatas($_POST['da'],$_POST['a'])) ) {
                $errore = $errore . "<li>Prenotazione minima un giorno</li>";
            }
            if (!isset($_POST['npers']) || !is_numeric($_POST['npers']) || $_POST['npers'] > $appartamento->getMax_persone()) {
                $errore = $errore . "<li>In questo appartamento ha la capienza di ".$appartamento->getMax_persone()." persone</li>";
            }
            if (!$errore && (prenotazione::checkDateLibere(prenotazione::formattaData($_POST['da']),prenotazione::formattaData($_POST['a']),$_SESSION['prenotazioneAttiva'])) ) {
                $errore = $errore . "<li>La data di prenotazione e gia impegnata</li>";
            }
            
            if(!$errore) {
                $servizi = array();
                $nuovaPrenotazione = prenotazione::prenota($_SESSION['utente']->getID(),$_SESSION['prenotazioneAttiva'],prenotazione::formattaData($_POST['a']),prenotazione::formattaData($_POST['da']),$_POST['npers']);
                /*aggiunta servizi*/
                if($nuovaPrenotazione){
                    $_SESSION['prenotazioneRiuscita'] = $_SESSION['prenotazioneAttiva'];
                    $_SESSION['prenotazioneAttiva']=null;
                    
                    if(isset($_POST['servizi'])){
                        foreach($_POST['servizi'] as $val){
                            array_push($servizi,servizio::servizio($val));
                        }
                        $risultatoAdd = $nuovaPrenotazione->addServizi($servizi);
                        
                    }
                   
                    header("location:riepilogoPrenotazioni.php");
                }
                else
                    $var['{messaggi}']="<li>Prenotazione fallita, la preghiamo di provare più tardi</li>";
            }
            if($errore) $var['{messaggi}'] = "<ul>".$errore."</ul>";
        }

        $listaServizi = servizio::getListaServizi();
        foreach($listaServizi as $servizio){
            $var['{listaServizi}'] = $var['{listaServizi}'].'<dd>
                        <input name="servizi[]" type="checkbox" value="'.$servizio->getIDservizio().'" id="servizio'.$servizio->getIDservizio().'"/>
                
                        <label for="servizio'.$servizio->getIDservizio().'">'.$servizio->getNome()
                        .' € '.$servizio->getCosto().' a '.$servizio->getUnita().'</label>
                        </dd>';
        }
    }
    catch(mysqli_sql_exception $e){
        $var['{messaggi}'] = "<ul><li>Il servizio al momento non disponibile, Server error: 500</li></ul>";
    }
        
    

	$body = preparePage("html/prenotazione.html",$var);
	writePage("Conferma prenotazione - Residence Al Mugo",$body,"","residence albergo siror di primiero san martino di castrozza al mugo prenotazioni prenota appartamento",login());
?>