<?php
	include "php/gestoreHeader.php";
	require_once('php/classi/utente.class.php');
	
	$var = array();
	$body = preparePage("html/home.html",$var);
	writePage("Home - Residence Al Mugo",$body,"Home","residence albergo siror di primiero san martino di castrozza al mugo home homepage descrizione ubicazione",login());
?>