function checkNomeCognome(par,nome){
    var tag = document.getElementById(par);
    var ctrl = /^[a-zA-Z ]+$/;
    var string ="";

    if (tag.value == null || tag.value == "" || /^[ ]+$/.test(tag.value) || tag.value.length < 3 || tag.value.length > 20)
        string += "<li>Il campo "+nome+" deve contenere tra 3 e 20 caratteri</li>";

    if(!string && !ctrl.test(tag.value))
        string += "<li>Il campo "+nome+" non possono contenere caratteri speciali</li>";
    return string;
}

function checkNumerico(par,nome){
    var tag= document.getElementById(par);
    var string="";

    if (tag.value == null || tag.value == "")
        string += "<li>Il campo "+nome+" non può essere vuoto</li>";

    if(!string && /^[0-9]{1,5}$/.test(tag))
        string += "<li>Il campo "+nome+" può contenere sono numeri</li>";

    return string;
}

function checkPassword(par){
    var tag= document.getElementById(par);
    var string="";

    if(tag.value == null || tag.value == "" || /^[ ]+$/.test(tag.value) || tag.value.length < 6 || tag.value.length > 15)
        string += "<li>Il campo password deve contenere tra 6 e 15 caratteri</li>";
    
    return string;
}

function checkEmail(par){
    var tag= document.getElementById(par);
    var string="";
    var ctrl = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

    if (tag.value == null || tag.value == "" || /^[ ]+$/.test(tag.value))
        string +=  "<li>Il campo email non può essere vuoto</li>";
    
    if(!string && !ctrl.test(tag.value))
        string+= "<li>il campo email non deve conterene caratteri speciali oltre la chiocciola e il punto</li>";

    return string;
}


function checkDescrizione(par){
    var tag= document.getElementById(par);
    var string="";

    if (tag.value == null || tag.value == "" || /^[ ]+$/.test(tag.value))
        string += "<li>Il campo descrizione non può essere vuoto</li>";

    return string;
}

function checkCodiceFiscale(par){
    var tag= document.getElementById(par);    
    var string="";

    if (tag.value == null || tag.value == "" || /^[ ]+$/.test(tag.value))
        string += "<li>Il campo descrizione non può essere vuoto</li>";

    if (!string && !/^[a-zA-Z0-9]{1,20}$/.test(tag.value))
        string += "<li>Il campo non deve contenere caratteri speciali con massimo 20 caratteri</li>";

    return string;
}

function checkPartitaIva(par){
    var tag= document.getElementById(par);    
    var string="";

    if (!/^[a-zA-Z0-9]{0,50}$/.test(tag.value))
        string += "<li>Il campo non deve contenere caratteri speciali con massimo 50 caratteri</li>";
    return string;
}

function checkData(par){
    var tag = document.getElementById(par);    
    var string = "";
    var ctrl = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;

    if(!ctrl.test(tag.value))
        string += "<li>Formato data non valido, il formato deve essere di tipo gg/mm/aaaa</li>";

    return string;
}

function checkCodice(par){
    var tag = document.getElementById(par);    
    var string = "";
    var ctrl = /^[0-9a-zA-Z]{1,4}$/;

    if(!ctrl.test(tag.value))
        string += "<li>Il codice deve essere lungo da 1 a 4 caratteri non speciali o numeri</li>";

    return string;
}
/*function checkImmagine(par){
    var tag = document.getElementById(par);    
    var string = "";
    

    if(!par.value)
        string += "<li>Bisogna inserire l'immagine</li>";

    return string;
}*/
function checkNomeServizio(par){
    var tag = document.getElementById(par);    
    var string = "";
    var ctrl = /^[0-9a-zA-Z\s]{3,30}$/;

    if(!ctrl.test(tag.value))
        string += "<li>Il nome del servizio deve essere lungo da 3 a 15 caratteri non speciali o numeri</li>";

    return string;
}
/*Funzioni dirette*/
function checkRegistra(){
    var errori = checkEmail("email") + checkPassword("password") + checkNomeCognome("nome","nome") + checkNomeCognome("cognome","cognome")+ checkCodiceFiscale("codiceFiscale") + checkPartitaIva("Piva");
    if(errori){
        document.getElementById("messaggi").innerHTML = "<ul>" + errori + "</ul>";
        return false;
    }
    return true;
}
function checkLogin(){
    var errori = checkEmail("emailLogin") + checkPassword("passwordLogin");
    if(errori){
        document.getElementById("messaggi").innerHTML = "<ul>" + errori + "</ul>";
        return false;
    }
    return true;
}
function checkPrenota(){
    var errori = checkNumerico("npers","numero persone") + checkData("da") + checkData("a");
    if(errori){
        document.getElementById("messaggi").innerHTML = "<ul>" + errori + "</ul>";
        return false;
    }
    return true;
}

function inserimentoAppartamenti(){
    var errori = checkCodice("id") + checkNumerico("npers","massimo numero persone") + checkNumerico("dim","dimensione in mq") + checkDescrizione("desc");
    if(errori){
        document.getElementById("messaggi").innerHTML = "<ul>" + errori + "</ul>";
        return false;
    }
    return true;
}
function modificaAppartamenti(){
    var errori = checkNumerico("npers","massimo numero persone") + checkNumerico("dim","dimensione in mq") + checkDescrizione("desc");
    if(errori){
        document.getElementById("messaggi").innerHTML = "<ul>" + errori + "</ul>";
        return false;
    }
    return true;
}
function checkCostoAppartamento(){
    var errori = checkNumerico("costo","costo unitario") + checkData("inizio") + checkData("fine");
    if(errori){
        document.getElementById("messaggi").innerHTML = "<ul>" + errori + "</ul>";
        return false;
    }
    return true;
}
function checkServizio(){
    var errori = checkNumerico("costo","costo unitario") + checkNomeServizio("nome");
    if(errori){
        document.getElementById("messaggi").innerHTML = "<ul>" + errori + "</ul>";
        return false;
    }
    return true;
}
function checkFiltroData(){
    var errori = checkData("da") + checkData("a");
    if(errori){
        document.getElementById("messaggi").innerHTML = "<ul>" + errori + "</ul>";
        return false;
    }
    return true;
}