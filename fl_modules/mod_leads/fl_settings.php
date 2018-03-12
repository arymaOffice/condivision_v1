<?php 
	

	$modulo_uid = 26;
	$parametri_modulo = check_auth($modulo_uid); /* Nuova preconfigurazione dei moduli (da convertire in classe?) */
	// Variabili Modulo
	$module_title = $parametri_modulo['label'];
	$permesso    = $parametri_modulo['permesso'];
	$tab_id   = $tab_parent_id   = $parametri_modulo['tab_id'];
	$tabella     = $tables[$tab_id];
	$select      = "*";
	$ordine      = $parametri_modulo['ordine_predefinito']; 
	$step        = $parametri_modulo['risultati_pagina']; 
	//$text_editor = $parametri_modulo['editor_wysiwyg'];
	$jquery      = $parametri_modulo['jquery'];
	$fancybox    = $parametri_modulo['fancybox'];
	$filtri      = $parametri_modulo['filtri'];
	$toggleOn    = $parametri_modulo['menu_aperto'];
	$calendar    = $parametri_modulo['calendari'];
	if($parametri_modulo['ricerca'] == 1) $searchbox = $parametri_modulo['placeholder_ricerca'];
	$checkRadioLabel = '<i class="fa fa-check-square"></i><i class="fa fa-square-o"></i>'; // Pulsante checkbox radio button Toggle apple
	//$tab_div_labels = array('id'=>"Dati Personali",'tipo_interesse'=>"Interesse");

	$data_dafault = 'data_creazione';//(defined('tab_prefix') && @tab_prefix == 'hrc') ? 'data_visita' : 'data_creazione';
	
	if(isset($_GET['id']) && check(@$_GET['id']) != 1) {
	$tab_div_labels = array('mod_richieste.php?reQiD=[*ID*]'=>'Attività','id'=>"Dati Personali",'tipo_interesse'=>"Interesse",'../mod_appuntamenti/mod_user.php?history&potential_rel=[*ID*]'=>"Agenda Incontri",'../mod_preventivi/mod_user.php?potential_id=[*ID*]'=>"Offerte");
	if(defined('tab_prefix') && tab_prefix == 'hrc')  $tab_div_labels['../mod_eventi/mod_user.php?lead_id=[*ID*]'] = "Eventi";
	}

	
	if(!isset($_SESSION['status_potential_id'])) $_SESSION['status_potential_id'] = -1;
	if(isset($_GET['status_potential'])) $_SESSION['status_potential_id'] = check($_GET['status_potential']);
	$status_potential_id = $_SESSION['status_potential_id'];

	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	 } else {
	 $data_da = date('Y-m-d',time()-31536000);  
	 $data_a =  date('Y-m-d',time()); 
	 $data_da_t = date('d/m/Y',time()-31536000); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("in_use DESC, data_creazione DESC, data_aggiornamento ASC","user ASC","proprietario ASC","id DESC");
	//$ordine = $ordine_mod[0];	
	
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC");
	
	/*Crea i campi, ordinamemnti e modalista */
	$campi = "SHOW COLUMNS FROM `$tabella`";
	$risultato = mysql_query($campi, CONNECT);
	$x = 0;
	$campi = array();
	while ($riga = mysql_fetch_assoc($risultato)) 
	{			
	$ordinamento[$x] = record_label($riga['Field'],CONNECT,1); 
	$ordine_mod[$x] = $riga['Field'];
	$campi[$riga['Field']] = record_label($riga['Field'],CONNECT,1); $x++; 
	}
	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('location_evento','status_potential','proprietario','campagna_id','source_potential','interessato_a','centro_di_ricavo','tipo_interesse','anno_di_interesse');
	$ordine = $ordine_mod[0].' DESC';	

	/* Strutturazione della query */
	$tipologia_main = gwhere($campi,'WHERE id != 1 ','','id','data_creazione',31536000,0);//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica


	if(isset($data_da) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND (`$data_dafault`  BETWEEN '$data_da' AND '$data_a' OR `$data_dafault`  = '0000-00-00') ";
	/* Filtri personalizzati */
	if(isset($_GET['qualificati'])) { $qualificati_id = check($_GET['qualificati']);  } else {    $qualificati_id = -1; }
	if(isset($qualificati_id) && @$qualificati_id == 1) {  $tipologia_main .= " AND (email != '' AND telefono != '' AND nome != '') ";	 }
	if(isset($qualificati_id) && @$qualificati_id == 0) {  $tipologia_main .= " AND (email = '' OR telefono = '' OR nome = '' ) ";	 }
	if(isset($qualificati_id) && @$qualificati_id == 2) {  $tipologia_main .= " AND (periodo_interesse != ''  AND interessato_a != '' AND email != '' AND telefono != '' AND nome != '') ";	 }
	$tipologia_main .= ($status_potential_id > -1)  ? " AND status_potential = $status_potential_id" :   "";
	$tipologia_main .= ($status_potential_id == 1 && !isset($_GET['cerca']))  ? " OR (`$data_dafault`  BETWEEN '$data_da' AND '$data_a' AND status_potential != -1 AND data_visita != '0000-00-00') " :   "";	

	$where_count = str_replace('WHERE ','', $tipologia_main);

	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$location_evento = $data_set->data_retriever('fl_sedi','sede',"WHERE id != 1",'sede ASC');
	$mansione = $data_set->get_items_key("mansione");	
	$tipo_interesse = $data_set->get_items_key("tipo_interesse");	
	$interessato_a  = $data_set->data_retriever('fl_cg_res','codice,label','WHERE attivo = 1 AND id > 1 AND parent_id = 0 AND tipo_voce = 1',' id ASC ');
	$centro_di_ricavo  = $data_set->data_retriever('fl_cg_res','codice,label','WHERE attivo = 1 AND id > 1 AND parent_id > 0 AND tipo_voce = 1');
	$pagamento_vettura = $data_set->get_items_key("pagamento_vettura");	
	$stato_nascita = $stato_sede = $stato_residenza = $stato_punto = $paese = $data_set->data_retriever('fl_stati','descrizione',"WHERE id != 1",'descrizione ASC');
	$campagna_id = $data_set->data_retriever('fl_campagne','descrizione',"WHERE id != 1",'descrizione ASC');
	$source_potential = $data_set->data_retriever('fl_campagne_attivita','oggetto',"WHERE id != 1",'oggetto ASC');
	$source_potential[1] = 'Inserimento manuale'; 
	$utm_medium = $data_set->get_items_key("utm_medium");

	unset($campagna_id[0]);
	unset($campagna_id[-1]);
	unset($utm_medium[0]);
	unset($utm_medium[-1]);
	unset($utm_medium[1]);
	$campagna_id = $campagna_id+$utm_medium;

	unset($source_potential[0]);

	$priorita_contatto = array('Bassa','Media','Alta');
	$operatoribdc =  $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND tipo = 3   ",'nominativo ASC');
	$venditori = $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND  tipo = 4  ",'nominativo ASC');
	$operatoribdc[0] = 'Non Assegnato'; 
	unset($venditori[0]);

	$templateSMS = $data_set->data_retriever('fl_msg_template','oggetto',"WHERE id != 1 AND tipo_template IN(0,1) ",'oggetto ASC');
	$templateEMAIL = $data_set->data_retriever('fl_msg_template','oggetto',"WHERE id != 1 AND tipo_template IN(0,2) ",'oggetto ASC');
	$mittente = $data_set->get_items_key("mittente");	
	$tag_sms = $data_set->get_items_key("tag_sms");
	$rito_civile = $data_set->get_items_key("rito_civile");
    
	$ambienti = $data_set->get_items_key("ambienti");
	unset($ambienti[0]);
	unset($ambienti[1]);	
	if(defined('MULTI_LOCATION')) $ambienti =  $data_set->data_retriever('fl_ambienti','nome_ambiente',"WHERE id != 1",'tipo_ambiente ASC');
	$stato_evento = array('Bozza','Attesa Contratto','Confermato','Archiviato','Annullato');

    $anno_di_interesse = array(); for($i=date('Y');$i<date('Y')+10;$i++) $anno_di_interesse[$i] = $i;

	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array('note'); 
	$select = array('centro_di_ricavo','proprietario','anno_di_interesse','interessato_a','campagna_id',"source_potential",'vettura_posseduta_alimentazione','pagamento_vettura','experience_level',"mansione","paese","proprietario","status_pagamento","causale","metodo_di_pagamento");
	$select_text = array("provincia","citta");
	$disabled = array("messaggio","data_creazione","visite");
	$hidden = array('industry',"paese",'marchio',"data_assegnazione","data_scadenza","data_scadenza_venditore",'venditore',"company","job_title","data_creazione","sent_mail","in_use",'is_customer',"data_aggiornamento","ip","operatore");
	$radio = array('rito_civile');
	$text = array();
	$selectbtn = array('location_evento',"status_potential",'attivo');	
	$calendario = array('data_visita');	
	$file = array();
	$invisible = array('priorita_contatto');
	$datePicker = array();
	$checkbox = array('tipo_interesse');
	$multi_selection = array("ambienti","mesi_di_interesse","giorni_di_interesse");
	$ifYesText = array();		
	if(defined('tab_prefix') && @tab_prefix == 'hrc') $hidden = array("indirizzo","ragione_sociale","partita_iva","paese","","","data_assegnazione","data_scadenza","data_scadenza_venditore",'venditore',"website","fatturato_annuo","mansione","company","job_title","numero_dipendenti","data_creazione","sent_mail","in_use","status_potential",'is_customer',"data_aggiornamento","marchio","ip","operatore");
	
	if(isset($_GET['id'])) { 
	if(check($_GET['id']) == 1) { 
		array_push($invisible,'anno_di_interesse','ambienti','mesi_di_interesse','tipo_interesse','periodo_interesse','preferenze_menu','prezzo_preventivato'); 
		array_push($hidden,'cap','proprietario','messaggio','rito_civile'); 
		if(check($_GET['status_potential']) != 1)  array_push($hidden,'source_potential');  
	}}

	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$select_text)) { $type = 12; }	
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$selectbtn)){ $type = 9; }
	if(in_array($who,$multi_selection)){ $type = 23; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$datePicker)){ $type = 11; }
	if(in_array($who,$ifYesText)){ $type = 13; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$invisible)){ $type = 7; }

	return $type;
	}
	

	if($status_potential_id >= 0){ 
	$module_title = mk_count($tabella,$where_count).' leads in ';
	if(isset($status_potential_id )) $module_title .= $status_potential[$status_potential_id];
	} else {
	$module_title = mk_count($tabella,$where_count).' leads (Tutti)';
	} 
    

    $module_menu = '<li $selected><a href="./?status_potential=-1">Tutti</a></li>';

  

	     foreach($status_potential as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_potential_id == $valores) ? " class=\"selected\"" : "";
			$action = (isset($_GET['action'])) ? '&action='.check($_GET['action']) : "";
			$module_menu .= "<li $selected><a href=\"./?status_potential=$valores$action\">".ucfirst($label)."</a></li>\r\n"; 
		 }
?>