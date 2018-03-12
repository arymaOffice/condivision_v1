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
	//$text_editor = 0;
	$jquery = 1;
	$searchbox = "Cerca nome...";
	$fancybox = 1;
	$calendar = 1;
	$documentazione_auto = 8;
	$dateTimePicker = 1;
	if($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 3) { $filtri = 1; }
    $module_title = "Agenda Appuntamenti";
		
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != '') { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	if($_SESSION['usertype'] != 0 && !isset($_GET['proprietario'])) $proprietario_id = $_SESSION['number'];
	if(isset($_GET['issue'])) { $issue_id = check($_GET['issue']);  } else {    $issue_id = -1; }
	if(isset($_GET['potential_rel'])) { $userid = check($_GET['potential_rel']);  } else {    $userid = 0; }
	if(isset($_GET['marchio'])) { $marchio_id = check($_GET['marchio']);  } else {    $marchio_id = $_SESSION['marchio']; }
	if(isset($_GET['meeting_location'])) { $meeting_location_id = check($_GET['meeting_location']);  } else {    $meeting_location_id = (defined('DEFAULT_MEETING_LOCATION')) ? DEFAULT_MEETING_LOCATION : -1; }
	if(isset($_GET['tipologia_appuntamento'])) { $tipologia_appuntamento_id = check($_GET['tipologia_appuntamento']);  } else {    $tipologia_appuntamento_id = -1; }
	

	
  	 if(isset($_GET['data_da']) && check(@$_GET['data_da']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); 
	 $data_da_t = check($_GET['data_da']); 
	 } else {
	 $data_da = date('Y-m-d',time()); 
	 $data_da_t = date('d/m/Y'); 
	 $domanistessaora = date('d/m/Y H:i',strtotime('+1 day')); 
	 }
	 $prev_date = date('Y-m-d', strtotime($data_da .' -1 day'));
	 $next_date = date('Y-m-d', strtotime($data_da .' +1 day'));


	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("start_meeting DESC","potential_rel ASC","operatore ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	if(isset($userid) && @$userid > 0) {  $tipologia_main .= " AND potential_rel = $userid ";	 }
	//if(isset($proprietario_id) && @$proprietario_id > 0) {  $tipologia_main .= " AND (proprietario = $proprietario_id OR callcenter = $proprietario_id) ";	 }
	if(isset($meeting_location_id) && @$meeting_location_id > -1) {  $tipologia_main .= " AND meeting_location = $meeting_location_id ";	 } 
	if(isset($issue_id) && @$issue_id > -1) {  $tipologia_main .= " AND issue = $issue_id ";	 }
	if(isset($tipologia_appuntamento_id) && @$tipologia_appuntamento_id > -1) {  $tipologia_main .= " AND tipologia_appuntamento = $tipologia_appuntamento_id ";	 }
	 
	if(isset($_GET['notissued'])) {  $tipologia_main .= " AND proprietario = ".$_SESSION['number']." AND  DATE(`start_meeting`) <= NOW() AND issue = 6";	 } 
	else if(isset($_GET['action'])) { 
	$tipologia_main .= '';
	} else {
	$tipologia_main .= (!isset($_GET['next'])) ? " AND DATE(`start_meeting`) = '$data_da' " : " AND  DATE(`start_meeting`) > NOW() ";
	}
	

	$tipologia_main .= "  ";	

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";	
	$tipologia_main = "WHERE id != 1 AND marchio = ".$_SESSION['marchio'];
	if($_SESSION['usertype'] == 2 ) $tipologia_main = "WHERE id != 1   AND marchio = ".$_SESSION['marchio']." AND (proprietario = ".$_SESSION['number']." OR proprietario < 2) ";
	$tipologia_main .= ricerca_semplice('nominativo','nominativo');	
	$tipologia_main .= ")";
	}
	
	
	/* Inclusioni Oggetti Dati */
	include('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();

	$paese = $data_set->data_retriever('fl_stati','descrizione',"WHERE id != 1",'descrizione ASC');
	$proprietario = $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1",'nominativo ASC');
	$promoter = $proprietario;
	$tipologia_appuntamento = $data_set->get_items_key("tipologia_appuntamento");
	unset($tipologia_appuntamento[0]);
	$source_potential = $data_set->data_retriever('fl_campagne_attivita','oggetto',"WHERE id != 1",'oggetto ASC');
	unset($source_potential[0]);
	$campagna_id = $data_set->data_retriever('fl_campagne','descrizione',"WHERE id != 1",'descrizione ASC');
	unset($campagna_id[0]);

	$meeting_location = $data_set->data_retriever('fl_sedi','sede',"WHERE id != 1",'sede ASC');
	$meeting_location[0] = 'Tutte';
	$proprietario['-1'] = "Tutti";



	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array("messaggio"); 
	$select = array('campagna_id',"mansione","paese","proprietario","status_pagamento","causale","metodo_di_pagamento");
	$disabled = array("visite");
	$hidden = array('all_day',"note",'dove','source_potential','issue',"data_creazione",'proprietario','marchio','callcenter',"data_arrived",'potential_rel','is_customer',"data_aggiornamento","marchio","ip","operatore");
	$radio = array();
	$text = array();
	$calendario = array();	
	$file = array();
	$timer = array();
	$touch = array();
	$checkbox = array('tipologia_appuntamento');
	$datePicker = array('end_meeting','start_meeting','start_date','end_date');
	if(defined('MULTI_LOCATION')) { $select[] = 'meeting_location'; } else { $hidden[] = 'meeting_location'; }
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$timer)){ $type = 7; }
	if(in_array($who,$datePicker)){ $type = 11; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$hidden)){ $type = 5; }

	
	return $type;
	}
	
	
	$module_menu = '';
	$menu = array('Tutti'=>'./?action=23&meeting_location=0');
	foreach($tipologia_appuntamento as $id => $label){	 $menu[$label] = './?action=23&meeting_location=0&tipologia_appuntamento='.$id; }


	$b = (!isset($_GET['b'])) ? 'Tutti' : check($_GET['b']);
	
	foreach($menu as $label => $link){ // Recursione Indici di Categoria
		$selected = ($b  == $label) ? " class=\"selected\"" : "";
		$module_menu .= "<li $selected><a href=\"$link&b=$label\">".ucfirst($label)."</a></li>\r\n"; 
	}


	
?>
