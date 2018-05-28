<?php 
	
	// Variabili Modulo
	
	$tab_id = 95;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$jorel = 0;
	$jquery = 1;
	$fancybox = 1;
	$calendar = 1;
	 
	$module_title = "Template";
	$module_menu = '';
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1";
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_avanzata('messaggio','messaggio');	
	}
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	require_once('../../fl_core/class/ARY_dataInterface.class.php');
    $data_set = new ARY_dataInterface();
    $mittente = $data_set->get_items_key("mittente");	
    unset($mittente[0]);
	$tag_sms = $data_set->get_items_key("tag_sms");
	$tipo_template = array('Generico','SMS','Email');
	
	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array('messaggio'); 
	$select = array('mittente');
	$disabled = array('from');
	$hidden = array("data_creazione",'tipo_template','status','data_ricezione',"data_aggiornamento","marchio","operatore");
	$radio = array();
	$text = array();
	$calendario = array();	
	$file = array();
	$timer = array();
	
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
	
	return $type;
	}
	
	
?>