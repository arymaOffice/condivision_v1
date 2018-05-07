<?php 
	
	$module_uid = 6;
	check_auth($module_uid);
	$tab_id = 20;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$filtri = 1;
	$module_menu = '
	 
  	  
      <li class=""><a href="./">Registro Saldi</a>      </li>
	   <li class=""><a href="./?action=17">Archivio Operazioni</a>      </li>
     
	
	';
  
		
	if(isset($_GET['new'])){ new_inserimento($tabella,ROOT.$cp_admin."fl_modules/mod_commissioni/"); }
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['saldo']) && check(@$_GET['saldo']) != -1 && check(@$_GET['saldo']) != '') { $saldo_id = check($_GET['saldo']); } else {  $saldo_id = -1; }

	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); 
	 $data_da_t = check($_GET['data_da']); 
	
	 } else {
	 $data_da = date('Y-m-d',time()-86400); 
	 $data_da_t = date('d/m/Y',time()-86400); 
	 }
	 
	 	
  	 if(isset($_GET['data_a']) && check($_GET['data_a']) != "") { 
	 $data_a = convert_data($_GET['data_a'],1); 
	 $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_a = date('Y-m-d',time()-86400); 
	 $data_a_t = date('d/m/Y',time()-86400); 
	 }

	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("data DESC","data ASC","proprietario ASC","marchio DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id > 0";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data`  = '$data_da'";
	if(isset($userid) && @$userid != -1) {  $tipologia_main .= " AND proprietario = $userid ";	 }
	if(isset($saldo_id) && @$saldo_id != -1) {  $tipologia_main .= ($saldo_id == 0) ? " AND `saldo_iniziale` >= 0  " : " AND `saldo_iniziale` < 0  ";	 }

	
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/data_manager/array_statiche.php');
	include('../../fl_core/category/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $causale = $data_set->data_get_items(82);


	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array(); 
	$select = array("proprietario","status_pagamento","causale","metodo_di_pagamento");
	$select_disabled = array("proprietario");
	$disabled = array();
	$hidden = array("marchio");
	$radio = array();
	$text = array();
	$calendario = array('data');	
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