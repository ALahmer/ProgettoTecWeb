<?php
	include "php/gestoreHeader.php";
    require_once('php/classi/appartamento.class.php');

    //aggiunta funzione vedi amministratore
    controlloAdmin();

	$var = array(
        '{messaggi}'=>'',
        '{tbody}'=>''
    );
    try{
        if(isset($_GET['modifica'])){
            $idApp = $_GET['modifica'];
            header("location: modificaAppartamento.php?modifica=$idApp");
        }
        elseif(isset($_GET['cancella'])){
            $errore = "";
            $result = appartamento::remove($_GET['cancella']);
            if(!$result) $errore = $errore . "<li>Eliminazione appartamento non riuscita</li>";
            $var['{messaggi}'] = "<ul>". $errore ."</ul>";        
        }
        elseif(isset($_POST['inserisci'])){
            $errore = NULL;
            $tempApp = NULL;
            if(!appartamento::checkIDappartamento($_POST['id'])) $errore = $errore . "<li>Formato \"codice appartamento\" non valido</li>";
            if(!appartamento::checkMax_persone($_POST['npers'])) $errore = $errore . "<li>Formato \"massimo persone\" non valido</li>";
            if(!appartamento::checkDimensione($_POST['dim'])) $errore = $errore . "<li>Formato \"dimensione\" non valido</li>";
            $nomeImmagine = $_FILES['immagine']['name'];
            if(!$nomeImmagine || !isset($_FILES['immagine'])) $errore = $errore . "<li>\"Immagine\" non trovata</li>";
            
            if(!$errore) {
                $tempApp = appartamento::add($_POST['id'],$_POST['npers'],$_POST['dim'],htmlentities($_POST['desc'], ENT_QUOTES));
                if($tempApp){ 
                    if(!($tempApp->caricaImmagine())) 
                        $errore = $errore . "<li>errore inserimento immagine</li>";
                }
                else
                    $errore = $errore . "<li>errore nell'inserimento dell'appartamento</li>";
            }
            if($errore != "") $var['{messaggi}'] = "<ul>". $errore ."</ul>";
        }

        foreach($listaAppartamenti = appartamento::getListaAppartamenti(NULL,NULL,NULL) as $appartamento){
            $var['{tbody}'] = $var['{tbody}'].'<tr>
                        <td headers="c1" axis="codice">'.$appartamento->getIDappartamento().'</td>
                        <td headers="c2" axis="n persone">'.$appartamento->getMax_persone().'</td>
                        <td headers="c3" axis="link"><a href="?modifica='.$appartamento->getIDappartamento().'">Modifica</a><a href="?cancella='.$appartamento->getIDappartamento().'">Elimina</a></td>
                    </tr>';
            
        }
    }
    catch(mysqli_sql_exception $e){
        $var['{messaggi}'] = "<ul><li>Il servizio al momento non disponibile, Server error: 500</li></ul>";
    }

    $body = preparePage("html/aggiuntaAppartamenti.html",$var);
	writePage("Gestione appartamenti - Residence Al Mugo",$body,"Gestione Appartamenti","residence albergo siror di primiero san martino di castrozza al mugo gestione appartamenti inserisci appartamento modifica elimina appartamento",login());
?>