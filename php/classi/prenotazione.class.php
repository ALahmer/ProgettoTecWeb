<?php
require_once('MyDB.class.php');
require_once('servizio.class.php');

class prenotazione {
	private $IDprenotazione;
	private $Utente;
	private $Data_partenza;
	private $Data_arrivo;
	private $Stato;
	private $NumPersone;
	private $Appartamento;

	public static function prenotazione($id) {
		$query = "SELECT id, utente, data_arrivo, data_partenza, stato, numPersone , appartamento FROM prenotazioni WHERE id=$id";
		$result = MyDB::doQueryRead($query);
		if($row = mysqli_fetch_row($result))
			return new prenotazione($row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6]);
		return null;
	}

	private function __construct($id,$utente,$data_partenza,$data_arrivo,$stato,$numPersone,$appartamento) {
		$this->IDprenotazione=$id;
		$this->Utente=$utente;
		$this->Data_partenza=$data_partenza;
		$this->Data_arrivo=$data_arrivo;
		$this->Stato=$stato;
		$this->NumPersone=$numPersone;
		$this->Appartamento=$appartamento;
	}

	public function getIDprenotazione() {return $this->IDprenotazione;}
	public function getUtente() {return $this->Utente;}
	public function getData_partenza() {return $this->Data_partenza;}
	public function getData_arrivo() {return $this->Data_arrivo;}
	public function getStato() {return $this->Stato;}
	public function getNumPersone() {return $this->NumPersone;}
	public function getAppartamento() {return $this->Appartamento;}

	public function getServizi() {
		$query = "SELECT id_servizio FROM servizi_prenotazioni WHERE id_prenotazione = $this->IDprenotazione";
		$result = MyDB::doQueryRead($query);
		$servizi = array();
		while ($row = mysqli_fetch_row($result))
			array_push($servizi, servizio::servizio($row[0]));
		return $servizi;
	}

	public static function getPrenotazioni($idUtente) {
		$ris=array();
		$query = "SELECT id, utente, data_arrivo, data_partenza, stato, numPersone, appartamento FROM prenotazioni";
		if($idUtente) {
			$query = $query . " WHERE utente = $idUtente";
		}
		$queryRes=MyDB::doQueryRead($query);
		while($queryRes && $row = mysqli_fetch_row($queryRes))
			array_push($ris,new prenotazione($row[0],$row[1],self::formattaDataIta($row[2]),self::formattaDataIta($row[3]),$row[4],$row[5],$row[6]));
		return $ris;
	}

	public static function getPrenotazioniData($da,$a) {
		$ris=array();
		$query = "SELECT id, utente, data_arrivo, data_partenza, stato, numPersone, appartamento FROM prenotazioni WHERE data_arrivo >= '$da' AND data_arrivo <= '$a'";

		$queryRes=MyDB::doQueryRead($query);

		while($queryRes && $row = mysqli_fetch_row($queryRes))
			array_push($ris,new prenotazione($row[0],$row[1],self::formattaDataIta($row[2]),self::formattaDataIta($row[3]),$row[4],$row[5],$row[6]));
		return $ris;
	}

	public static function prenota($utente,$appartamento,$data_partenza,$data_arrivo,$numPersone) {
		if( isset($utente) && isset($data_partenza) && isset($data_arrivo) ){
			$stato='sospeso';
			$result = MyDB::doQueryWriteID("INSERT INTO prenotazioni (utente,appartamento,data_partenza,data_arrivo,stato,numPersone) VALUES ($utente,'$appartamento','$data_partenza','$data_arrivo', '$stato', $numPersone)");
			if($result){ //se entra qua vuol dire che allora andra tutto bene ma dovrei controllare che tutte le categorie esistano
				return self::prenotazione($result);
			}
		}
		return null; // se ritorna false, vuol dire che esiste già
	}

	public static function checkData($string) {
		$date = explode("/", $string);
		if(sizeof($date) >= 3) {
            return checkdate($date[1], $date[0], $date[2]);
		}
		else
			return false;	
	}

	public static function checkDatas($stringda,$stringa){
		$da = new DateTime(self::formattaData($stringda));
		$a = new DateTime(self::formattaData($stringa));
		return (($a>$da) && ($da > new DateTime('today')));
	}

	public static function formattaData($string) {
		$date = explode("/", $string);
		$result = $date[2] . "-" . $date[1] . "-". $date[0];
		return $result;
	}
	public static function formattaDataIta($string) {
		$date = explode("-", $string);
		$result = $date[2] . "/" . $date[1] . "/". $date[0];
		return $result;
	}


	public function addServizi($servizi) {
		$risultato = true;
		foreach ($servizi as $value) {
			$pren = $this->getIDprenotazione();
			$val = $value->getIDservizio();
			$query = "INSERT INTO servizi_prenotazioni (id_prenotazione,id_servizio) VALUES ('$pren',$val)";
			$risultato = $risultato && MyDB::doQueryWrite($query);
		}
		return $risultato;
	}

	public static function checkDateLibere($data_inizio,$data_fine,$appartamento) {
		//false il periodo è ok, true il periodo è occupato
		$query = "SELECT * FROM prenotazioni 
		WHERE (('$data_inizio' BETWEEN data_arrivo AND data_partenza) 
		OR (data_arrivo BETWEEN '$data_inizio' AND '$data_fine')) 
		AND appartamento = '$appartamento'";
		$result = MyDB::doQueryRead($query);
		return (mysqli_fetch_row($result));
	}

	public function cancella(){
		$query="DELETE FROM prenotazioni WHERE id=".$this->IDprenotazione;
		return MyDB::doQueryWrite($query);
	}

	public function setStato($stato) {
		$query = "UPDATE prenotazioni SET stato = '$stato' WHERE id = " . $this->getIDprenotazione();
		return (MyDB::doQueryWrite($query));
	}

	public function giorniPrenotati() {
		//numero giotni e prezzo
		$arrivo = new DateTime($this->getData_arrivo());
		$partenza = new DateTime($this->getData_partenza());
		$giorni = date_diff($arrivo, $partenza);
		return $giorni;
	}
	public function getGiornaliero($data) {
		$appartamento = $this->getAppartamento();
		$query = "SELECT costo_giornaliero FROM prezzi_appartamenti WHERE ('$data' BETWEEN da AND a) AND appartamento = '$appartamento'";
		$result = MyDB::doQueryRead($query);

		if($result && $row=mysqli_fetch_row($result))
			return $row[0];
		
		return 0;
	}
	public function getCosto() {
		$costo = 0;
		try {
			$arrivo = new DateTime($this->getData_arrivo());
		} catch (Exception $e) {}
		try {
			$partenza = new DateTime($this->getData_partenza());
		} catch (Exception $e) {}
		//echo $partenza;
		for($data = $partenza; $data < $arrivo; $data->add(DateInterval::createFromDateString('+1 days')))
			$costo += self::getGiornaliero($data->format('Y-m-d'));
		
		return $costo;
	}
}

?>