<?php

	// Variabili Modulo
	$tab_id = 35;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 20; 
	$sezione_id = -1;
	$jorel = 0;
	$text_editor = 2;
	$jquery = 1;
	$fancybox = 1;
	$searchbox = 'Cerca...';
	$calendar = 1;
	$documentazione_auto = 18;
	if(isset($_GET['new'])){ new_inserimento($tabella,ROOT.$cp_admin."fl_modules/mod_attivita/mod_inserisci.php"); }

	$module_title = 'Attivita ';
    $module_menu = '
  	   <li class=""><a href="./?todo">Attivit&aacute; <span class="subcolor">da svolgere </span></a>      </li>
	   <li class=""><a href="./?assigned">Attivit&aacute; <span class="subcolor">assegnate </span></a>      </li>
	';

	$operatore_id = $_SESSION['number'];// (isset($_GET['operatore']) && check(@$_GET['operatore']) > 0 && $_SESSION['usertype'] == 0) ? check($_GET['operatore']) : $_SESSION['number']; 
	$proprietario_id = 0; //(isset($_GET['proprietario']) && check(@$_GET['proprietario']) > 0 && $_SESSION['usertype'] == 0) ? check($_GET['proprietario']) : -1;
	if(isset($_GET['assigned'])) { $proprietario_id = $_SESSION['number']; $operatore_id = 0; }
	if(isset($_GET['todo'])) { $todo = 1; $proprietario_id = 0; $operatore_id = $_SESSION['number']; }
    	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 }

	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("proprietario ASC","id DESC","oggetto ASC","operatore ASC");
	$ordine = $ordine_mod[1];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1 ";
	if(isset($data_da_t)) 	$tipologia_main .= " AND (data_creazione BETWEEN '$data_da' AND '$data_a')";
	if(isset($operatore_id) && @$operatore_id > 0) {  $tipologia_main .= " AND operatore = $operatore_id ";	 }
	if(isset($proprietario_id) && @$proprietario_id > 0) {  $tipologia_main .= " AND proprietario = $proprietario_id ";	 }
	if(isset($todo) && @$todo > 0) {  $tipologia_main .= " AND fatto = 0 ";	 }

	if(@$where != "") $tipologia_main .= $where;
	

	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('oggetto','e');
	$tipologia_main .= ricerca_avanzata('descrizione','descrizione');
	$tipologia_main .= ")";
	}
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	$operatore = $proprietario;
	$operatore[0] = 'Tutti';
	
	function select_type($who){
	/* Gestione Oggetto Statica */
	
	$textareas = array("descrizione"); 
	$select = array("operatore");
	$disabled = array();
	$hidden = array("status_segnalazione","proprietario","data_creazione","data_aggiornamento");
	$radio = array('urgente','fatto','importante');
	$text = array();
	$calendario = array('data_avvio','data_conclusione');	
	$file = array("upfile");
	

	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	
	return $type;
	}
	
	
?>