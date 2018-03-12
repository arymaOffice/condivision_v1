<?php 
	// Variabili Modulo
	$tab_id = 99;
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
	$filtri = 1;
	$parent_id = (isset($_GET['parent_id'])) ? check(@$_GET['parent_id']) : 0;
	$element = ($parent_id > 1) ? GRD('fl_catalogo',$_GET['parent_id']) : '';

	if(!isset($_SESSION['anagrafica'])) $_SESSION['anagrafica'] = 1;
	if(isset($_GET['ANiD']) && $_SESSION['usertype'] < 2) $_SESSION['anagrafica'] = check($_GET['ANiD']);
	
	
	$parent_id_id = (isset($_GET['parent_id']) ) ?  check($_GET['parent_id']) : 0;
	
	$module_title = 'Prenotazioni';
   
   
    $module_menu = '';

	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("data_prenotazione DESC, orario DESC","id ASC");
	$ordine = $ordine_mod[0];	
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }

	$campi = "SHOW COLUMNS FROM `$tabella`";
	$risultato = mysql_query($campi, CONNECT);
	$x = 0;
	$campi = array(); //E' possibile aggiungere campi head tabella statici
	while ($riga = mysql_fetch_assoc($risultato)) 
	{					
	if(select_type($riga['Field']) != 5 ) { 
						$ordinamento[$x] = record_label($riga['Field'],CONNECT,1); 
						$ordine_mod[$x] = $riga['Field'];
						$campi[$riga['Field']] = record_label($riga['Field'],CONNECT,1); $x++; }
	}
	$basic_filters = array('tipo_prenotazione','stato_prenotazione','nominativo','email_nominativo','riferimento','telefono_prenotazione');



	/* RICERCA */
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND anagrafica_id = ".$_SESSION['anagrafica']." ";
	foreach($_GET as $chiave => $valore){
		$chiave = check($chiave);
		$valore = check($valore);
		if(isset($campi[$chiave]) && $chiave != 'data_a' && $chiave != 'data_da' && $chiave != 'start' && $chiave != 'a' && $chiave != 'action'){
			  if(select_type($chiave) != 5 && select_type($chiave) != 2 && select_type($chiave) != 19 && $chiave != 'id' && $valore != '') $tipologia_main .=  " AND LOWER($chiave) LIKE '%$valore%' ";
			  if(select_type($chiave) == 2 && $chiave != 'id' && $valore > -1) $tipologia_main .=   " AND $chiave = '$valore' ";
			  if(select_type($chiave) == 19 && $chiave != 'id' && $valore > -1) $tipologia_main .=  " AND $chiave = '$valore' ";
			}
		
	}

	if(isset($data_da) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_prenotazione`  BETWEEN '$data_da' AND '$data_a' ";

	
	if($_SESSION['anagrafica'] > 1) $tipologia_main .= ' AND anagrafica_id = '.$_SESSION['anagrafica'];	

	

	
	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $anagrafica_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale');
		
	function select_type($who){
	$textareas = array('commento','note'); 
	$select = array('stato_prenotazione','tipo_prenotazione','anagrafica_id');
	$disabled = array();
	$hidden = array("proprietario","id",'operatore','data_creazione','data_aggiornamento');
	$radio  = array('crea_nuova_pagina','pubblicato');	
	$selectbtn  = array();	
	$calendario = array('data_prenotazione');	
	$file = array("upfile");
	if($_SESSION['usertype'] > 1) array_push($hidden,'anagrafica_id');
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	
?>
