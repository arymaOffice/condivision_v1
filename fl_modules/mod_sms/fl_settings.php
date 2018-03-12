<?php 
	
	// Variabili Modulo
	$active = 2;
	$sezione_tab = 1;
	$tab_id = 31;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$sezione_id = -1;
	$jorel = 0;
	$jquery = 1;
	$fancybox = 1;
	$calendar = 1;
	$documentazione_auto = 8;
	
  	 if(isset($_GET['data_da']) && check(@$_GET['data_da']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); 
	 $data_da_t = check($_GET['data_da']); 
	 } else {
	 $data_da = date('Y-m-d',time()); 	 
	 $data_da_t = date('d/m/Y',time()); 
	 }
	 
	 $module_menu = '';
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("data_creazione DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_avanzata('body','body');	
	}
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	require_once('../../fl_core/class/ARY_dataInterface.class.php');
    $data_retrive = new ARY_dataInterface();


	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array("messaggio"); 
	$select = array('proprietario');
	$disabled = array('from');
	$hidden = array("data_creazione",'status','data_ricezione',"data_aggiornamento","marchio","operatore");
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