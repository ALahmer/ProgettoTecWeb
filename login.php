<?php
	include "php/gestoreHeader.php";
	require_once("php/classi/utente.class.php");
	require_once("php/classi/amministratore.class.php");

	//se è loggato, non ha sento rientri nel login
	if(login()){
		if(isAdmin())
			header("location:aggiuntaAppartamenti.php");
		else	
			header("location:index.php");
	}

	$var = array('{messaggi}'=>'');

try{
	/*preparazione pagina*/
	if(isset($_POST['registrati'])) {
		$errore = "";
		$nome = $_POST['nome'];
		$cognome = $_POST['cognome'];
		$email = $_POST['email'];
		$codiceFiscale = $_POST['codiceFiscale'];
		$partitaIVA = $_POST['partitaIVA'];
		$password = $_POST['password'];
		if(!isset($nome) || !utente::checkNome($nome)) {
			$errore = $errore . "<li>errore nel nome</li>";
		}
		if (!isset($cognome) || !utente::checkNome($cognome)) {
			$errore = $errore . "<li>errore nel cognome</li>";
		}
		if(!isset($codiceFiscale) || !utente::checkCodFiscale($codiceFiscale)) {
			$errore = $errore . "<li>errore nel codice fiscale</li>";
		}
		if(!empty($partitaIVA)) {
			if(!isset($partitaIVA) || !utente::checkPiva($partitaIVA)) {
				$errore = $errore . "<li>errore nella partita IVA</li>";
			}
		}
		if (!isset($email) || !utente::email_exist($email)) {
			$errore = $errore . "<li>errore nella mail</li>";
		}
		if (!isset($password) || !utente::checkPassword($password)) {
			$errore = $errore . "<li>errore nella password</li>";
		}
		if(empty($errore)) {
			$_SESSION['utente'] = utente::register($email,$nome,$cognome,$codiceFiscale,$partitaIVA,$password);
			if($_SESSION['utente'])
				header("Location: index.php");
			else
				$var['{messaggi}'] = "<ul><li>l'utente esiste gi&agrave</li></ul>";
		} else {
			$var['{messaggi}'] = "<ul>" . $errore . "</ul>";
			
		}
	}
	if(isset($_POST['accedi'])) {
			if(utente::email_exist($_POST['email'])) {
				if ($amministratore = amministratore::login($_POST['email'],$_POST['password'])) {
						$_SESSION['amministratore'] = $amministratore;
						header("location: aggiuntaAppartamenti.php");
					}
				elseif ($utente = utente::login($_POST['email'],$_POST['password'])) {
					$_SESSION['utente'] = $utente;
					if(isset($_SESSION['pagina']) && ($_SESSION['pagina']!="")) {
						$pagina = $_SESSION['pagina'];
						$_SESSION['pagina']= "";
						//echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$pagina.'">';
						header("location: " . $pagina);
					} else {
						header("location: index.php");
					}
				}

			}
			$var['{messaggi}'] = "<ul><li>Email o password errati</li></ul>";
	}
}
catch(mysqli_sql_exception $e){
	$var['{messaggi}'] = "<ul><li>Il servizio al momento non disponibile, Server error: 500</li></ul>";
}
	
	$body = preparePage("html/login.html",$var);
	writePage("Login - Residence Al Mugo",$body,"","residence albergo siror di primiero san martino di castrozza al mugo login registrazione accedi register",login());
?>