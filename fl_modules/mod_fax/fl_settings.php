<?php 
	ini_set('display_errors',0);
	error_reporting(E_ALL);
	// Variabili Modulo
	$sezione_tab = 1;
	$modulo_uid = 23;
	$tab_id = 42;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 20; 
	$jquery = 1;
	$ajax = 1;
	$fancybox = 1;
	$searchbox = 'Ricerca Fax';
	$calendar = 1;
	$documentazione_auto = 18;
	$filtri = 1;
	
	$module_title = 'Ricezione FAX ';
	if(!isset($_GET['action']))	$module_title = 'Archivio FAX ';

    $new_button = '';
    $module_menu = '
	
  	   <li class=""><a href="./?action=15&a=dashboard">Ricezione <span class="subcolor"> </span></a>      </li>
	   <li class=""><a href="./">Archivio <span class="subcolor"> </span></a>      </li>
	';



	set_time_limit(1200);
	ini_set('max_execution_time',300);
	$username1 = "betitaly@betitalynetwork.it";
	$username2 = "betscore@betitalynetwork.it";
	$username3 = "giocasempre@betitalynetwork.it";
	$password = "fersinoandrea";
	$server = "mail.betitalynetwork.it";
	$mail_accounts = array('','betitaly','betscore','giocasempre');

	if(!isset($_GET['userid']) && function_exists('imap_open') ){
	$mail_conn1 = imap_open("{".$server.":110/pop3/novalidate-cert}INBOX",$username1, $password);
	$mail_conn2 = imap_open("{".$server.":110/pop3/novalidate-cert}INBOX",$username2, $password);
	$mail_conn3 = imap_open("{".$server.":110/pop3/novalidate-cert}INBOX",$username3, $password);
	$mail_conns = array('',$mail_conn1,$mail_conn2,$mail_conn3);
	}

	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1) { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	if(isset($_GET['note']) && check(@$_GET['note']) != -1) { $note = check($_GET['note']); } else {  $note = ''; }
	if(isset($_GET['identificato']) && check(@$_GET['identificato']) != 0) { $identitficato_id = check($_GET['identificato']);  } else {    $identitficato_id = 0; }
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-1'); 
	 $data_a = date('Y-m-d',time()+86400); 
	 
	 $data_da_t = date('1/m/Y'); 
	 $data_a_t = date('d/m/Y'); 
	 }
	   	
	
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("data_aggiornamento ASC","account ASC","identificato ASC","id DESC");
	$ordine = $ordine_mod[3];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(isset($_GET['data_da']) && !isset($_GET['cerca'])) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:00' ";
	if(isset($proprietario_id) && @$proprietario_id != -1) {  $tipologia_main .= " AND proprietario = $proprietario_id ";	 }
	if(isset($identitficato_id) && @$identitficato_id != 0) {  $tipologia_main .= " AND identificato = $identitficato_id ";	 }
	if(isset($note) && @$note != '') {  $tipologia_main .= " AND note LIKE '%$note%' ";	 }
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('account','account');
	$tipologia_main .= ricerca_avanzata('mittente','mittente');
	$tipologia_main .= ricerca_avanzata('note','note');

	$tipologia_main .= ")";
	}

	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/cms.php');
	include('../../fl_core/dataset/proprietario.php');

	
	function select_type($who){

	
	$textareas = array("note"); 
	$select = array('proprietario');
	$disabled = array();
	$hidden = array("data_ricezione","mittente","account","data_creazione","ip","operatore","data_aggiornamento");
	$radio = array();
	$text = array();
	if(@!is_numeric($_GET['action']) || $_SESSION['usertype'] > 0){
		
	array_push($hidden,"attivo","identificato");
	} else { $radio  = array("attivo");	};

	$calendario = array();	
	$file = array("upfile");
	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 19; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	
	return $type;
	}
	
	
?>