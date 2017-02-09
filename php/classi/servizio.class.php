<?php
require_once('MyDB.class.php');

class servizio {
	private $IDservizio;
	private $Nome;
	private $Costo;
	private $Unita;

	public static function servizio($id) {
		$query = "SELECT id, nome, costo, unita FROM servizi WHERE id=$id";
		$result = MyDB::doQueryRead($query);
		$row = mysqli_fetch_row($result);
		return new servizio($id,$row[1],$row[2],$row[3]);
	}

	private function __construct($id,$nome,$costo,$unita) {
		$this->IDservizio=$id;
		$this->Nome=$nome;
		$this->Costo=$costo;
		$this->Unita=$unita;
	}

	public function getIDservizio() {return $this->IDservizio;}
	public function getNome() {return $this->Nome;}
	public function getCosto() {return $this->Costo;}
	public function getUnita() {return $this->Unita;}

	public static function addServizio($nome,$costo,$unita) {
		$query = "INSERT INTO servizi (nome,costo,unita) VALUES ('$nome','$costo','$unita')";
		if(MyDB::doQueryWrite($query))
			return true;
		return false;
	}

	public static function deleteServizio($id) {
		$query = "DELETE FROM servizi WHERE id = '$id'";
		if(MyDB::doQueryWrite($query))
			return true;
		return false;
	}

	public static function checkNome($string) {
		if(preg_match("/^[a-zA-Z0-9\s]{3,30}$/",$string))
			return true;
		return false;
	}
	public static function checkCosto($string) {
		if(preg_match("/^[0-9]{1,5}$/", $string))
			return true;
		return false;
	}

	public static function getListaServizi() {
		$query = "SELECT id,nome,costo,unita FROM servizi";
		$result = MyDB::doQueryRead($query);
		$listaServizi = array();
		while($result && $row = mysqli_fetch_row($result)) {
			array_push($listaServizi,new servizio($row[0],$row[1],$row[2],$row[3]));
		}
		return $listaServizi;
	}

}
?>
