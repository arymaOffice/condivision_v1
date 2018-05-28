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
	$ajax = 1;
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$searchbox = 'Cerca..';
	$filtri = 1;
	
	
	$documentazione_auto = 18;
	if(isset($_GET['tBiD'])){ 
	$tab_id = check(base64_decode($_GET['tBiD']));
	$tabella = $tables[$tab_id]; 
	$tabs_div = 0;
	$tab_div_labels = array('id'=>"Profilo");
	
	if(isset($id) && @$id != 1 && defined('SOCIAL_ITEMS')) { 
	$tab_div_labels['./mod_links.php?anagrafica_id=[*ID*]'] = "Social";
	$tab_div_labels['./mod_video.php?anagrafica_id=[*ID*]'] = "Videogallery";
	$tab_div_labels['../mod_gallery/mod_home.php?dir=[*ID*]'] = "Fotogallery";
	$tab_div_labels['./mod_qrcode.php?id=[*ID*]'] = "QR Code";
	}
	} else {
	$tabs_div = 0;
	if(defined('ANAGRAFICA_SEMPLICE')){
	$tab_div_labels = array('id'=>"Dati Fiscali",'telefono'=>"Contatti");
	} else {
	$tab_div_labels = array('id'=>"Dati Anagrafici",'tipo_documento'=>"Dati Documento",'forma_giuridica'=>"Dati Fiscali",'telefono'=>"Contatti",'note'=>"Note");
	}
	if(isset($id) && @$id != 1 && defined('CONTI_BANCARI')) { 
	$tab_div_labels['./mod_conti.php?anagrafica_id=[*ID*]'] = "Conti";
	}
	if(isset($id) && @$id != 1 && defined('PARCO_VEICOLI')) { 
	$tab_div_labels['./mod_veicoli.php?workflow_id='.$tab_id.'&parent_id=[*ID*]'] = "Veicoli";
	}
	
	if(isset($id) && @$id != 1 && defined('ARCHIVIO_DOCUMENTAZIONE_ANAGRAFICA')) {  // ID della cartella DMS in cui archiviare i documenti
	$tab_div_labels['../mod_dms/uploader.php?PiD='.base64_encode(FOLDER_ANAGRAFICA).'&workflow_id='.$tab_id.'&NAme[]=Preventivo&NAme[]=Contratto&record_id=[*ID*]'] = 'Documenti Allegati';
	}


	if(isset($id) && @$id != 1 && ATTIVA_ACCOUNT_ANAGRAFICA == 1) { 
	$tab_div_labels['../mod_account/mod_scheda.php?anagrafica_id=[*ID*]&external'] = "Account";
	}
	}

	$module_title = 'Clienti';
    $module_menu = '
	<ul>
     </ul>';
		
	if(isset($_GET['tab_id'])) { $tab_id = check($_GET['tab_id']); }
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['status_anagrafica']) && check(@$_GET['status_anagrafica']) != 0) { $status_anagrafica_id = check($_GET['status_anagrafica']);  } else {    $status_anagrafica_id = -1; }
	if(isset($_GET['tipo_profilo']) && check(@$_GET['tipo_profilo']) != 0) { $tipo_profilo_id = check($_GET['tipo_profilo']);  } else {    $tipo_profilo_id = 0; }
	$stato_account_id = (isset($_GET['status_account']) && check(@$_GET['status_account']) != -1) ? check(@$_GET['status_account']) : -1;
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("ragione_sociale ASC","data_creazione ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] == 3) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(@$_GET['action'] == 12) $tipologia_main .= " AND status_anagrafica = 3 ";
	if(isset($data_da) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($userid) && @$userid != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND proprietario = $userid ";	 }
	if(isset($status_anagrafica_id) && @$status_anagrafica_id > -1) {  $tipologia_main .= " AND status_anagrafica = $status_anagrafica_id ";	 }
	if(isset($tipo_profilo_id) && @$tipo_profilo_id != 0) {  $tipologia_main .= " AND tipo_profilo = $tipo_profilo_id ";	 }
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('nome','nome');
	$tipologia_main .= ricerca_avanzata('cognome','cognome');
	$tipologia_main .= ricerca_avanzata('ragione_sociale','ragione_sociale');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	include('../../fl_core/dataset/gruppo.php');
	include('../../fl_core/dataset/provincia.php');
	include('../../fl_core/dataset/items_rel.php');
	$tipologia_attivita = get_items_key("punto_vendita");	
	$account_id = $proprietario;
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();

	$stato_nascita = $stato_sede = $stato_residenza = $stato_punto = $stato = $paese = $data_set->data_retriever('fl_stati','descrizione',"WHERE id != 1",'descrizione ASC');

	
	$mandatory = array("id");

	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("bio","note"); 
	$select = array('regione_residenza','tipologia_attivita','stato_nascita','stato_punto','stato_sede','stato_residenza','provincia_nascita','account_id',"provincia","tipo_documento","punto_vendita","regione_sede","regione_punto","status_anagrafica","proprietario","status","regione","nazione");
	$select_text = array('comune_punto','comune_sede','comune_residenza','provincia_residenza',"provincia_sede","provincia_punto");
	$disabled = array("visite");
	$hidden = array('industry','tipo_profilo','professione','status_anagrafica','data_creazione','data_aggiornamento','account_id','tipo_utente','marchio','operatore','ip','proprietario',"relation");
	$radio = array("fotogallery","videogallery","ecommerce","prenotazioni","catalogo");
	$text = array();
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario","attivo","status_anagrafica","marchio","account_affiliato",'logo','audio');
	}
	$calendario = array('data_acquisto','data_consegna','anno_immatricolazione','data_saldo','data_consegna','data_scadenza_pec','data_scadenza','data_emissione','data_nascita');	
	$file = array("upfile");
	$checkbox = array('pagamenti_f24','sesso',"tipo_profilo","forma_giuridica");
    if(defined('CAMPI_INATTIVI')) array_push($hidden,'centro_di_costo','pagamenti_f24','pin_cassetto_fiscale','data_scadenza_pec'); // Campi disabilitati per cliente governati da file customer.php
	if($_SESSION['usertype'] > 1) array_push($hidden,'catalogo','titolo_catalogo',"fotogallery","videogallery","ecommerce","prenotazioni");
	
	
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

    if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	
?>