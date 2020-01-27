<?php
defined ( "NODIRECT" ) or die ();
/*
 * Clase para la creación de conexiones a la base de datos
 */

class jboConection {
    
    private $databaseName;
    public $db;

function __construct() {

		$this->databaseName = jboConf::$dbName;
                
}

public function conect(){
 
    try{

    $this->db = new PDO('sqlite:'. $this->databaseName);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->db->setAttribute(PDO::ATTR_TIMEOUT, jboConf::$busyTimeout);

    /*$this->db = new PDO('mysql:dbname=impresor;host=127.0.0.1',"admin","password");
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->db->setAttribute(PDO::ATTR_TIMEOUT, jboConf::$busyTimeout);
    */

     }
  catch (PDOException $e) {
    print $e->getMessage();
  }
 
}
public function disconect(){
    $this->db = null;
}

    /*private $username;
	private $password;
	private $hostname;
	private $databaseName;
	public $conector;
	
	function __construct() {
		$this->databaseName = jboConf::$dbname;
		$this->username = jboConf::$dbuser;
		$this->password = jboConf::$dbpassword;
		$this->hostname = jboConf::$dbhost;
		$this->conector = new mysqli ( $this->hostname, $this->username, $this->password, $this->databaseName );
		$this->conector->set_charset ( "utf8" ); // IMPORTANTE PARA EVITAR MALAS TRADUCCIONES DE TABLAS DE CARACTERES
		if ($this->conector->connect_errno > 0) {
			die ( 'Unable to connect to database [' . $this->conector->connect_error . ']' );
		}
	}
	
	public function disconect() {
		mysqli_close ( $this->conector );
	}*/
}