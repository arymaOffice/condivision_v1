<?php 
	
	// Variabili Modulo
	$active = 1;
	$sezione_tab = 1;
	$tab_id = 90;
	
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



	$module_title = 'Attivit&agrave; Lead Generation';
    $module_menu = ' <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_items/">Liste Parametri </a></li>
    <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_campagne/">Canali CRM</a></li>
    <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_campagne_attivita/">Attività CRM</a></li>';



		

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
    $ordine_mod = array("descrizione ASC","data_creazione ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1  ";
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('descrizione','descrizione');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$campagna_id = $data_set->data_retriever('fl_campagne','descrizione',"WHERE id != 1",'id DESC');
	$tipo_campagna = $data_set->get_items_key('tipo_campagna');
	
	
	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("descrizione"); 
	$select = array('campagna_id','tipo_campagna','stato','provincia','anagrafica_id');
	$disabled = array("visite");
	$hidden = array('operatore','data_aggiornamento','data_creazione');
	$radio = array("catalogo");
	$text = array();
	$calendario = array('data_inizio_attivita','data_fine_attivita');	
	$file = array("upfile");
	$checkbox = array();
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
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