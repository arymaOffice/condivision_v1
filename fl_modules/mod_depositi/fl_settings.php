<?php 
	
		// Variabili Modulo
	$module_uid = 3;
	check_auth($module_uid);
	

	$sezione_tab = 1;
	$tab_id = 43;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = (isset($_SESSION['step'])) ? $_SESSION['step'] : 100;
	$sezione_id = -1;
	$jorel = 0;
	$text_editor = 2;
	$jquery = 1;
	$calendar = 1;
	$searchbox = 'Cerca..';
	$fancybox = 1;
	$documentazione_auto = 8;
	
  
		
	if(isset($_GET['new'])){ new_inserimento($tabella,ROOT.$cp_admin."fl_modules/mod_depositi"); }
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['status_pagamento']) && check(@$_GET['status_pagamento']) >= 0) { $status_pagamento_id = check($_GET['status_pagamento']);  } else {    $status_pagamento_id = -1; }
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != '') { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	if(isset($_GET['causale']) && check(@$_GET['causale']) != -1 && check(@$_GET['causale']) != '') { $causale_id = check($_GET['causale']); } else {  $causale_id = -1; }
	if(isset($_GET['metodo_di_pagamento']) && check(@$_GET['metodo_di_pagamento'])  != -1) { $metodo_di_pagamento_id = check($_GET['metodo_di_pagamento']);  } else {    $metodo_di_pagamento_id = -1; }

	$module_menu = '
	<ul>
    <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_depositi/">Depositi  <span class="subcolor"> e Prelievi</span></a>      </li>
	    <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_depositi/?estratto">Estratto <span class="subcolor"> Conto </span></a>      </li>';

	//if($_SESSION['usertype'] < 2) $module_menu .= '<li><a href="'.ROOT.$cp_admin.'fl_modules/mod_depositi/?action=11&causale=84" class="">Nuovo Deposito</a></li>';
  // 	if($_SESSION['usertype'] < 2) $module_menu .= '<li><a href="'.ROOT.$cp_admin.'fl_modules/mod_depositi/?action=11&causale=86" class="">Nuovo Storno</a></li>';
 $module_menu .=  '</ul>';
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
 	 
	 $ora_da_id = (isset($_GET['ora_da'])) ? check($_GET['ora_da']) :  '00:00:00';
	 $ora_a_id = (isset($_GET['ora_a'])) ?  check($_GET['ora_a']) : '23:59:59';
	 
	 $data_da  .= ' '.$ora_da_id;
	 $data_a  .= ' '.$ora_a_id;
	 
	 
	 } else {
	
	 $ora_da_id = '00:00:00';
	 $ora_a_id = '23:59:59';
	 
	 $data_da = date('Y-m-d '.$ora_da_id.':00',time()); 
	 $data_a = date('Y-m-d '.$ora_a_id.':59',time()); 
	 
	 $data_da_t = date('d/m/Y',time()); 
	 $data_a_t = date('d/m/Y');

 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC","user ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 AND (causale = 84 OR causale = 86 OR causale = 88)";
	if(isset($_GET['estratto']))  { 	check_auth(4); $tipologia_main = "WHERE id != 1"; }
	if(isset($causale_id) && @$causale_id != -1) {  $tipologia_main = "WHERE id > 1 AND causale = $causale_id ";	 }
	if(isset($estrattoconto)) $tipologia_main = "WHERE id != 1";
	if($_SESSION['usertype'] > 1) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($proprietario_id) && @$proprietario_id != -1) {  $tipologia_main .= " AND proprietario = $proprietario_id ";	 }
	if(isset($metodo_di_pagamento_id) && @$metodo_di_pagamento_id != -1) {  $tipologia_main .= " AND metodo_di_pagamento = $metodo_di_pagamento_id ";	 }
	if(isset($status_pagamento_id) && @$status_pagamento_id >= 0) {  $tipologia_main .= " AND status_pagamento = $status_pagamento_id ";	 }
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_avanzata('estremi_di_pagamento','estremi_di_pagamento');	
	}
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $causale = $data_set->data_get_items(82);
    $rif_operazione = $data_set->data_retriever('fl_sottoprodotti','label');
	$rif_operazione[0] = '';
	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array('note'); 
	$select = array("metodo_di_pagamento");
	$select_disabled = array("causale","proprietario");
	$disabled = array('data_valuta','data_operazione',"visite");
	$hidden = array('rif_operazione',"proprietario","status_pagamento","data_creazione","data_aggiornamento","marchio","ip","operatore");
	$radio = array();
	$text = array();
	if(@!is_numeric($_GET['action']) || $_SESSION['usertype'] > 0){
	array_push($hidden,"status_pagamento","attivo","identificato","marchio");
	array_push($text,"note"); } else { $radio  = array("attivo");	};
	if(!isset($all)) { if(check(@$_GET['causale']) == 84 || check(@$_GET['causale']) == 88){ 	array_push($hidden,"avere"); } else { array_push($hidden,"dare"); }}

	$calendario = array();	
	$file = array();
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$select_disabled)){ $type = 19; }
	
	return $type;
	}
	
	
?>