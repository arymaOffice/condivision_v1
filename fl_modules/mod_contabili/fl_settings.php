<?php 
	
	// Variabili Modulo
	$tab_id = 82;
	$tabella = $tables[$tab_id];

	$select = "*";
	$step = 50; 
	$formValidator = 1;
	$text_editor = 2;
	$jquery = 1;
	$filtri = 1;
	$calendar = 1;
	$fancybox = 1;

	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != '') { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	$proprietario_id = ($_SESSION['usertype'] > 1) ? $_SESSION['number'] : $proprietario_id ;

	if(isset($_GET['numero_settimana'])) $numero_settimana = check($_GET['numero_settimana']);
	if(isset($_GET['anno'])) $anno = check($_GET['anno']);

	$module_title = 'Settimane Contabili';
    $module_menu = '';
	$new_button = ($_SESSION['usertype'] > 1) ? '' : '<a href="./?action=11" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a>';

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
    $ordine_mod = array("numero_settimana DESC","data_creazione ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1  ";
	if($proprietario_id > 1) $tipologia_main .= " AND  proprietario = $proprietario_id";
	if(isset($numero_settimana)) $tipologia_main .= " AND  numero_settimana = $numero_settimana";		
	if(isset($anno)) $tipologia_main .= " AND  YEAR(data_creazione) = '$anno'";		
	
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$proprietario =  $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND tipo = 2 AND attivo = 1",'nominativo ASC');





	function select_type($who){		
	
	/* Gestione Oggetto Statica */	
	$textareas = array("note"); 
	$select = array('proprietario');
	$disabled = array("visite");
	$hidden = array('operatore','data_aggiornamento','data_creazione');
	$radio = array();
	$text = array();
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario");
	array_push($disabled,"numero_settimana","periodo_inzio","periodo_fine",'importo');
	} else { $radio  = array();	};
	$calendario = array('periodo_inizio','periodo_fine');	
	$file = array();
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