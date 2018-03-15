<?php

//Version Beta 1.0 
//Powered By Aryma.it
//1/6/2016

class restServices{

    protected $endpoint = 'http://dev.bluemotive.it/fl_api/?'; // Endpoint
	
	var $demo = false;  // Se abilitare o meno il demo 
	var $body = '';
	var $accessToken;
	var $user;
	var $password;
	var $client_id;
	var $secret;


	public function __construct($client_id, $secret) {
			$this->client_id = $client_id;
			$this->secret = $secret;
			if(0) { $this->loadToken(); } else { $this->retrieveAccessToken(); }
	}

	public function insert_lead() {
			$this->body = http_build_query($_POST);
			$insert_lead = 'insert_lead&token='.$this->accessToken;
			$data = $this->get_data_query($insert_lead);
			return $data; 
	}

	public function get_attivita() {
			$this->body = http_build_query($_POST);
			$insert_lead = 'get_attivita&token='.$this->accessToken;
			$data = $this->get_data_query($insert_lead);
			return $data; 
	}

	public function get_leads() {
			$this->body = http_build_query($_POST);
			$insert_lead = 'get_leads&token='.$this->accessToken;
			$data = $this->get_data_query($insert_lead);
			return $data; 
	}


	private function retrieveAccessToken() {
			
			$now = time();
			$sha = sha1($now.$this->secret.$this->client_id);
			$retreiveAccessToken = 'app_login&time='.$now.'&request_id='.$sha.'&client_id='.$this->client_id;
			$data = $this->get_data_query($retreiveAccessToken);
			if($data->esito == 1) { $this->accessToken = $data->token; $this->refreshToken = date('Y-m-d', strtotime("+1 days")); $this->saveToken(); } else { die($data->info_txt); }
	}
		
	private function get_data_query($query){
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->endpoint.$query);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
		
		$headers = array();
		$headers["Content-length"] = strlen($this->body);
		$headers["Accept"] = "application/json";
		//$headers["Authorization"] = "Basic ".base64_encode($this->client_id.":".$this->secret);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
		
		if($data = curl_exec($ch)) { $data = $data; } else { $data = array('esito'=>0,'info_txt'=>curl_error($ch)); } 
		curl_close($ch);
		$data = json_decode($data);
		return $data; //esito e dati in ritorno dalla richiesta
	}
	
	
	private function loadToken() {
				if (isset($_COOKIE["access_token"])) $this->accessToken = $_COOKIE["access_token"];
				if (isset($_COOKIE["refresh_token"])) $this->refreshToken = $_COOKIE["refresh_token"];
	}
			
	private function saveToken() {
				setcookie("access_token", $this->accessToken, time()+60*60*24*30);
				setcookie("refresh_token", $this->refreshToken, time()+60*60*24*30);
	}


} // Fine 