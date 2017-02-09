<?php
	include "php/gestoreHeader.php";
	
	$var = array();
	$body = preparePage("html/attrazioni.html",$var);
	writePage("Attrazioni - Residence Al Mugo",$body,"Attrazioni","residence albergo siror di primiero al mugo attrazioni san martino di castrozza snowpark ski area tognola skitour passo rolle altopiano delle pale",login());
?>