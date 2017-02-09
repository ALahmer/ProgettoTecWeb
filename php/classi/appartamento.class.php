<?php
require_once('MyDB.class.php');

class prezzi_appartamento {
	private $IDappartamento;
	private $da;
	private $a;
	private $costo_giornaliero;
    
    public function __construct($ID,$DA,$A,$COSTO) {
		$this->IDappartamento = $ID;
		$this->da = $DA;
		$this->a = $A;
		$this->costo_giornaliero = $COSTO;
	}
    
	public function getIDappartamento() {return $this->IDappartamento;}
	public function getDa() {return $this->da;}
	public function getA() {return $this->a;}
	public function getCostoGiornaliero() {return $this->costo_giornaliero;}

    public static function add($ID,$DA,$A,$COSTO) {
        if( isset($ID) && isset($DA) && isset($A) && isset($COSTO) ){
            $result = MyDB::doQueryWrite("INSERT INTO prezzi_appartamenti (appartamento,da,a,costo_giornaliero) VALUES ('$ID','$DA','$A','$COSTO')");
			if($result){
                return true;
			}
		}
		return false; // se ritorna false, vuol dire che non è andata la query
	}
    	
    public static function getPrezzi($appartamento) {
        $listaPrezzi = array();
        $query = "SELECT da, a, costo_giornaliero FROM prezzi_appartamenti WHERE appartamento = '$appartamento' ";
        $result = MyDB::doQueryRead($query);
        while($result && $row = mysqli_fetch_row($result)){
            array_push($listaPrezzi,new prezzi_appartamento($appartamento,$row[0],$row[1],$row[2]));
        }
        return $listaPrezzi;
	}
    
    public static function remove($ID,$DA,$A) {
        //echo "sono nella query delleliminazione";
		$result = MyDb::doQueryWrite("DELETE FROM prezzi_appartamenti WHERE appartamento = '$ID' && da = '$DA' && a = '$A'");
        return $result;
	}
    
    public static function checkIDappartamento($string) {
		if(preg_match("/^[a-zA-Z0-9]{1,4}$/",$string))
			return true;
		return false;
    }
	public static function checkCosto($string) {
		if(preg_match("/^[0-9]{1,4}$/",$string))
			return true;
		return false;
    }

	public static function checkPeriodo($da,$a,$app){
		$query = "SELECT * FROM prezzi_appartamenti 
		WHERE (('$da' BETWEEN da AND a) 
		OR (da BETWEEN '$da' AND '$a')) 
		AND appartamento = '1A'";
		$result = MyDB::doQueryRead($query);
		return (mysqli_fetch_row($result));
	}
}

class appartamento {
	private $IDappartamento;
	private $Max_persone;
	private $Dimensione;
	private $Descrizione;

	public static function appartamento($id) {
		$query = "SELECT id, max_persone, dimensione, descrizione FROM appartamenti WHERE id='$id'";
		$result = MyDB::doQueryRead($query);
		if($result){
			$row = mysqli_fetch_row($result);
			return new appartamento($row[0],$row[1],$row[2],$row[3]);
		}
		return null;
	}

	private function __construct($id,$max_persone,$dimensione,$descrizione) {
		$this->IDappartamento=$id;
		$this->Max_persone=$max_persone;
		$this->Dimensione=$dimensione;
		$this->Descrizione=$descrizione;
	}

	public function getIDappartamento() {return $this->IDappartamento;}
	public function getMax_persone() {return $this->Max_persone;}
	public function getDimensione() {return $this->Dimensione;}
	public function getDescrizione() {return $this->Descrizione;}
    
    public static function checkIDappartamento($string) {
		if(preg_match("/[a-zA-Z0-9]{1,4}/",$string))
			return true;
		return false;
        }
	public static function checkMax_persone($string) {
		if(preg_match("/[0-9]{1,2}/",$string))
			return true;
		return false;
        }
	public static function checkDimensione($string) {
		if(preg_match("/[0-9]{1,4}/",$string))
			return true;
		return false;
        }
    
