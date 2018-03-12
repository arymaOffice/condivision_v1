<?php 
	
	// Variabili Modulo
	
	$tab_id = 107;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$jorel = 0;
	$jquery = 1;
	$fancybox = 1;
	$calendar = 1;
	if(isset($_GET['tipo_template']) && check($_GET['tipo_template']) > 1) $text_editor = 2;
	 
	$module_title = "Template";
	$module_menu = '<li><a href="./?tipo_template=1">SMS</a></li><li><a href="./?tipo_template=2">Email</a></li>';
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC");
	$ordine = $ordine_mod[0];	
	if(isset($_GET['tipo_template'])) { $tipo_template = check($_GET['tipo_template']);  } else {    $tipo_template = 1; }
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 AND tipo_template = $tipo_template ";
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_avanzata('messaggio','messaggio');	
	}
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	require_once('../../fl_core/class/ARY_dataInterface.class.php');
    $data_set = new ARY_dataInterface();
    $mittente = $data_set->get_items_key("mittente");	
	$tag_sms = $data_set->get_items_key("tag_sms");
	$tipo_template = array('Generico','SMS','Email');
	
	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array('messaggio'); 
	$select = array('mittente');
	$disabled = array('from');
	$hidden = array("data_creazione",'status','data_ricezione',"data_aggiornamento","marchio","operatore");
	$radio = array('tipo_template');
	$text = array();
	$selectbtn = array("tipo_template",'attivo');	
	$calendario = array();	
	$file = array();
	$timer = array();
	
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
	if(in_array($who,$invisible)){ $type = 7; }
	if(in_array($who,$multi_selection)){ $type = 23; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$datePicker)){ $type = 11; }
	if(in_array($who,$ifYesText)){ $type = 13; }
	if(in_array($who,$hidden)){ $type = 5; }
		
	return $type;
	}
	
	
?>