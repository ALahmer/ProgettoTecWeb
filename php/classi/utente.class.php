<?php
require_once('MyDB.class.php');

class utente{
	private $IDUtente;
	private $Email;
	private $NomeUtente;
	private $Cognome;
	private $codiceFiscale;
	private $partitaIVA;
	private $Password;
	private $Cookie;
	private $Cf;
	private $Piva;

	private function __construct($IDUtente) {
		$query = "SELECT email, nome, cognome, password, cookie, cf, piva FROM utenti WHERE id=$IDUtente";

		$result = MyDB::doQueryRead($query);
		$row = mysqli_fetch_row($result);
		$this->IDUtente=$IDUtente;
		$this->Email=$row[0];
		$this->Nome=$row[1];
		$this->Cognome=$row[2];
		$this->Password=$row[3];
		$this->Cookie=$row[4];
		$this->Cf=$row[5];
		$this->Cookie=$row[6];
	}

	public static function login($email,$password){//return utente se l'untente esiste, null altrimenti
		if(isset($email) && isset($password)){
			$result = MyDB::doQueryRead("SELECT id FROM utenti WHERE email='$email' AND password=MD5('$password')");
			if($result && $row = mysqli_fetch_row($result)){
				//gestico l'eccazione del fail della query
				//genero il cookie
				$idus=$row[0];
				$cookie=uniqid($idus);
				//salvo il cookie nel database
				if(MyDB::doQueryWrite("UPDATE utenti SET cookie='$cookie' WHERE id = $idus")){
					return new utente($row[0]);
				}
			}
		}
		return null; //se mi torna null vuol dire che devo fare il messaggio username o password errati
	}
	//creo un nuova funzione dove lasso il cookie e mi fa il login o meno
	public static function loginCookie($cookie){
		if(isset($cookie) && ($row = mysqli_fetch_row(MyDB::doQueryRead("SELECT id FROM utenti WHERE cookie='$cookie'"))))
			return new utente($row[0]);
		return null;
	}

	public static function register($email,$nome,$cognome,$codiceFiscale,$partitaIVA,$pw){//return utente se la registrazione è riuscita, null altrimenti
		//devo controllare gli altri input

		if( isset($email) && isset($nome) && isset($cognome) && isset($pw) && !(self::userExist($email)) && !self::adminExist($email)){
			$result = MyDB::doQueryWrite("INSERT INTO utenti (email,nome,cognome,cf,piva,Password) VALUES ('$email','$nome','$cognome','$codiceFiscale','$partitaIVA',MD5('$pw'))");
			if($result){ //se entra qua vuol dire che allora andra tutto bene ma dovrei controllare che tutte le categorie esistano
				$user = self::login($email,$pw);
				return $user;
			}
		}
		return null; // se ritorna false, vuol dire che esiste già
	}
	public static function delete($idUtente){
		//if($result = MyDb::doQueryWrite("DELETE FROM categorie_utenti WHERE IDUtente = $idUtente"))
			$result = MyDb::doQueryWrite("DELETE FROM utenti WHERE id = $idUtente");
        return $result;
	}

	public function getID(){
		return $this->IDUtente;
	}
	public function getNome(){
		return $this->Nome;
	}
	public function getCognome(){
		return $this->Cognome;
	}
	public function getCookie(){
		return $this->Cookie;
	}
	public function getEmail(){
		return $this->Email;
	}
	public function getCf(){
		return $this->Cf;
	}

	public function getPiva(){
		return $this->Piva;
	}


	/* FUNZIONI DI UTILITà SECONDARIA */
	public static function checkNome($string){
		if(preg_match("/[a-zA-Z ]{3,20}/",$string))
			return true;
		return false;
	}
	public static function checkPassword($string){
		if($string=="" || strlen($string) < 6 || strlen($string) > 15)
			return false;
		return true;
	}
	public function changePasswordUser($newPw,$oldPw){ //il criptiong in md5 mi risolve tutti i problemi di sicurezza
		if(isset($newPw) && isset($oldPw)){
			$newPw=md5($newPw);
			$oldPw=md5($oldPw);
			if($oldPw == $this->Password){
				$result=MyDB::doQueryWrite("UPDATE utenti SET password='$newPw' WHERE id = $this->IDUtente");
				if($result)
					$this->Password = $newPw;
				return $result;
			}
		}
		return false;
	}
	public function changeMail($newMail){
		//deve essere una mail
		if(isset($newMail)){
			$result=MyDB::doQueryWrite("UPDATE utenti SET email='$newMail' WHERE id = $this->IDUtente"); //do query write ritorna true o false;
			if($result)
				$this->Email= $newMail;
			return $result;
		}
		return false;	
	}
	public static function userExist($email){
		$query = "SELECT id FROM utenti WHERE email='$email'";
		if(mysqli_fetch_row(MyDB::doQueryRead($query)))
			return true;
		return false;
	}

	public static function adminExist($email) {
		$query = "SELECT nome FROM amministratori WHERE email = '$email'";
		if(mysqli_fetch_row(MyDB::doQueryRead($query)))
			return true;
		return false;
	}

	public static function email_exist($email) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
		//elseif (!checkdnsrr(array_pop(explode('@',$email)),'MX')) return false;
		else return true;
    }
	public static function getAllUser(){
		$ris=array();
		$queryRes=MyDB::doQueryRead("SELECT id FROM utenti");
		while($row = mysqli_fetch_row($queryRes))
			array_push($ris,new User($row[0]));
		return $ris;
	}
	public static function getUserByID($idUtente){
		if(is_numeric($idUtente))
			return new utente($idUtente);
		else
			return null;
	}

	public static function checkCodFiscale($codiceFiscale) {
		if(preg_match("/[a-zA-Z0-9]{1,20}/",$codiceFiscale))
			return true;
		return false;
	}
	public static function checkPiva($codiceFiscale) {
		if(preg_match("/[a-zA-Z0-9]{1,50}/",$codiceFiscale))
			return true;
		return false;
	}
}
?>