    public static function getListaAppartamenti($da, $a, $nPers) {
        if(!$da && !$a && !$nPers) { //se chiamo la funzione con i parametri non settati
            $listaAppartamenti = array();
            $query = "SELECT id, max_persone, dimensione, descrizione FROM appartamenti;";
            $result = MyDB::doQueryRead($query);
            while($result && $row = mysqli_fetch_row($result)){
                array_push($listaAppartamenti,new appartamento($row[0],$row[1],$row[2],$row[3]));
			}
            return $listaAppartamenti;
		} elseif($da && $a && $nPers){  //se tutti i campi sono settati
			//da fare nella pagina prenotazioni dopo correzione database
			$query = "SELECT * FROM appartamenti WHERE id NOT IN (SELECT appartamento FROM prenotazioni WHERE (('$da' BETWEEN data_arrivo AND data_partenza) OR (data_partenza BETWEEN '$da' AND '$a')))";
			if($nPers) {
				$query = $query . "AND max_persone >= $nPers";
			}
			$result = MyDB::doQueryRead($query);
			$listaAppartamenti = array();
			if($result) {
           		while($row = mysqli_fetch_row($result)){
              		array_push($listaAppartamenti,new appartamento($row[0],$row[1],$row[2],$row[3]));
				}
			}
            return $listaAppartamenti;
		}
	}
    
    //---------------------------qui---------------------
	public static function add($id,$max_persone,$dimensione,$descrizione) {
        if( isset($id) && isset($max_persone) && isset($dimensione) && isset($descrizione) ){
            $result = MyDB::doQueryWrite("INSERT INTO appartamenti (id,max_persone,dimensione,descrizione) VALUES ('$id','$max_persone','$dimensione','$descrizione')");
			if($result){ //se entra qua vuol dire che allora andra tutto bene
                $appartamentoInserito = self::appartamento($id);
                return $appartamentoInserito;
			}
		}
		return null; // se ritorna false, vuol dire che non è andata la query
	}
    
    public static function modifica($id,$max_persone,$dimensione,$descrizione) {
        if( isset($id) && isset($max_persone) && isset($dimensione) && isset($descrizione) ){
            $result = MyDB::doQueryWrite("UPDATE appartamenti SET max_persone = '$max_persone', dimensione = '$dimensione', descrizione = '$descrizione' WHERE id = '$id' ");
			if($result){ //se entra qua vuol dire che allora andra tutto bene
                $appartamentoModificato = self::appartamento($id);
                echo "modifica fatta";
                return $appartamentoModificato;
			}
		}
		echo "modifica non fatta";
        return null; // se ritorna false, vuol dire che non è andata la query
    }
    
	public static function remove($IDappartamento) {
		$result = MyDb::doQueryWrite("DELETE FROM appartamenti WHERE id = '$IDappartamento'");
        return appartamento::cancellaImmagine($IDappartamento) && $result;
	}

	public function caricaImmagine() {
		if ($_FILES['immagine']['type'] == "image/jpeg") {
			$path = "immagini";
			$tmpNome = $_FILES['immagine']['tmp_name'];
			$nome = basename($this->getIDappartamento()); 
            $result = move_uploaded_file($tmpNome, "$path/$nome.jpg");
			if($result)
				return true;
		}
		return false;
	}
    
    public function modificaImmagine() {
		if ($_FILES['immagine']['type'] == "image/jpeg") {
			$path = "immagini";
			$tmpNome = $_FILES['immagine']['tmp_name'];
			$nome = basename($this->getIDappartamento());
            $cancellazione = unlink("$path/$nome.jpg");
            /*if($cancellazione) { echo "cancellazione immagine NON riuscita"; }
            else { echo "cancellazione immagine riuscita"; }*/
            $result = move_uploaded_file($tmpNome, "$path/$nome.jpg");
            //clearstatcache();
			if($result) {return true;}
            //else return false;
		}
		return false;
	}
    
	public static function cancellaImmagine($IDappartamento) {
		//chdir("immagini/");
		$result = unlink("immagini/$IDappartamento.jpg");
		//chdir("..");
		return $result;
	}

	public function getPrezzo($data_arrivo,$data_partenza) {
		$arrivo = new DateTime("$data_arrivo");
		$partenza = new DateTime("$data_partenza");
		$costo = 0;
		for ($data=$arrivo; $data<$partenza; $data++) { 
			$costo += getGiornaliero($data,getIDappartamento());
		}
		return $costo;
	}

	public function getGiornaliero($dataDa, $dataA) {
		$query = "SELECT costo_giornaliero FROM prezzi_appartamenti WHERE appartamento=$this->IDappartamento AND $dataDa > da AND $dataA < a";
		$result = MyDB::doQueryRead($query);
		if($result){
			$row = mysqli_fetch_row($result);
			return $row['costo_giornaliero'];
		}
		return 0;
	}

	public static function cercaAppartamenti($data_inizio,$data_fine,$numPersone) {

	}
}
?>