<?php 
	
	// Variabili Modulo
	$active = 1;
	$sezione_tab = 1;
	$tab_id = 36;
	$tabella = $tables[$tab_id];
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


	$module_title = 'Area ';
    $module_menu = '
	<ul>
	   <li><a href="./mod_inserisci.php?id=1" class="create_new"><i class="fa fa-plus-circle"></i> Nuovo </a></li>
     
	 
     </ul>';
		
	if(isset($_GET['tipo_profilo']) && check(@$_GET['tipo_profilo']) != 0) { $tipo_profilo_id = check($_GET['tipo_profilo']);  } else {    $tipo_profilo_id = 0; }
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-d',time()-999204800); 
	 $data_a = date('Y-m-d',time()+86400); 
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("nominativo ASC","data_creazione ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($userid) && @$userid != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND proprietario = $userid ";	 }
	if(isset($tipo_profilo_id) && @$tipo_profilo_id != 0) {  $tipologia_main .= " AND tipo_profilo = $tipo_profilo_id ";	 }
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('nominativo','nominativo');
	$tipologia_main .= ricerca_avanzata('email','email');
	$tipologia_main .= ricerca_avanzata('telefono','telefono');
	$tipologia_main .= ricerca_avanzata('cellulare','cellulare');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$account_id = $proprietario;
	$tipo_profilo = array('2'=>'Cliente','3'=>'Fornitore');
	$mandatory = array("id");
	$tipo_contatto = array('Comops','Seller');
	$anagrafica_rel = $data_set->data_retriever('fl_anagrafica','ragione_sociale',"WHERE id != 1 ");
	
	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("note"); 
	$select = array('','anagrafica_rel',"proprietario");
	$disabled = array("visite");
	$hidden = array('data_creazione','data_aggiornamento','marchio','operatore','tipo_contatto','proprietario',"relation");
	$radio = array();
	$text = array();
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario","attivo","status_anagrafica","marchio","account_affiliato");
	} else { $radio  = array("attivo");	};
	$calendario = array();	
	$file = array("upfile");
	$checkbox = array();
    if(defined('CAMPI_INATTIVI')) array_push($hidden,'centro_di_costo','pagamenti_f24','pin_cassetto_fiscale','data_scadenza_pec'); // Campi disabilitati per cliente governati da file customer.php
	
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