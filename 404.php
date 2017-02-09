<?php
	include "php/gestoreHeader.php";
	require_once('php/classi/utente.class.php');
	
	$var = array();
	$body = preparePage("html/404.html",$var);
	writePage("404 - Residence Al Mugo",$body,"","residence pagina trovata montagna sciare",login());
?>