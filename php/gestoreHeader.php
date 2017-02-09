<?php
require_once('classi/utente.class.php');
session_start();
//modulo logout

if(isset($_GET['logOut'])){
    $_SESSION['utente']=null;
    $_SESSION['amministratore']=null;
    $_COOKIE['cookieLogin']=0;
    setcookie("cookieLogin", "", time() - 3600);
    header("location: index.php");
    //echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
}
//modulo login cookie
if((!isset($_SESSION['utente'])) && isset($_COOKIE['cookieLogin'])){
    if($user=utente::loginCookie($_COOKIE['cookieLogin'])){
        $_SESSION['utente'] = $user;
    }
}

function controlloLogin(){
    if(!isset($_SESSION['utente']))
        header("location: login.php");
}

function login(){
    if(isset($_SESSION['utente']) || isset($_SESSION['amministratore']))
        return true;
    return false;
}
function isAdmin(){
    if(isset($_SESSION['amministratore']))
        return true;
    return false;
}

function controlloAdmin(){
    if(!isset($_SESSION['amministratore'])){//dato che il costruttore di amministratore è privato, solo se ha inserito username e password può essere di quel tipo
        header("location: index.php");
    }
}

function creaMenu($index){
    $menu = '';
    $menuEl=null;
    if(isAdmin()){
        $menuEl=array(
          'Gestione Appartamenti'=>'aggiuntaAppartamenti.php',
          'Gestione Servizi'=>'servizi.php',
          'Gestione Prenotazioni'=>'gestionePrenotazioni.php'
        );
    }
    else{
        $menuEl=array(
          'Home'=>'index.php',
          'Appartamenti'=>'appartamenti.php',
          'Attrazioni'=>'attrazioni.php',
          'Prenota'=>'prenotazioni.php',
          'Contatti'=>'contatti.php'
        );
        if(login()){
          $menuEl['Riepilogo']='riepilogoPrenotazioni.php';
        }
    }
            
    foreach($menuEl as $nome=>$link){
        if($nome==$index)
            $menu = $menu.'<li>'.$nome.'</li>';
        else
            $menu = $menu.'<li class="nascondi"><a href="'.$link.'">'.$nome.'</a></li>';
            
    }

    return $menu;
}

function preparePage($link,$arrayMod){
    $page = file_get_contents($link);
    foreach($arrayMod as $tag=>$value){
         $page = str_replace($tag,$value,$page);       
    }
    return $page;
}

function writePage($title,$body,$index,$keywords,$login){
    $page = file_get_contents("html/struttura.html");
    $page = str_replace('{title}',$title,$page);
    $page = str_replace('{keywords}',$keywords,$page);
    $page = str_replace('{menu}',creaMenu($index),$page);
    if(!$login){
        $page = str_replace('{login}',"Login",$page);
        $page = str_replace('{loginLink}',"login.php",$page);
    }
    else {
        $page = str_replace('{login}',"Logout",$page);
        $page = str_replace('{loginLink}',"?logOut=1",$page);
    }
    //$page = str_replace('{menu}',prepareMenu($index),$page);
    $page = str_replace('{body}',$body,$page);
    echo($page);
}
?>