<?php
/**
* RequestRealTime torna i dati in quel preciso momento
* RequestStorico torna i dati dalla data inizio a tutti i dati dopo con una cadenza di 15 min 
*/
class macNilGps_soap 
{
	var $user;
	var $password;
	var $endpoint;
	var $idRichiesta;
	private static $mySoapClient;

	public function __construct(){ 
		$this->idRichiesta = time(); 
		$this->user = GTALARM_USER; 
		$this->password = sha1(GTALARM_PWD); 
		$this->endpoint = 'http://ws.remoteangel.net/CentraleOperativa.asmx?WSDL'; 
		self::$mySoapClient = new SoapClient($this->endpoint,array('soap_version'   => SOAP_1_2));
		if(is_soap_fault(self::$mySoapClient)) {    
			trigger_error("SOAP Errore: (Codice Errore: {self::$mySoapClient->faultcode}, Errore Stringa: {$client->faultstring})", E_USER_ERROR); }
		} 

		public function RequestRealTime($IdAlfanumerico){

			$IdAlfanumerico = filter_var($IdAlfanumerico,FILTER_SANITIZE_STRING);

			$request = self::templateRealTime($IdAlfanumerico);

			$result = self::$mySoapClient->__doRequest($request,$this->endpoint,'',SOAP_1_2);


			$xml = simplexml_load_string($result);
			$xml->registerXPathNamespace('mac', 'http://macnil.it/'); //registro il namespace
			$var = $xml->xpath('//mac:RequestRealTimeResult');	//recupero la risposta		

			if($var[0]->IdRichiesta == $this->idRichiesta ){

				if($var[0]->Esito == 0){//controllo esito

					$DatiGPS = $var[0]->DatiGPS->DatoGPS_RealTime;

					$posizione = $DatiGPS->Latitudine.','.$DatiGPS->Longitudine;
					$km_percorsi = (string) $DatiGPS->Info01;

					return array('esito'=>0 , 'posizione' => $posizione ,'chilometri_percorsi' => $km_percorsi );

				}else{
					return array('esito'=>$var[0]->Esito );
				}
			}
		}


	//crea template RequestRealTime
		private function templateRealTime($carId){
			$template = '
			<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:mac="http://macnil.it/">
			<soap:Header/>
			<soap:Body>
			<mac:RequestRealTime>
			<!--Optional:-->
			<mac:User>'.$this->user.'</mac:User>
			<!--Optional:-->
			<mac:Password>'.$this->password.'</mac:Password>
			<mac:IdRichiesta>'.$this->idRichiesta.'</mac:IdRichiesta>
			<!--Optional:-->
			<mac:IdAlfanumerico>'.$carId.'</mac:IdAlfanumerico>
			</mac:RequestRealTime>
			</soap:Body>
			</soap:Envelope>';
			return $template;
		}

		public function RequestStorico($IdAlfanumerico,$data_inizio){

			$IdAlfanumerico = filter_var($IdAlfanumerico,FILTER_SANITIZE_STRING);

		//data inizio 2002-09-24T06:00:00

			$request = self::templateRequestStorico($IdAlfanumerico,$data_inizio);

			$result = self::$mySoapClient->__doRequest($request,$this->endpoint,'',SOAP_1_2);

			$xml = new SimpleXMLElement($result);

	//risposta return array(IdRichiesta,Oggetto Dato GPS,Esito);
		}
		//crea template RequestStorico
		private function templateRequestStorico($carId,$data){
			$template = '
			<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:mac="http://macnil.it/">
			<soap:Header/>
			<soap:Body>
			<mac:RequestStorico>
			<!--Optional:-->
			<mac:User>'.$this->user.'</mac:User>
			<!--Optional:-->
			<mac:Password>'.$this->password.'</mac:Password>
			<mac:IdRichiesta>'.$this->idRichiesta.'</mac:IdRichiesta>
			<!--Optional:-->
			<mac:IdAlfanumerico>'.$carId.'</mac:IdAlfanumerico>
			<mac:DataInizio>'.$data.'</mac:DataInizio>
			</mac:RequestStorico>
			</soap:Body>
			</soap:Envelope>';
			return $template;
		}

	}

/** codici errore
1 User e/o Password non valida
2 IdAlfanumerico non valido
3 Formato DataInizio non valido
4 Servizio sospeso
5 Cliente non attivo
6 Servizio Scaduto o cessato
100 Errore generico
*/


/*-----------------ESEMPI DI UTILIZZO --------------------------------------------------*/
$macNilGps_soapClient = new macNilGps_soap;

?>
