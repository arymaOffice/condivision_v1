<?php
/*
	classe per la gestion dell'oggetto leadgen  

		1° richiama readObj di abstractFb

		2° richiesta informazioni lead

		3° inserimento nel db

*/


include_once('Mysql&curl/abstractParent.php');
class leadgen extends abstractParent{

	private static $languageForm;
	private static $formType;
	private static $marchio;
	private static $tabella = "fl_potentials";
	var $connessioneChild;

	function __construct(){
		parent::__construct();
		$this->connessioneChild = parent::getConnection();//mi prendo la connessione dal parent
	}



	public function leadgenFunction($arrayFromGeneral){

		if(array_key_exists('leadgen_id', $arrayFromGeneral) && array_key_exists('form_id', $arrayFromGeneral)){
			self::registerForm($arrayFromGeneral['form_id']);
			$form_id = $arrayFromGeneral['form_id'];
			$created_time = $arrayFromGeneral['created_time'];
			$curl = parent::curlFacebook($arrayFromGeneral['leadgen_id']);

			if($curl){
				//recupero i singoli dati e li elimino dall'array, se ci dovvessero essere campi nuovi li metto nelle note
				$note = '';
				$email = (isset($curl['email']))?$curl['email']:'';
				unset($curl['email']);
				$full_name = (isset($curl['full_name']))?$curl['full_name']:'';
				unset($curl['full_name']);
				$phone_number = (isset($curl['phone_number']))?$curl['phone_number']:'';
				unset($curl['phone_number']);
				$country = (isset($curl['country']))?$curl['country']:'';
				unset($curl['country']);
				$dateTime = date('Y-m-d H:i:s', $created_time);
				$date = date('Y-m-d', $created_time);

				if(count($curl) >= 1){
					$note = implode(',', $curl);
				}

				self::insertLead($arrayFromGeneral['page_id'],
					$form_id,
					$full_name,
					$email,
					$phone_number,
					$country,
					$note,
					$date,
					$dateTime,
					$marchio 
					);
			}else{
				return false;
			}			

		}else{
			return false;
		}
	}

	private function registerForm($form_id){
		$curl = parent::curlFacebook($form_id);
		if($curl){
			$form_name = $curl['name'];
			//questa query nel caso il form fosse già esistente torna 1 come risultato a differenza di insert into normale che tornerebbe un errore per una key duplicata
			$insert = "INSERT IGNORE INTO `fl_facebook_forms` SET `marchio` = 1 , `form_id` = '".$form_id."', `description` = '".$form_name."', `form_language` = 0 ,`form_type` = 1,`data_creazione` = NOW();";
			self::setFormCharacteristics($form_id);
			return $doQuery = $this->connessioneChild->query($insert);
		}
		return true;
	}

	private function setFormCharacteristics($form_id){

		$selectCharac = "SELECT `form_language`,`form_type`,marchio FROM `fl_facebook_forms` WHERE form_id = '$form_id'";
		$query =  $this->connessioneChild->query($selectCharac);
		if($query->num_rows == 1 ){
			$result = $query->fetch_assoc();
			self::$languageForm = $result['form_language'];
			self::$formType = $result['form_type'];
			self::$marchio = $result['marchio'];
		}

	}

	private function insertLead($pagina,$formID,$nome,$email,$telefono,$country,$note,$date,$dateTime){

 $insertLead = "
		INSERT INTO ".self::$tabella." ( `in_use`, `status_potential`, `referer`, `sent_mail`, `marchio`, `type`, `source_potential`, `data_aggiornamento`, `is_customer`, `paese`,`country`, `nome`, `cognome`, `email`, `telefono`, `mansione`, `experience_level`, `messaggio`, `note`, `operatore`, `proprietario`, `data_creazione`, `ip`,`preferedlanguage`) 
		SELECT '0', '0','$formID', '0', '".self::$marchio."', '".self::$formType."', 'FACEBOOK', '$dateTime', '0', '0','$country', '$nome', '', '$email', '$telefono', '', '1', 'PAGE id : $pagina .', '$note', '0', '0', '$date', '1','".self::$languageForm."' FROM ".self::$tabella." 
		WHERE NOT EXISTS (SELECT email,telefono FROM ".self::$tabella." WHERE  `email` = '$email' OR `telefono` = '$telefono') LIMIT 1;
		";
		
		//mail('support@aryma.it','FB Query',$insertLead);


		$query = $this->connessioneChild->query($insertLead);
		
		if($this->connessioneChild->insert_id != 0){
			mail('potentials@escapenetwork.com','FB Lead NEW','id '.$this->connessioneChild->insert_id."\r\n"."email: ".$email."\r\n"."phone: ".$telefono);
			//mail('michelefazio@aryma.it','FB NEW '.$nome,'id '.$this->connessioneChild->insert_id."\r\n"."email: ".$email."\r\n"."phone: ".$telefono);
			return true;


		} else {

			//aggiorna il potential già esistente			
			
			$status_potential = array('New','Not Show','In Meeting','Not interested','Discarted','Hazardous','Archived','In contract','','',''); //status del potential
			
			
			$select ="SELECT id,data_creazione,status_potential,nome,email,telefono FROM ".self::$tabella." WHERE `email` = '$email' OR `telefono` = '$telefono' ORDER BY id DESC LIMIT 1  ";
			$execSelect = $this->connessioneChild->query($select);
			if($execSelect->num_rows > 0){
					$result = $execSelect->fetch_assoc();				
	
					$data = date("d-m-Y", strtotime($result['data_creazione']));
					$setAsNew = ($result['status_potential'] > 0 && $result['status_potential'] != 2) ? 'data_creazione = NOW() , status_potential = 0, ' : '';

					$updateStatus = $setAsNew." type= '".self::$formType."', note = CONCAT(note,' ','Already applied on  ".$data." with res: ".$status_potential[$result['status_potential']].") ',note)";
					$query = "UPDATE ".self::$tabella." SET country = '$country', nome = '$nome', source_potential = 'FACEBOOK' , referer = CONCAT(referer, ' , $formID'), email = '$email', telefono = '$telefono', $updateStatus WHERE id = ".$result['id'].' LIMIT 1';
					$this->connessioneChild->query($query);
					mail('potentials@escapenetwork.com','FB Lead Update '.$nome,'id '.$result['id']."\r\n"."email: ".$email."\r\n"."phone: ".$telefono);
					//mail('michelefazio@aryma.it','FB Lead Duplicate '.$status_potential[$result['status_potential']],'id '.$result['id']."\r\n"."email: ".$email."\r\n"."phone: ".$telefono.mysql_error());
				
				} else { 	
					mail('michelefazio@aryma.it','FB Lead Not Found '.$nome,$select);
			}


			return false;
		}//fine else
		
	}//fine  funzione


}//fine classe
?>
