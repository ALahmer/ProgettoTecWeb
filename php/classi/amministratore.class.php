<?php
require_once('MyDB.class.php');

class amministratore {
	private $Nome;
	private $Email;
	private $Password;
	private $Cookie;

	public static function amministratore($nome) {
		$query = "SELECT nome, email, password, cookie FROM amministratori WHERE nome= '$nome'";
		$result = MyDB::doQueryRead($query);
		$row = mysqli_fetch_row($result);
		return new amministratore($row[0],$row[1],$row[2],$row[3]);
	}

	private function __construct($nome,$email,$password,$cookie) {
		$this->Nome=$nome;
		$this->Email=$email;
		$this->Password=$password;
		$this->Cookie=$cookie;
	}

	public function getNome() {return $this->Nome;}
	public function getEmail() {return $this->Email;}
	public function getCookie() {return $this->Cookie;}

	public static function login($email,$password){//return utente se l'untente esiste, null altrimenti
		if(isset($email) && isset($password)){
			if($row = mysqli_fetch_row(MyDB::doQueryRead("SELECT nome FROM amministratori WHERE email='$email' AND password=MD5('$password')"))){
				//gestico l'eccazione del fail della query
				//genero il cookie
				$nome=$row[0];
				$cookie=uniqid();
				//salvo il cookie nel database
				if(MyDB::doQueryWrite("UPDATE amministratori SET cookie='$cookie' WHERE nome = '$nome'")){
					return self::amministratore($nome);
				}
			}
		}
		return null; //se mi torna null vuol dire che devo fare il messaggio username o password errati
	}
}

?>