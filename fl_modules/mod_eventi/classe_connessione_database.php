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
		
		private static $HOST;								//nome host
		private static $USER;								//nome utente
		private static $PASSWORD;							//password utente
		private static $DATABASE;							//nome del database
		static protected $connessione;
		
		public static function getConnectionWhithParam($host, $user, $password,$database){
			self::$HOST = $host;								
			self::$USER = $user;								
			self::$PASSWORD = $password;						
			self::$DATABASE = $database;

			return 	self::getConnection();
		}

		private static function getConnection(){
				
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
