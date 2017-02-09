<?php
	include "php/gestoreHeader.php";
	
	$var = array();
	$body = preparePage("html/contatti.html",$var);
	writePage("Contatti - Residence Al Mugo",$body,"Contatti","residence albergo siror di primiero san martino di castrozza al mugo contattaci come arrivare numero telefono fax",login());
?>