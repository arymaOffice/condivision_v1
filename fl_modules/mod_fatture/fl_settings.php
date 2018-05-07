<?php 
	
	$module_uid = 7;
	check_auth($module_uid);
	$sezione_tab = 1;
	$tab_id = 59;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = (isset($_SESSION['step'])) ? $_SESSION['step'] : 24;
	$sezione_id = -1;
	$jorel = 0;
	$text_editor = 2;
	$jquery = 1;
	$calendar = 1;
	$searchbox = 'Cerca..';
	$fancybox = 1;
	$documentazione_auto = 8;
	$filtri = 1;
	
  
		
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != '') { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	if($_SESSION['usertype'] > 0) $proprietario_id = $_SESSION['number'];
	
	$module_menu = '
	<ul>
    <li class=""><a href="./">Fatture <span class="subcolor"></span></a>      </li>';
    $module_menu .=  '</ul>';
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
 	 
	 if(isset($_GET['ora_da'])){
	 
	 $ora_da_id = check($_GET['ora_da']);
	 $ora_a_id = check($_GET['ora_a']);
	 $data_da  .= ' '.$ora_da_id;
	 $data_a  .= ' '.$ora_a_id;
	 }
	 
	 } else {
	
	 $ora_da_id = '00:00:00';
	 $ora_a_id = '23:59:59';
	 
	 $data_da = date('Y-m-1 '.$ora_da_id.':00',time()); 
	 $data_a = date('Y-m-d '.$ora_a_id.':59',time()); 
	 
	 $data_da_t = date('1/m/Y',time()); 
	 $data_a_t = date('d/m/Y');

 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC","user ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id > 0 ";
	if($_SESSION['usertype'] > 1) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_documento`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($proprietario_id) && @$proprietario_id != -1) {  $tipologia_main .= " AND proprietario = $proprietario_id ";	 }
	if(isset($metodo_di_pagamento_id) && @$metodo_di_pagamento_id != -1) {  $tipologia_main .= " AND metodo_di_pagamento = $metodo_di_pagamento_id ";	 }
	if(isset($status_pagamento_id) && @$status_pagamento_id >= 0) {  $tipologia_main .= " AND status_pagamento = $status_pagamento_id ";	 }
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_avanzata('informazioni_cliente','informazioni_cliente');	
	}
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/data_manager/array_statiche.php');
	include('../../fl_core/category/proprietario.php');
	include('../../fl_core/category/provincia.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $causale = $data_set->data_get_items(82);
    $rif_operazione = $data_set->data_retriever('fl_sottoprodotti','label');
	$rif_operazione[0] = '';
	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array('informazioni_cliente','mittente','destinatario','note'); 
	$select = array("metodo_di_pagamento");
	$select_disabled = array("causale","proprietario");
	$disabled = array("visite");
	$hidden = array("rif_contratto","totale_prodotti","data_creazione","data_aggiornamento","marchio","operatore");
	$radio = array("pagato");
	$text = array();
	$calendario = array('data_documento');	
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