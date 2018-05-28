<?php 
	// Variabili Modulo
	$active = 6;
	$tab_id = 77;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 50; 
	$sezione = 0;
	$jorel = 0;
	$accordion_menu = 1;
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 0;


	if(isset($_GET['all'])) { unset( $_SESSION['workflow_id']); unset($_SESSION['parent_id']); }
	
	if(!isset($_SESSION['parent_id'])) $_SESSION['parent_id'] = 1;
	if(isset($_GET['PiD'])) $_SESSION['parent_id'] = check(base64_decode($_GET['PiD']));

	if(!isset($_SESSION['workflow_id'])) $_SESSION['workflow_id'] = 1;
	if(isset($_GET['WiD'])) $_SESSION['workflow_id'] = check(base64_decode($_GET['WiD']));


    $module_menu = '
	<ul>';
  	  
	  if($_SESSION['parent_id'] != 1)   $module_menu .= ' <li class=""><a href="./mod_inserisci.php?id=1&WiD='.base64_encode($_SESSION['workflow_id']).'&PiD='.base64_encode($_SESSION['parent_id']).'" class="create_new"><i class="fa fa-plus-circle"></i> Nuovo Questionario</a></li>';
	  
	  $module_menu .= '</ul>';
	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("titolo ASC","parent_id ASC","id ASC");
	$ordine = $ordine_mod[2];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('titolo','titolo');
	if($_SESSION['parent_id'] != 1) $tipologia_main .= " AND parent_id = ".$_SESSION['parent_id'];
	if($_SESSION['workflow_id'] != 1) $tipologia_main .= " AND workflow_id = ".$_SESSION['workflow_id'];
	
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;
	
	

	
	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$modalita_esecuzione = array('Singola','Ripetibile Subito','Ripetibile dopo 1 ora');


	function select_type($who){
	$textareas = array(); 
	$select = array();
	$disabled = array();
	$hidden = array('workflow_id','parent_id',"id",'proprietario','data_creazione');
	$radio  = array();	
	$checkbox = array('modalita_esecuzione');	
	$multi_selection = array();	
	$calendario = array();	
	$selectbtn  = array();	
	$file = array();

	$type = 1;

	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$selectbtn)){ $type = 22; }
	if(in_array($who,$file)){ $type = 18; }

	if(in_array($who,$multi_selection)){ $type = 23; }
		
	return $type;
	}
	
	
?>
