<?php 
	
	// Variabili Modulo
	$active = 1;
	$sezione_tab = 1;
	$tab_id = 71;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$sezione_id = -1;
	$jorel = 0;
	$text_editor = 2;
	$jquery = 1;
	$searchbox = "Cerca nome...";
	$fancybox = 1;
	$calendar = 1;
	$documentazione_auto = 8;
	$dateTimePicker = 1;
	$filtri = 1;
	if($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 3) { $filtri = 1; }
  
		
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != '') { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	if($_SESSION['usertype'] != 0 && !isset($_GET['proprietario'])) $proprietario_id = $_SESSION['number'];
	
	if(isset($_GET['issue'])) { $issue_id = check($_GET['issue']);  } else {    $issue_id = -1; }
	if(isset($_GET['potential_rel'])) { $userid = check($_GET['potential_rel']);  } else {    $userid = 0; }
	if(isset($_GET['marchio'])) { $marchio_id = check($_GET['marchio']);  } else {    $marchio_id = $_SESSION['marchio']; }
	if(isset($_GET['meeting_location'])) { $meeting_location_id = check($_GET['meeting_location']);  } else {    $meeting_location_id = -1; }
    if(isset($_GET['source_potential'])) $source_potential_id  = check($_GET['source_potential']);
	

	
  	 if(isset($_GET['data_da']) && check(@$_GET['data_da']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); 
	 $data_a = (isset($_GET['data_a'])) ? convert_data($_GET['data_a'],1) : $data_da; 
	 $data_da_t = check($_GET['data_da']); 
	 $data_a_t = (isset($_GET['data_a'])) ? check($_GET['data_a']) : $data_da_t; 
	 } else {
	 $data_da = date('Y-m-d',time()); 
	 $data_da_t = date('d/m/Y'); 
	 $data_a = date('Y-m-d',time()); 
	 $data_a_t = date('d/m/Y'); 
	 $domanistessaora = date('d/m/Y H:i',strtotime('+1 day')); 
	 }
	 $prev_date = date('Y-m-d', strtotime($data_da .' -1 day'));
	 $next_date = date('Y-m-d', strtotime($data_da .' +1 day'));


	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("start_meeting DESC","potential_rel ASC","operatore ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 AND marchio = ".$_SESSION['marchio']."  ";
	if(isset($userid) && @$userid > 0) {  $tipologia_main .= " AND potential_rel = $userid ";	 }
	if(isset($proprietario_id) && @$proprietario_id > 0 && !isset($_GET['source_potential'])) {  $tipologia_main .= " AND (proprietario = $proprietario_id OR callcenter = $proprietario_id) ";	 }
	if(isset($meeting_location_id) && @$meeting_location_id > -1) {  $tipologia_main .= " AND meeting_location = $meeting_location_id ";	 } 
	if(isset($issue_id) && @$issue_id > -1) {  $tipologia_main .= " AND issue = $issue_id ";	 }
	

	if(isset($_GET['notissued'])) {  $tipologia_main .= " AND DATE(start_meeting) < CURDATE() AND issue < 2";	 } 
	else if(isset($_GET['gestiti'])) {  $tipologia_main .= " AND issue > 1";	 } 
	else if(isset($_GET['presi'])) {  $tipologia_main .= " ";	 } 
	else if(isset($_GET['next'])) {  
	$tipologia_main .= " AND  DATE(`start_meeting`) > NOW()";	 }
	else if(isset($_GET['presiAttivita'])) {  
	$tipologia_main .= " AND issue = 0";	 } 
	else if(isset($_GET['ok'])) {  
	$tipologia_main .= " AND  issue != 0 AND issue != 3";	 } 
	else if(isset($_GET['ko'])) {  
	$tipologia_main .= " AND issue = 3 ";	 } 

	else if(!isset($_GET['action'])) {  $tipologia_main .= " AND DATE(`start_meeting`) BETWEEN '$data_da' AND '$data_a' ";	 } 



	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";	
	$tipologia_main = "WHERE id != 1 AND marchio = ".$_SESSION['marchio'];
	if($_SESSION['usertype'] == 2 ) $tipologia_main = "WHERE id != 1   AND marchio = ".$_SESSION['marchio']." AND (proprietario = ".$_SESSION['number']." OR proprietario < 2) ";
	$tipologia_main .= ricerca_semplice('nominativo','nominativo');	
	$tipologia_main .= ")";
	}
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$mansione = $data_set->get_items_key("mansione");	
	$paese = $data_set->data_retriever('fl_stati','descrizione',"WHERE id != 1",'descrizione ASC');
	
	$operatoribdc =  $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND tipo = 3   ",'nominativo ASC');
	$venditore = $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND  tipo = 4  ",'nominativo ASC');
	$operatoridgt = $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND  (tipo = 5)  ",'nominativo ASC');
	unset($operatoribdc[0]);
	unset($operatoridgt[0]);
	unset($venditore[0]);


	$promoter = $proprietario;

	$meeting_location = $data_set->data_retriever('fl_sedi','sede',"WHERE id != 1 AND mostra_in_agenda = 1",'sede ASC');
	$meeting_location[0] = 'Tutte';
	$proprietario['-1'] = "Tutti";

	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array("messaggio","note"); 
	$select = array('meeting_location',"mansione","paese","proprietario","status_pagamento","causale","metodo_di_pagamento");
	$disabled = array("visite");
	$hidden = array('issue',"data_creazione",'marchio','callcenter',"data_arrived",'potential_rel','is_customer',"data_aggiornamento","marchio","ip","operatore");
	$radio = array('all_day');
	$text = array();
	$calendario = array();	
	$file = array();
	$timer = array();
	$touch = array();
	$datePicker = array('end_meeting','start_meeting','start_date','end_date');
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$timer)){ $type = 7; }
	if(in_array($who,$datePicker)){ $type = 11; }
	
	return $type;
	}
	
		$module_menu = '';
	//if( ($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 3) && ($proprietario_id < 1 || isset($_GET['intro']))) { 
	foreach($meeting_location as $valores => $label){ // Recursione Indici di Categoria
				$selected = ($meeting_location_id  == $valores) ? " class=\"selected\"" : "";
			    $module_menu .= "<li $selected><a href=\"./?action=23&meeting_location=$valores\">".ucfirst($label)."</a></li>\r\n"; 
	}//}


	
?>