<?php 
	
	// Variabili Modulo
	$active = 7;
	$tab_id = 38;
	$modulo_uid = 16;
	$tabella = $tables[$tab_id];	
	$select = "*";
	$step = 50;
	$text_editor = 2;
	$jquery = 1;
	$fancybox = 1;
	$searchbox = 'Ricerca nota';
	$calendar = 1;
	$tabs_div = 0;
	$tab_div_labels = array('id'=>"Richiesta",'../mod_documentazione/mod_user.php?mode=out&amp;operatore='.$_SESSION['number'].'&modulo=0&cat=9&contenuto=[*ID*]'=>"Files Allegati");
	
	if(isset($_GET['new'])){ new_inserimento($tabella,'./'); }

	$module_title = 'Help Desk';
    $module_menu = '
	<ul>
  	   <li class=""><a href="./">Richieste <span class="subcolor">Help Desk </span></a>      </li>
	   <li><a href="./mod_inserisci.php?id=1">Nuova Richiesta <i class="fa fa-plus-circle"></i></a></li>
     </ul>';
    $module_menu = '
	<ul>
  	   <li class=""><a href="#" onclick="display_toggle(\'#menu_modulo\');"><i class="fa fa-th-large"></i></a></li>
     </ul>';

	
	/* Parametri Filtri */
	if(isset($_GET['operatore']) && $_SESSION['usertype'] == 0 && check($_GET['operatore']) != 0) { $proprietario_id = check($_GET['operatore']); } 
	if(isset($_GET['status_assistenza']) && check(@$_GET['status_assistenza']) != 0) { $status_assistenza_id = check($_GET['status_assistenza']);  } else {  $status_assistenza_id = 0; }

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-1 H:i'); 
	 $data_a = date('Y-m-d H:i',time()+86400); 
	 
	 $data_da_t = date('1/m/Y'); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
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
	if(isset($_GET['data_da']) && !isset($_GET['cerca'])) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($status_assistenza_id) &&  $status_assistenza_id != 0) $tipologia_main .= " AND status_assistenza = $status_assistenza_id";	
	
	
	/* Filtro Ricerca */
	if(isset($_GET['cerca'])) {
	$vars = "cerca=".check($_GET['cerca'])."&";
	$tipologia_main .= "AND (id = 0 ".ricerca_avanzata('oggetto','oggetto');
    $tipologia_main .= ricerca_avanzata('descrizione','descrizione')." )";
	}
	

	/* Libraries Loading */	
	include('../../fl_core/dataset/proprietario.php');
		include('../../fl_core/dataset/array_statiche.php');


	

	
		
	function select_type($who){
	$textareas = array("note","descrizione"); 
	$select = array("marchio","proprietario","status_assistenza");
	$disabled = array("data_chiusura");
	$title = array();
	$radio  = array();	
	$calendario = array();	
	$hidden = array("operatore","data_creazione","letto","jorel","urgenza","cat","id","codice","type","ip","data_aggiornamento");
	
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario","attivo","operatore","marchio");
	if(@$_GET['action'] != 8){array_push($hidden,"status_assistenza");  }
	array_push($textareas,"note"); 
	} else { 
	if(@$_GET['action'] != 8){ array_push($hidden,"status_assistenza");  }
	$radio  = array("attivo");	};

	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$title)){ $type = 25; }	
	if(in_array($who,$hidden)){ $type = 5; }	
	
	return $type;
	}
	
	
?>
