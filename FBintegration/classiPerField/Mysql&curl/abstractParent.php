<?php
/**
* permette alle classi più piccole di avere metodi generici
*
*/

abstract class abstractParent{

	private static $endpoint = "https://graph.facebook.com/v2.10/";
	var $connessione;
	
	function __construct(){
		require_once 'classe_connessione_database.php';
		$this->connessione = database::getConnection();
	}



	public function curlFacebook($idElemento){
		//riceve un id in entrata al quale corrispondono dei dati lo associa all'access token ed invi ala richiesta

		$accessToken = self::getAccessToken();

		if($accessToken){
			$endpointCompleto = self::$endpoint.$idElemento.'/?access_token='.$accessToken;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$endpointCompleto);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
			$data = curl_exec($ch);
			$result= json_decode($data,true);
			curl_close($ch);
			if(!isset($result['error'])){
				$valori_puliti = array();
				if(isset($result['field_data'])){
					foreach ($result['field_data'] as $key => $value) {
					$valori_puliti[$value['name']] = $value['values'][0];
					}
				return $valori_puliti;
				}else{
					return $result;
				}
				

			}else{//se il recupero dati  è andato male

				return false;
			}			
		}else{
			return false;
		}


	}



	private function getAccessToken(){
		//recupera ultimo fbtoken inserito
	$query = "SELECT fb_token FROM fl_fb_access_token WHERE data_creazione BETWEEN NOW()  - INTERVAL 30 DAY AND NOW()  ORDER BY id DESC LIMIT 0,1";
	$query = $this->connessione->query($query);
		if($query->num_rows == 1){ //controllo se ho un token da tornare
			$token = $query->fetch_assoc();
			$token = $token['fb_token'];
			return $token;
		}else{
			mail('asaracino@aryma.it','no token per app escape', 'forniscimi un token facebook');
			mail('michelefazio@aryma.it','no token per app escape', 'forniscimi un token facebook');
			return false;
		}
	}

	public function getConnection(){
		return $this->connessione;
	}
}//fine classe



?>
