<?php 
	/**
	 *											database:
	 *
	 * classe database che stabilisce una connessione con il database e restituisce
	 * l'oggetto connessione.
	 * Si deve nexessariamente utilizzare l'unico metodo public: getConnectionWhithParam
	 * passando:
	 *	- $host: nome host
	 *	- $user: username dell'utente nel db
	 *	- $password: la pawword del'user
	 *	- $database: il nome del db
	 */

	class database {
		
<<<<<<< HEAD
		private static $HOST = "";				//nome host
		private static $USER = "";								//nome utente
		private static $PASSWORD = "";				//password utente
		private static $DATABASE = "";							//nome del database
=======
		private static $HOST = "vps6876.mondoservercloud.it";				//nome host
		private static $USER = "appescap_loc";								//nome utente
		private static $PASSWORD = "FiringDearlySevensMuch67";				//password utente
		private static $DATABASE = "appescap_etl";							//nome del database
>>>>>>> 9b468b677e35c571daaeddcd59fe983b70651627
		static protected $connessione;
		

		public static function getConnection(){
				
			if(!self::$connessione){	
				self::$connessione = new mysqli(self::$HOST,self::$USER,self::$PASSWORD,self::$DATABASE);
				if(self::$connessione->connect_error){
					die ("Attenzione errore di connessione " . self::$connessione->connect_error);
				}else{
					//echo "Connessione al Database avvenuta con successo<br>";
				}
				return self::$connessione;
			}else{
				return self::$connessione;
			}	
		}
		private function __construct() {
		}
	}
	
?>
