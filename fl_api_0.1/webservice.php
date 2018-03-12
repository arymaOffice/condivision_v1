<?php


class webservice{


	var $demo = false; // Se abilitare o meno il demo 
	var $datatype = "JSON";	
	var $contenuto = array();
	var $token;
	var $user;
	var $password;
	var $obbligatorio = array('nome','email');
	var $numerici = array('telefono','cellulare');
	var $date = array('data_test_drive','periodo_cambio_vettura');
	var $secret = 're56fdsfw285hfw5k3923k2ASYLWJ8tr3';
	var $push_type = 'post';
	var $deviceToken = 0;
	


function app_start(){
	session_cache_limiter( 'private_no_expire' );
	session_cache_expire(time()+5259200); 
    session_start();		
	
	if(session_id() != $this->token && $this->token != 'app') {	
	    $this->contenuto = '';
		$this->contenuto['esito'] = 0;
		$this->contenuto['info_txt'] = 'Not valid session. Please restart app';
		mysql_close(CONNECT);		
		echo json_encode($this->contenuto);
		exit;
	}

}

function cnv_makedata(){
	
	mysql_close(CONNECT);		
	
	if($this->datatype == 'JSON') {
	echo json_encode($this->contenuto);
	exit;
	}
	
    if($this->datatype == 'OBJECT') {
	return $this->contenuto;
	}
	
	if($this->datatype == 'XML') {
	echo json_encode($this->contenuto);
	exit;
	}
	
}

function insert_lead(){


$this->contenuto['esito'] = 0;

if(!isset($_POST['privacy']) || $_POST['privacy'] == 0){
$this->contenuto['class'] = 'red';
$this->contenuto['esito'] = "Per continuare è necessario autorizzare al trattamento dei propri dati!";
$this->cnv_makedata();
}

$mail_message = '';
// Campi Obbligatori
foreach($_POST as $chiave => $valore){
	
		if(in_array($chiave,$this->obbligatorio)) {
		if($valore == ""){
		$chiave = ucfirst(check($chiave));
		$this->contenuto['class'] = 'red';
		$this->contenuto['esito'] = "Compila il campo $chiave";
		$this->cnv_makedata();

		}}
		
		if(in_array($chiave,$this->numerici)) {
		if(!is_numeric(trim($valore))){
		$chiave = ucfirst(check($chiave));
		$this->contenuto['class'] = 'red';
		$this->contenuto['esito'] = "Inserisci solo numeri in $chiave";
		$this->cnv_makedata();
		}}
		

		$$chiave = $this->check($valore);
		if(in_array($chiave,$this->date)) $$chiave = $this->convert_data($this->check($valore),1);
		$mail_message .= '<p>'.$chiave.' = '.$$chiave.'</p>';
}


if(!is_numeric(trim($telefono)) || strlen(@$telefono) < 9 ){
$this->contenuto['class'] = 'red';
$this->contenuto['esito'] = "Inserisci un numero di telefono corretto, almeno 9 cifre!";
$this->cnv_makedata();
}



$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
if (isset($email) && !preg_match($regex, $email)){
$this->contenuto['class'] = 'red';
$this->contenuto['esito'] = "Inserisci email valido";
$this->cnv_makedata();
}


$campi = array('tipo_appuntamento','appuntamento_tap','meeting_time','meeting_date','nome', 'cognome','email', 'messaggio','telefono','citta','ragione_sociale', 'partita_iva','campagna','attivita','oggetto','messaggio','note','interessato_a','tipo_interesse','anno_di_interesse','numero_persone','preferenze_menu');
foreach($campi as $chiave => $valore){
	if(!isset($$valore)) $$valore = '';
}
if(!isset($_POST['campagna'])) $campagna = 8;
if(!isset($_POST['attivita'])) $attivita = 8;


$sql = "INSERT INTO `fl_leads_hrc` (`id`, `in_use`, `status_potential`, `sent_mail`, `marchio`, `data_visita`, `campagna_id`, `source_potential`, `data_aggiornamento`, `is_customer`, `priorita_contatto`, `paese`, `company`, `job_title`, `nome`, `cognome`, `email`, `telefono`, `website`, `indirizzo`, `provincia`, `citta`, `cap`, `industry`, `ragione_sociale`, `partita_iva`, `messaggio`, `tipo_interesse`, `interessato_a`, `anno_di_interesse`, `mesi_di_interesse`, `periodo_interesse`, `numero_persone`,  `note`, `operatore`, `proprietario`, `venditore`, `data_creazione`, `data_assegnazione`, `data_scadenza`, `data_scadenza_venditore`) 
VALUES (NULL, '0', '0', '', '0', '', '$campagna', '$attivita', NOW(), '0', '0', '100000100', '', '', '$nome', '$cognome', '$email','$telefono', '', '', '', '$citta', '', '', '$ragione_sociale', '$partita_iva', '$oggetto $messaggio', '$tipo_interesse', '$interessato_a',  '$anno_di_interesse', '', '', '$numero_persone', '$note', '1', '1', '', NOW(), '', '', '');";

$sql2 = "SELECT id,telefono FROM `fl_leads_hrc` WHERE telefono = '$telefono' LIMIT 1;";
$duplicato = mysql_query($sql2, CONNECT);

if(mysql_affected_rows() < 1) {

$esito = mysql_query($sql, CONNECT);

	if( $esito == true ){
	$lead_id = mysql_insert_id();
	$mail_subject = "Nuovo Lead Inserito";
	$this->contenuto['class'] = 'green';
	$this->contenuto['url'] = '#';
	$this->contenuto['esito'] = "Grazie l'interesse Dimostrato!";
	$mail_body2 = str_replace("[*CORPO*]",$mail_message,mail_template); 
	} else {
	$this->contenuto['class'] = 'red';
	$this->contenuto['esito'] = "Error 1101: Problema inserimento lead.".mysql_error();
	$mail_message = "ERRORE DATABASE: ".mysql_error().'<br>'.$mail_message;
	$mail_body3 = str_replace("[*CORPO*]",$mail_message,mail_template); 
	smail( mail_admin, "Problema inserimento nuovo lead su: ".ROOT,$mail_body3);
	}	

} else { $duplicato = mysql_fetch_assoc($duplicato); $lead_id = $duplicato['id']; $this->contenuto['esito'] = 'Grazie '.$nome.' (inserito in precedenza) '; } // Recupero id duplicato

$this->contenuto['lead_id'] = $lead_id;


if(isset($_REQUEST['appuntamento_tap'])) { 

	$meeting_date = $this->determina_data($meeting_date);


	$time = strtotime($meeting_time);
	$end_meeting = date("H:i", strtotime('+30 minutes', $time));


	$queryAppuntamento = "INSERT INTO `fl_appuntamenti` ( `tipologia_appuntamento`, `dove`, `meeting_location`, `start_meeting`, `end_meeting`, `all_day`, `potential_rel`, `nominativo`, `telefono`, `email`, `note`, `issue`, `callcenter`, `operatore`, `proprietario`, `data_creazione`, `data_aggiornamento`) 
	VALUES ( '$tipo_appuntamento', '', '31', '".$meeting_date." ".$meeting_time.":00', '".$meeting_date." ".$end_meeting.":00', '0', '$lead_id', '$nome $cognome', '$telefono', '$email', 'Appuntamento inserito da App', '0', '0', '1', '0', NOW(),NOW() );";
	$esito = mysql_query($queryAppuntamento, CONNECT);

	if( $esito == true ){
	$lead_id = mysql_insert_id();
	$mail_subject = "Nuovo Lead Inserito";
	$this->contenuto['class'] = 'green';
	$this->contenuto['url'] = '#';
	$this->contenuto['esito'] .= ", appuntamento creato!";
	$mail_body2 = str_replace("[*CORPO*]",$mail_message,mail_template); 
	} else {
	$this->contenuto['class'] = 'red';
	$this->contenuto['esito'] = "Error 1101: Problema inserimento lead.".mysql_error();
	$mail_message = "ERRORE DATABASE: ".mysql_error().'<br>'.$mail_message;
	$mail_body3 = str_replace("[*CORPO*]",$mail_message,mail_template); 
	smail( mail_admin, "Problema inserimento nuovo appuntamento su: ".ROOT,$mail_body3);
	}	


}


$this->cnv_makedata();

} // Lead in


public function determina_data($data){
	$str_array = preg_split('/[\/\-]/', $data);
	return (strlen($str_array[0]) == 4) ? $data : $this->convert_data($data,1);
}


function get_leads(){
	$leads = array();
	$query = "SELECT * FROM `fl_potentials`  WHERE 1 ORDER BY data_creazione DESC";
	
	if($risultato = mysql_query($query,CONNECT)){
	
	while($riga = mysql_fetch_array($risultato)){

	array_push($leads,array(
	
	'id'=>$riga['id'],
	'nome'=>$riga['nome'],
	'cognome'=>$riga['cognome'],
	'priorita'=>$riga['priorita']
	));
	
	}	
	$this->contenuto['class'] = 'green';
	$this->contenuto['esito'] = "OK";
	$this->contenuto['leads'] = $leads;
	
	} else { 
	$this->contenuto['class'] = 'red';
	$this->contenuto['esito'] = "Error 1102: Errore caricamento.".mysql_error();
	}
	
	$this->cnv_makedata();
}

function get_items($item_rel,$condition=0) {
	$query = "SELECT * FROM `fl_items` WHERE id != 1 AND attiva > 0 AND item_rel = $item_rel ORDER BY label ASC";
	$dati = array();
	if($risultato = mysql_query($query,CONNECT)){
	
	while($riga = mysql_fetch_array($risultato)){

	array_push($dati,array(
	
	'id'=>$riga['id'],
	'label'=>$riga['label'],
	'descrizione'=>$riga['descrizione']
	));
	
	}	
	$this->contenuto['class'] = 'green';
	$this->contenuto['esito'] = "OK";
	$this->contenuto['dati'] = $dati;
	
	} else { 
	$this->contenuto['class'] = 'red';
	$this->contenuto['esito'] = "Error 1102: Errore caricamento.".mysql_error();
	}
	
	$this->cnv_makedata();
}


/*Funzioni Globali e Utility */
function lead_info($lead_id) {
 	
	$this->app_start();
	$query = "SELECT * FROM `fl_leads_hrc` WHERE `id` = $lead_id LIMIT 1";
	$risultato = mysql_query($query,CONNECT);	
	$riga = @mysql_fetch_array($risultato); 
	
	if(mysql_affected_rows(CONNECT) < 1){			
	$this->contenuto =  array('id'=>$lead_id,'ragione_sociale'=>'Unknow','nome'=>'Unknow','cognome'=>'Unknow',"email"=>'Unknow',"telefono"=>'Unknow',"indirizzo"=>'Unknow',"cap"=>'Unknow',"localita"=>'Unknow',"citta"=>'Unknow');
	} else {
	$this->contenuto =  array('id'=>$riga['id'],'ragione_sociale'=>$riga['ragione_sociale'],'nome'=>$riga['nome'],'cognome'=>$riga['cognome'],"email"=>$riga['email'],"telefono"=>$riga['telefono'],"indirizzo"=>$riga['indirizzo'],"cap"=>$riga['cap'],"citta"=>$riga['citta']);
	}
	$this->cnv_makedata();

}
function get_page(){
		
		$query = "SELECT * FROM `fl_articles` WHERE `id`  = '".$this->page_id."' LIMIT 1";
		if($risultato = mysql_query($query,CONNECT)){
		$riga = mysql_fetch_array($risultato);
		
		$this->contenuto['esito'] = 1;
		$this->contenuto['info_txt'] = "Pagina";
		$this->contenuto['page_title'] = $riga['titolo'];
		$this->contenuto['page_content'] = $this->css.$this->convert($riga['articolo']);
		
		} else {
			
		$this->contenuto['esito'] = 0;
		$this->contenuto['info_txt'] = "Errore";
		
		}
		
		$this->cnv_makedata();
}

function do_login(){
$this->app_start();

		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
		if ($this->user != 'sistema' && !preg_match($regex,strtolower(trim($this->user)))){
		$this->contenuto['esito'] = 1;
		$this->contenuto['info_txt'] = "Per usare le api devi essere registrato";
		$this->cnv_makedata();
		}

		
		if($this->user == "" || $this->password == "") {	
		$this->contenuto['esito'] = 0;
		$this->contenuto['info_txt'] = "Inserisci user e password!";
		$this->cnv_makedata();
		}
		
	
		if($this->user != "" && $this->password != ""){
		
		$this->password = md5($this->password); 
		
		$query = "SELECT * FROM `fl_account` WHERE `password`  = '".$this->password."' AND `user`  = '".$this->user."' LIMIT 1";
		
		$risultato = mysql_query($query,CONNECT);
		
		$this->contenuto['esito'] = 0;
		$this->contenuto['info_txt'] = mysql_affected_rows();
	
	
		if(mysql_affected_rows(CONNECT) < 1){		
		$this->contenuto['esito'] = 0;
		$this->contenuto['info_txt'] = "Email o password errate!";
		$this->cnv_makedata();
		} 		
		
		$riga = mysql_fetch_array($risultato);
				
		
		if($riga['attivo'] == 0){		
		$this->contenuto['esito'] = 0;
		$this->contenuto['info_txt'] = "Utente non attivo.";
		$this->cnv_makedata();
		}
		
		mysql_query("UPDATE `fl_account` SET `visite` = visite+1 WHERE '".$this->password."' AND `user`  = '".$this->user."' LIMIT 1;");
		
		$_SESSION['user'] = $riga['user'];
		$_SESSION['operatore'] = $riga['user'];
		$_SESSION['userid'] = $riga['id'];
		$_SESSION['nome'] = $riga['nominativo'];
		$_SESSION['mail'] = $riga['email'];		
		$_SESSION['number'] = $riga['id'];			
		$_SESSION['usertype'] = $riga['tipo'];
		$_SESSION['time'] = time();	
		$_SESSION['idh'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['aggiornamento_password'] = $riga['aggiornamento_password'];
		$_SESSION['marchio'] = $riga['marchio'];
		// Fine Avvio Sessione			
		$agent = @$_SERVER['HTTP_USER_AGENT'];
		$referer = @$_SERVER['HTTP_REFERER'];
		$lang = @$_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $data = time();

		$this->contenuto['esito'] = $riga['attivo'];		
		$this->contenuto['info_txt'] = "Login OK";
		$this->contenuto['usertype'] = $_SESSION['usertype'];
		$this->contenuto['user'] = $_SESSION['user'];
		$this->contenuto['operatore'] = $_SESSION['user'];
		$this->contenuto['email'] = $_SESSION['mail'];
		$this->contenuto['usr_id'] = $_SESSION['number'];	
		$this->contenuto['token'] = session_id();
		$this->contenuto['nome'] = $_SESSION['nome'];	
		$this->contenuto['aggiornamento_password'] = $riga['aggiornamento_password'];	
		$this->contenuto['time'] = time();	
		$this->contenuto['idh'] = $_SERVER['REMOTE_ADDR'];
		$this->contenuto['marchio'] = $_SESSION['marchio'] ;
		}
		$this->cnv_makedata();
} // Login



private function data_labels($item_rel,$condition=0) {
	$query = "SELECT * FROM `fl_items` WHERE id != 1 AND attiva > 0 AND item_rel = $item_rel ORDER BY label ASC";
	$risultato = mysql_query($query,CONNECT);
	$rel_info = array();
	
	while ($riga = @mysql_fetch_array($risultato)) {
	$rel_info[$riga['id']] = $riga['label'];
    }
	
	if($condition == 1){	
	$this->contenuto = 	array('dati'=>$rel_info,'esito'=>1,'info_txt'=>"dati caricati");	
	echo json_encode($this->contenuto);
	mysql_close(CONNECT);
	exit;	
	} else {
	return $rel_info;
	}
}
function html_to_text($stringa,$quot=0){
	$stringa = str_replace("&gt;", ">",str_replace("&lt;", "<",str_replace("'", "&rsquo;",$stringa)));
	//sostituisc i <br/>
	$stringa=preg_replace("/<br\W*?\/>/", "\r\n",$stringa);
	//elimino tutti i tag
	$stringa = strip_tags($stringa);
	return $stringa."\r\n\r\n\r\n\r\n\r\n\r\n\r\n";
}
function mydate($mysqldate){
	if($mysqldate != '0000-00-00') {
	$phpdate = strtotime( $mysqldate );
	return date( 'd/m/Y', $phpdate );
	} else { return '--'; }
}
function mydatetime($mysqldate){
	$phpdate = strtotime( $mysqldate );
	return date('H:i d/m/Y', $phpdate );
}
function convert($var,$quot=0){
$var =  str_replace("../../../",ROOT,str_replace("&gt;", ">",str_replace("&lt;", "<",str_replace("'", "&rsquo;",$var))));
if($quot==0) { $var =  str_replace("&quot;", '"',$var); }
str_replace('"', "&quot;",$var);
return $var;
}
	
function check($var){
$var =  trim(str_replace("<", "&lt;",str_replace(">", "&gt;",@addslashes(@stripslashes(@str_replace('"',"&quot;",str_replace("'", "&rsquo;", $var) ))))));
return $var;
} 


	
function convert_data($data,$mode=0){

if($mode == 0) {
$tempo = explode("/",$data);
$extra = "";
$data = @mktime(0,0,0,$tempo[1],$tempo[0],$tempo[2]);
} else if($mode == 1){ 
$tempo = explode("/",$data);
$extra = "";
$data = trim($tempo[2])."-".trim($tempo[1])."-".trim($tempo[0]);
 }
return $data;

}





}