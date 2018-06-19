<?php 
	
	// Variabili Modulo
	$workflow_id = $tab_id = 47;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$text_editor = 2;
	$jquery = 1;
	$calendar = 1;
	$filtri = 1;
	$searchbox = 'Cerca ricevuta..';
	$fancybox = 1; 
  	$module_title = "Ricevute Pagamento";
	$css_iframe = ' width: 100%; border: none; height:400px; ' ;
	$dmsFolder = base64_encode(FOLDER_RICEVUTE_PAGAMENTO);
		
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['status_pagamento']) && check(@$_GET['status_pagamento']) != 0) { $status_pagamento_id = check($_GET['status_pagamento']);  } else {    $status_pagamento_id = 0; }
	

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	 } else {
	 $data_da = date('Y-m-d',time()-86400); 
	 $data_a = date('Y-m-d',time()+86400); 
	 $data_da_t = date('d/m/Y',time()-86400); 
	 $data_a_t = date('d/m/Y'); 
	 }
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC","user_associato ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 AND status_pagamento > 0";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(isset($data_da_t) && @$status_pagamento_id != 1) 	$tipologia_main .= " AND DATE(data_creazione) BETWEEN '$data_da' AND '$data_a' ";
	if(isset($userid) && @$userid != -1) {  $tipologia_main .= " AND proprietario = '$userid' ";	 }
	if(isset($status_pagamento_id) && @$status_pagamento_id != 0) {  $tipologia_main .= " AND status_pagamento = $status_pagamento_id ";	 }
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('user_associato','user_associato');
	$tipologia_main .= ricerca_avanzata('note','note');
	$tipologia_main .= ")";
	}
	
	
	
		/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');


	function select_type($who){
	
	$textareas = array("note"); 
	$select = array("marchio","status_pagamento");
	$disabled = array("visite");
	$hidden = array('is_app',"data_creazione","operatore","data_aggiornamento");
	$radio = array();
	$text = array();
	$checkbox = array("status_pagamento");

	if($_SESSION['usertype'] > 0){
	array_push($hidden,"status_pagamento","proprietario","attivo","identificato","marchio");
	array_push($text,"note"); } else { $select  = array("proprietario","marchio");	};

	$calendario = array('data_pagamento');	
	$file = array("upfile");
	
	
	
	$type = 1;
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$hidden)){ $type = 5; }
	return $type;
	}	

	$new_button = '<a href="./mod_inserisci.php?id=1" class="" style="color: gray"> <i class="fa fa-plus-circle"></i></a>';

?>