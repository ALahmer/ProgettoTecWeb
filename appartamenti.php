<?php
	include "php/gestoreHeader.php";
	
	$var = array();
	$body = preparePage("html/appartamenti.html",$var);
	writePage("Appartamenti - Residence Al Mugo",$body,"Appartamenti","residence albergo siror di primiero san martino di castrozza al mugo appartamenti tipologie camere servizi",login());
?>