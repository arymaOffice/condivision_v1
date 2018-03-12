<?php 
	// Variabili Modulo
	$active = 7;
	$tab_id = 34;
	$tabella = $tables[$tab_id];
	$select = "* ";
	$step = 100; 
	$sezione_id = -1;
	$jorel = 0;
	$text_editor = 2;
	$jquery = 1;
	$fancybox = 1;
	$searchbox = 'Ricerca richiesta';
	$calendar = 1;
	$filtri = 1;
	$documentazione_auto = 18;
	$tabs_div = 0;

	$tab_div_labels = array('id'=>"Richiesta",'approvato'=>"Intervento");

	$module_title = 'Registro Manutenzioni ';
    $module_menu = '
  	   <li class=""><a href="./">Richieste  <span class="subcolor">Manutenzione </span></a>      </li>
 
     ';
		
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != ''  && $_SESSION['usertype'] == 0) { 
		$proprietario_id = check($_GET['proprietario']); 
	} else {  $proprietario_id = -1; }
	if( $_SESSION['usertype'] > 1) $proprietario_id = $_SESSION['number']; 



	if(isset($_GET['approvato'])) { $approvato_id = check($_GET['approvato']);} else { $approvato_id = -1; }

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('2014-m-1'); 
	 $data_a = date('Y-m-d',time()+386400); 
	 
	 $data_da_t = date('1/m/2014'); 
	 $data_a_t = date('d/m/Y'); 
	 }
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("data_creazione DESC","data_creazione DESC","categoria_mnt ASC","proprietario DESC");
	$ordine = $ordine_mod[0];	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";

	if(isset($proprietario_id) && @$proprietario_id != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND proprietario = $proprietario_id ";	 } 
	if(isset($proprietario_id) && @$proprietario_id != -1 && $_SESSION['usertype'] > 0) {	$tipologia_main = "WHERE id != 1 AND proprietario = ".$_SESSION['number']." ";	 }
	if($approvato_id != -1) $tipologia_main .= "AND approvato = $approvato_id";	 	
	if(isset($data_da)) $tipologia_main .= " AND data_creazione BETWEEN '$data_da' AND '$data_a' ";	 	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('oggetto','oggetto');
	$tipologia_main .= ricerca_avanzata('note','note');
	$tipologia_main .= ")";
	}

	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/proprietario.php');
	include('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $fornitore = $data_set->data_retriever('fl_anagrafica','ragione_sociale','WHERE id > 1 AND tipo_profilo > 0');
	$categoria_mnt = $data_set->get_items_key("categoria_mnt");
	
		
	function select_type($who){
	$textareas = array("note","descrizione"); 
	$select = array("proprietario","categoria_mnt","lang","percorso");
	$disabled = array("operatore");
	$hidden = array('urgenza',"operatore","status","data_aggiornamento");
	$radio  = array('eseguito');	
	$checkbox = array('approvato');	
	if($_SESSION['usertype'] > 0) array_push($hidden,"proprietario","approvato");
	$calendario = array('data_intervento','data_richiesta','data_preventivo');	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }	
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$hidden)){ $type = 5; }

	return $type;
	}
	
	
?>
