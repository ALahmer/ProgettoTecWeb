<?php
	include "php/gestoreHeader.php";
    require_once('php/classi/appartamento.class.php');
    require_once('php/classi/prenotazione.class.php');

    controlloAdmin();
	$var = array(
        '{messaggi}'=>'',
        '{tbody}'=>'',
        '{appartamento}'=>'',
        '{IdAppartamento}'=>'',
        '{npers}'=>'',
        '{dimensione}'=>'',
        '{descrizione}'=>'',
        '{immagineAttuale}'=>''
    );

    $idApp = NULL;
    $errore = "";
    try{
        if(isset($_GET['modifica'])) {
            $idApp = $_GET['modifica'];
            if ($appartamento = appartamento::appartamento($idApp)) {
                $var['{appartamento}'] = $idApp;
                $var['{IdAppartamento}'] = $idApp;
                $var['{npers}'] = $appartamento->getMax_persone();
                $var['{dimensione}'] = $appartamento->getDimensione();
                $var['{descrizione}'] = $appartamento->getDescrizione();
                clearstatcache();
                $var['{immagineAttuale}'] = '<img src="immagini/'.$idApp.'.jpg" alt="Immagine appartamento"/>';
            }

        }
        if(isset($_GET['modifica']) && isset($_POST['aggiungi']) && isset($_POST['costo']) && isset($_POST['inizio']) && isset($_POST['fine'])){
            $Appart = $_GET['modifica'];

            if(!appartamento::checkIDappartamento($Appart)) $errore = $errore . "<li>Formato \"codice appartamento\" non valido</li>";
            if(!prenotazione::checkData($_POST['inizio'])) $errore = $errore . "<li>Formato \"data  inizio\" non valido</li>";
            if(!prenotazione::checkData($_POST['fine'])) $errore = $errore . "<li>Formato \"data  fine\" non valido</li>";
            if(!prezzi_appartamento::checkCosto($_POST['costo'])) $errore = $errore . "<li>Formato \"costo\" non valido</li>";
            
            $dataDecorrenzaDA = prenotazione::formattaData($_POST['inizio']);
            $dataDecorrenzaA = prenotazione::formattaData($_POST['fine']);

            if(prezzi_appartamento::checkPeriodo($dataDecorrenzaDA,$dataDecorrenzaA,$Appart)) $errore = $errore . "<li>Esiste gia una fascia di costo per questo periodo</li>";
            
            if(!$errore){
                $aggiuntoPrezzoPeriodo = prezzi_appartamento::add($Appart,$dataDecorrenzaDA,$dataDecorrenzaA,$_POST['costo']);
                if(!$aggiuntoPrezzoPeriodo) $errore = $errore . "<li>Laggiunta del costo periodo non è andata a buon fine</li>";
            }
        }

        elseif(isset($_GET['id']) && isset($_POST['modificaAppartamento'])){
            $App = $_GET['id'];
            $tempApp = NULL;
            if(!appartamento::checkIDappartamento($App)) $errore = $errore . "<li>Formato \"codice appartamento\" non valido</li>";
            if(!appartamento::checkMax_persone($_POST['npers'])) $errore = $errore . "<li>Formato \"massimo persone\" non valido</li>";
            if(!appartamento::checkDimensione($_POST['dim'])) $errore = $errore . "<li>Formato \"dimensione\" non valido</li>";
            if($errore == "") $tempApp = appartamento::modifica($App,$_POST['npers'],$_POST['dim'],htmlentities($_POST['desc'], ENT_QUOTES));
            if(!$tempApp) $errore = $errore . "<li>La modifica non è andata a buon fine</li>";
            if($tempApp && isset($_FILES['immagine']))
            if(!$tempApp->modificaImmagine()){$errore = $errore . "<li>errore inserimento immagine</li>";}
            
            header("location: modificaAppartamento.php?modifica=$App");
        }

        elseif(isset($_GET['cancella']) && isset($_GET['da']) && isset($_GET['a'])){
            $idApp = $_GET['cancella'];
            $da = $_GET['da'];
            $a = $_GET['a'];
            if(!prezzi_appartamento::checkIDappartamento($idApp)) $errore = $errore . "<li>Formato \"codice appartamento\" non valido</li>";
            if(!prezzi_appartamento::checkDa($da)) $errore = $errore . "<li>Formato \"data  inizio\" non valido</li>";
            //if(!prenotazione::checkData($da)) $errore = $errore . "<li>Formato \"data  inizio\" non valido</li>";
            if(!prezzi_appartamento::checkA($a)) $errore = $errore . "<li>Formato \"data  fine\" non valido</li>";
            //if(!prenotazione::checkData($a)) $errore = $errore . "<li>Formato \"data  fine\" non valido</li>";
            if($errore == "") $result = prezzi_appartamento::remove($idApp,$da,$a);
            if(!$result) $errore = $errore . "<li>Eliminazione costo periodo non riuscita</li>";

            header("location: modificaAppartamento.php?modifica=$idApp");
        } 
        if($errore) $var['{messaggi}'] = "<ul>". $errore ."</ul>";

        if($idApp){
            foreach($listaPrezzi = prezzi_appartamento::getPrezzi($idApp) as $prezzo){
                $var['{tbody}'] = $var['{tbody}'].'<tr>
                        <td headers="c1" axis="data_da">'.prenotazione::formattaDataIta($prezzo->getDa()).'</td>
                        <td headers="c2" axis="data_a">'.prenotazione::formattaDataIta($prezzo->getA()).'</td>
                        <td headers="c3" axis="euro">'.$prezzo->getCostoGiornaliero().'</td>
                        <td headers="c4" axis="link"><a href="?cancella='.$prezzo->getIDappartamento().'&amp;da='.$prezzo->getDa().'&amp;a='.$prezzo->getA().'">Elimina</a></td>
                    </tr>';
            
            }
        }
    }
    catch(mysqli_sql_exception $e){
        $var['{messaggi}'] = "<ul><li>Il servizio al momento non disponibile, Server error: 500</li></ul>";
    }
        

    $body = preparePage("html/modificaAppartamento.html",$var);
	writePage("Modifica appartamento - Residence Al Mugo",$body,"","residence albergo siror di primiero san martino di castrozza al mugo modifica appartamento aggiungi elimina costo periodo",login());
?>