<?php 
	
	// Variabili Modulo
	$active = 1;
	$modulo_uid = 2;
	$sezione_tab = 1;
	$tab_id = 48;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 2000; 
	$sezione_id = -1;
	$jorel = 0;
	$accordion_menu = 1;    
	$formValidator = 1;
	$text_editor = 2;
	$export = 1;
	$ajax = 1;
	$jquery = 1;
	$filtri = 1;
	$calendar = 1;
	$fancybox = 1;
	$searchbox = 'Cerca..';
	 
	


	$module_title = 'Anagrafica';
    $module_menu = '';
	if(isset($_GET['action'])) $new_button = '';
	if(isset($_GET['action']) && check(@$_GET['action']) == 4) $module_title = 'Contact Center';
		
	if(isset($_GET['tab_id'])) { $tab_id = check($_GET['tab_id']); }
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['status_anagrafica']) && check(@$_GET['status_anagrafica']) != 0) { $status_anagrafica_id = check($_GET['status_anagrafica']);  } else {    $status_anagrafica_id = 0; }
	if(isset($_GET['tipo_profilo']) && check(@$_GET['tipo_profilo']) != 0) { $tipo_profilo_id = check($_GET['tipo_profilo']);  } else {    $tipo_profilo_id = 0; }
	$stato_account_id = (isset($_GET['status_account'])) ? check(@$_GET['status_account']) : 1;
	$tipo_account_id = (isset($_GET['tipo_account']) && check(@$_GET['tipo_account']) != -1) ? check(@$_GET['tipo_account']) : -1;
	$status_saldi_id = (isset($_GET['status_saldi']) && check(@$_GET['status_saldi']) != -1) ? check(@$_GET['status_saldi']) : -1;

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	$campi = "SHOW COLUMNS FROM `$tabella`";
	$risultato = @mysql_query($campi,CONNECT);
	$x = 0;
	$campi = array();
	if(ATTIVA_ACCOUNT_ANAGRAFICA == 1){	$campi = array('tipo_account','account_attivo');	 }	

	while ($riga = @mysql_fetch_assoc($risultato)) 
	{			
		
	if(select_type($riga['Field']) != 5 ) { 
						$ordinamento[$x] = record_label($riga['Field'],CONNECT,1); 
						$ordine_mod[$x] = $riga['Field'];
						$campi[$riga['Field']] = record_label($riga['Field'],CONNECT,1); $x++; 
				}
	}
	$basic_filters = array('marchio','ragione_sociale','partita_iva','numero_concessione','email','telefono');


	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC","id ASC","account ASC","ragione_sociale ASC");
	$ordine = $ordine_mod[3];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id > 2 ";
	if($_SESSION['usertype'] == 3) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(@$_GET['action'] == 12) $tipologia_main .= " AND status_anagrafica = 3 ";
	foreach($_GET as $chiave => $valore){
		$chiave = check($chiave);
		$valore = check($valore);
		if(isset($campi[$chiave]) && $chiave != 'data_a' && $chiave != 'data_da' && $chiave != 'start' && $chiave != 'a' && $chiave != 'action' && $chiave != 'cerca' && $chiave != 'ricerca_avanzata'){
			  if(select_type($chiave) != 5 && select_type($chiave) != 2 && select_type($chiave) != 19 && $chiave != 'id' && $valore != '') $tipologia_main .=  " AND LOWER($chiave) LIKE '%$valore%' ";
			  if(select_type($chiave) == 2 && $chiave != 'id' && $valore > -1) $tipologia_main .=   " AND $chiave = '$valore' ";
			  if(select_type($chiave) == 19 && $chiave != 'id' && $valore > -1) $tipologia_main .=  " AND $chiave = '$valore' ";
			}
	}
	if(isset($_GET['id_sc'])) {  $tipologia_main .= " AND data_scadenza <= NOW() ";	 }
	if(isset($_GET['cn_sc'])) {  $tipologia_main .= " AND data_scadenza_contratto <= NOW() ";	 }
	if(isset($data_da) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";

	
	if(isset($_GET['cerca'])) { 
	$valore = strtolower(check($_GET['cerca']));
	if($valore != '') {
	$tipologia_main .= " AND (";
	$x = 0;
	foreach($campi as $chiave => $val){		
		$chiave = check($chiave);
		if(select_type($chiave) != 5 && select_type($chiave) != 2 && select_type($chiave) != 19 && $chiave != 'id' && $valore != '') { if($x > 0) { $tipologia_main .= ' OR '; } $tipologia_main .=  " LOWER($chiave) LIKE '%$valore%' "; $x++; }
	}
	$tipologia_main .= ")";
	}}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/data_manager/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	include('../../fl_core/dataset/provincia.php');
	$account_id = $proprietario;
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$tipologia_attivita = $data_set->get_items_key("punto_vendita");	
	$stato_nascita = $stato_sede = $stato_residenza = $stato_punto = $stato = $paese = $data_set->data_retriever('fl_stati','descrizione',"WHERE id != 1",'descrizione ASC');
	$profilo_genitore = $account_id = $proprietario;
	$profilo_commissione = array("Nessuno","Profilo1","Profilo2","Profilo3","Profilo4","Profilo5");
	$mandatory = array("id");

	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array('pie_pagina_catalogo','widget','informazioni_disabili','numeri_utili',"bio","note",'info_aggiuntive'); 
	$select = array('garanzia_fido','profilo_commissione','profilo_genitore','provincia_nascita','scheda_apertura','emesso_da','sesso','forma_giuridica','marchio','tipo_profilo_scheda','tipologia_attivita','stato_nascita','stato_punto','stato_sede','regione_residenza','stato_residenza','account_id',"provincia","tipo_documento","punto_vendita","regione_sede","regione_punto","status_anagrafica","proprietario","status","regione","nazione");
	$select_text = array('comune_punto','comune_sede','comune_residenza','provincia_residenza',"provincia_sede","provincia_punto");
	$disabled = array("visite");
	$hidden = array('status_anagrafica','data_creazione','data_aggiornamento','account_id','tipo_utente','operatore','ip','proprietario',"relation");
	$radio = array('concessione_fido','catalogo_apri_chiudi','attivo',"fotogallery","videogallery","ecommerce","prenotazioni","catalogo",'commenti');
	$text = array();
	$multi_selection = array("servizi");	
	if($_SESSION['usertype'] > 0)array_push($hidden,"proprietario","attivo","status_anagrafica","marchio","account_affiliato",'logo','audio');
	if(defined('TIPO_DA_ACCOUNT') && @TIPO_DA_ACCOUNT == 1)	array_push($hidden,"tipo_profilo"); // Demanda all'account la tipologia di profilo
	$calendario = array('data_scadenza_smartcard','data_scadenza_contratto','data_scadenza_pec','data_scadenza','data_emissione','data_nascita');	
	$file = array("upfile",'logo','photo');
	$checkbox = array('pagamenti_f24',"tipo_profilo");
    if(defined('CAMPI_INATTIVI')) array_push($hidden,'centro_di_costo','pagamenti_f24','pin_cassetto_fiscale','data_scadenza_pec'); // Campi disabilitati per cliente governati da file customer.php
	if($_SESSION['usertype'] > 1) array_push($hidden,'attivo','commenti','tipo_profilo_scheda','catalogo','titolo_catalogo',"fotogallery","videogallery","ecommerce","prenotazioni");
	if($_SESSION['usertype'] > 1) array_push($disabled,'info_profilo');
	if(!defined('MULTI_BRAND')) array_push($hidden,'marchio');


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
	if(in_array($who,$multi_selection)){ $type = 23; }

    if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	



	/* TAB AUTOMATICI */
	if(isset($_GET['tBiD'])){ 
	$tab_id = check(base64_decode($_GET['tBiD']));
	$tabella = $tables[$tab_id]; 
	$tabs_div = 0;
	$tab_div_labels = array('id'=>"Profilo");
	
	if(isset($id) && @$id != 1 && defined('SOCIAL_ITEMS')) { 
	$tab_div_labels['logo'] = "Informazioni";
	$tab_div_labels['./mod_links.php?anagrafica_id=[*ID*]'] = "Social";
	$tab_div_labels['./mod_video.php?anagrafica_id=[*ID*]'] = "Videogallery";
	$tab_div_labels['../mod_gallery/mod_home.php?dir=[*ID*]'] = "Fotogallery";
	$tab_div_labels['./mod_qrcode.php?id=[*ID*]'] = "QR Code";
	}
	
	} else {
	
	$tabs_div = 0;
	
	if(defined('ANAGRAFICA_SEMPLICE') && @$id != 1 ){
	$tab_div_labels = array('forma_giuridica'=>"Dati Fiscali",'telefono'=>"Contatti");
	} else if(@$id != 1) {
	$tab_div_labels = array('marchio'=>'Profilo','cognome'=>"Dati Anagrafici",'tipo_documento'=>"Dati Documento",'forma_giuridica'=>"Dati Fiscali",'tipologia_attivita'=>$etichette_anagrafica['tipologia_attivita'],'telefono'=>"Contatti",'note'=>"Note");
	}
	
	if(isset($id) && @$id != 1 && defined('CONTI_BANCARI')) { 
	$tab_div_labels['./mod_conti.php?anagrafica_id=[*ID*]'] = "Conti";
	}

	
	if(isset($id) && @$id != 1 && defined('ARCHIVIO_DOCUMENTAZIONE_ANAGRAFICA')) {  // ID della cartella DMS in cui archiviare i documenti
	$tab_div_labels['../mod_dms/uploader.php?PiD='.base64_encode(FOLDER_ANAGRAFICA).'&workflow_id='.$tab_id.'&NAme[]=Carta di Identita&NAme[]=Codice Fiscale&NAme[]=Visura Camerale&NAme[]=Certificato P.iva&NAme[]=Contratto&record_id=[*ID*]'] = 'Documenti';
	}
	
	if(isset($id) && @$id != 1 && ATTIVA_ACCOUNT_ANAGRAFICA == 1 && !isset($view)) { 
	$tab_div_labels['../mod_account/mod_scheda.php?anagrafica_id=[*ID*]&external'] = "Account";
	}

	}

?>