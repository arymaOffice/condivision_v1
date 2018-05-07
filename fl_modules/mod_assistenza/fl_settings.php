<?php 
	
	$module_uid = 16;
	check_auth($module_uid);
	$sezione_tab = 1;
	$tab_id = 38;
	$tabella = $tables[$tab_id];	
	$select = "*";
	$accordion_menu = 1;
	$text_editor = 2;
	$step = 50; 
	$calendar = 1;
	$operatore_check = 1;
	if(isset($_GET['new'])){ new_inserimento($tabella); }
	
	
	/* Parametri Filtri */
	if(isset($_GET['operatore']) && $_SESSION['usertype'] == 0 && check($_GET['operatore']) != 0) { $proprietario_id = check($_GET['operatore']); } 
	if(isset($_GET['status_assistenza']) && check(@$_GET['status_assistenza']) != 0) { $status_assistenza_id = check($_GET['status_assistenza']);  } else {  $status_assistenza_id = 0; }
	if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	$data_da = convert_data($_GET['data_da']); $data_a = convert_data($_GET['data_a']); 
	$data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']);	
	} else {
	$data_da = convert_data(date('d/m/Y',time()-604800)); $data_a = convert_data(date('d/m/Y')); 
	$data_da_t = date('d/m/Y',time()-604800); $data_a_t = date('d/m/Y'); 
	}
	$data_a += 86399;

	/* Classi Ordine */
    $ordine_mod = array("data_aggiornamento DESC","operatore ASC","urgenza DESC","data_chiusura DESC","data_chiusura DESC","status_assistenza ASC","priority DESC");
	$ordine = $ordine_mod[0];	
	$tipologia = 0;

	
	/* Filtri */
	if(isset($proprietario_id) && $_SESSION['usertype'] == 0) {	
	$tipologia_main = "WHERE id != 1 AND proprietario = $proprietario_id AND jorel = 0   AND status_assistenza != 0";
	} else {
	if($_SESSION['usertype'] == 0) { 
	$tipologia_main = "WHERE id != 1 AND jorel = 0 AND status_assistenza != 0";	
	} else{
	$tipologia_main = "WHERE id != 1 AND jorel = 0 AND proprietario = ".$_SESSION['number']."  AND status_assistenza != 0";	
	}}
	if(isset($data_da_t) && $status_assistenza_id != 1) $tipologia_main .= " AND (data_creazione BETWEEN $data_da AND $data_a ) ";
	if(isset($status_assistenza_id) &&  $status_assistenza_id != 0) $tipologia_main .= " AND status_assistenza = $status_assistenza_id";	
	
	
	/* Filtro Ricerca */
	if(isset($_GET['cerca'])) {
	$vars = "cerca=".check($_GET['cerca'])."&";
	$tipologia_main .= "AND (id = 0 ".ricerca_avanzata('oggetto','oggetto');
    $tipologia_main .= ricerca_avanzata('descrizione','descrizione')." )";
	}
	

	/* Libraries Loading */	
	include('../../fl_core/category/sezione.php');
	include('../../fl_core/category/proprietario.php');
	include('../../fl_core/category/cms.php');

	

	
		
	function select_type($who){
	$textareas = array("note","descrizione"); 
	$select = array("marchio","proprietario","status_assistenza","approvato","lang","percorso");
	$disabled = array("operatore");
	$title = array("data_creazione");
	$radio  = array();	
	$calendario = array();	
	$cal_data = array('data_creazione');	
	$hidden = array("letto","data_chiusura","jorel","urgenza","cat","id","codice","type","ip","data_aggiornamento");
	
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario","attivo","operatore","marchio");
	if($_GET['action'] != 8){array_push($hidden,"status_assistenza");  }
	array_push($textareas,"note"); 
	} else { 
	if($_GET['action'] != 8){ array_push($hidden,"status_assistenza");  }
	$radio  = array("attivo");	};

	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$title)){ $type = 25; }	
	if(in_array($who,$cal_data)){ $type = 26; }	
	if(in_array($who,$hidden)){ $type = 5; }	
	
	return $type;
	}
	
	
?>
