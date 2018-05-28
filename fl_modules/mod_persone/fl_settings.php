<?php 
	
	// Variabili Modulo
	$active = 1;
	$sezione_tab = 1;
	$tab_id = 85;
	
	$select = "*";
	$step = 2000; 
	$sezione_id = -1;
	$jorel = 0;
	$accordion_menu = 1;
	$formValidator = 1;
	$text_editor = 2;
	$jquery = 1;
	
	$calendar = 1;
	$fancybox = 1;
	$searchbox = 'Cerca..';
	$documentazione_auto = 18;
	$tabella = $tables[$tab_id];
	
	$tabs_div = 0;
	$tab_div_labels = array('id'=>"Persona",'sedi_id'=>'Sedi assegnate');//,'mod_valuta_competenze.php?PerID=[*ID*]'=>'Valutazione Competenze','mod_report_competenze.php?PerID=[*ID*]'=>'Panoramica Competenze');

	if(!isset($_SESSION['proprietario_id'])) $_SESSION['proprietario_id'] = 0;
	if(isset($_GET['cmy'])) $_SESSION['proprietario_id'] = check(base64_decode($_GET['cmy']));
	$proprietario_id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : $_SESSION['proprietario_id'];


	$module_title = 'Persone';
	$new_button = '<a href="./mod_inserisci.php?id=1&ANiD='.base64_encode($proprietario_id).'1" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a>';
    $module_menu = '';


  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-d H:i',time()-999204800); 
	 $data_a = date('Y-m-d H:i',time()+86400); 
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("cognome ASC","data_creazione ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 AND anagrafica_id = $proprietario_id ";

	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('nome','nome');
	$tipologia_main .= ricerca_avanzata('cognome','cognome');
	$tipologia_main .= ricerca_avanzata('cellulare','cellulare');
	$tipologia_main .= ricerca_avanzata('email','email');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	include('../../fl_core/dataset/gruppo.php');
	include('../../fl_core/dataset/stato.php');
	include('../../fl_core/dataset/provincia.php');
	include('../../fl_core/dataset/items_rel.php');
	$account_id = $proprietario;
	$tipo_profilo = array('2'=>'Cliente','3'=>'Fornitore');
	$mandatory = array("id");

	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$anagrafica =  $anagrafica_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale',"WHERE id != 1  AND tipo_profilo = 0",'ragione_sociale ASC');
	$profilo_funzione = $data_set->data_retriever('fl_profili_funzione','funzione',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'funzione ASC');
	$sedi_id = $data_set->data_retriever('fl_sedi','sede,citta',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'sede ASC');
	$sedi_id[0] = 'Tutte';

	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("note"); 
	$select = array('profilo_funzione');
	$disabled = array("visite");
	$hidden = array('attivo','descrizione_uso_interno','anagrafica_id','data_creazione','data_aggiornamento','account_id','tipo_utente','marchio','operatore','ip','proprietario',"relation");
	$radio = array("catalogo");
	$text = array();
	$checkbox = array();
	$calendario = array();
	$file = array("upfile");
	$multi_selection = array('sedi_id');
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$multi_selection)){ $type = 6; }
    if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	
?>