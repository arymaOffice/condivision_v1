<?php 
	
	 /* Nuova preconfigurazione dei moduli (da convertire in classe?) */
	$modulo_uid = 30;
	$parametri_modulo = check_auth($modulo_uid);
	if(!isset($parametri_modulo['label'])) die('<h1>Modulo '.$modulo_uid.' non esistente!');
	
	// Variabili Modulo
	$module_title = $parametri_modulo['label'];
	$permesso    = $parametri_modulo['permesso'];
	$tab_id      = $parametri_modulo['tab_id'];
	$tabella     = $tables[$tab_id];
	$select      = "*";
	$ordine      = $parametri_modulo['ordine_predefinito']; 
	$step        = $parametri_modulo['risultati_pagina']; 
	//$text_editor = $parametri_modulo['editor_wysiwyg'];
	$jquery      = $parametri_modulo['jquery'];
	$fancybox    = $parametri_modulo['fancybox'];
	$filtri      = $parametri_modulo['filtri'];
	$toggleOn    = $parametri_modulo['menu_aperto'];
	$calendar    = $parametri_modulo['calendari'];
	if($parametri_modulo['ricerca'] == 1) $searchbox = $parametri_modulo['placeholder_ricerca'];
	$checkRadioLabel = '<i class="fa fa-check-square"></i><i class="fa fa-square-o"></i>'; // Pulsante checkbox radio button Toggle apple


	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $operatore_id = check($_GET['operatore']); } else {  $operatore_id = -1; }
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1) { $proprietario_id = check($_GET['proprietario']);  } else {    $proprietario_id = -1; }
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = 0; 
	 $data_a = 0; 
	 
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
	$basic_filters = array('marca','modello','alimentazione','tipologia_veicolo','pagamento_veicolo','targa');

	
	$tipologia_main = "WHERE id != 1 ";
	foreach($_GET as $field => $valore){
		$field = check($field);
		$chiave = select_type($field);
		$valore = check($valore);
		if(isset($campi[$field]) && $field != 'data_a' && $field != 'data_da' && $field != 'start' && $field != 'a' && $field != 'action'){
			  if($chiave != 7 && $chiave != 9 && $chiave != 5 && $chiave != 2 && $chiave != 19 && $chiave != 'id' && $valore != '') $tipologia_main .=  " AND LOWER($field) LIKE '%$valore%' ";
			  if(($chiave == 7 || $chiave == 19 || $chiave == 2 || $chiave == 9) && $chiave != 'id' && $valore > 1) $tipologia_main .=   " AND $field = '$valore' ";
			}
		
	}
	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('marca','marca');	
	$tipologia_main .= ricerca_avanzata('modello','modello');	
	$tipologia_main .= ')';
	}
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$tipologia_veicolo = $data_set->get_items_key("tipo_interesse");	
	$metodo_pagamento = $pagamento_veicolo = $data_set->get_items_key("pagamento_vettura");	
	$workflow_id = array(48=>'Interni',16=>'Esterni');
	$data_acquisto = array(); for($i=1995;$i<date('Y')+1;$i++) $data_acquisto[$i] = $i;
	$anno_immatricolazione = array(); for($i=1950;$i<date('Y');$i++) $anno_immatricolazione[$i] = $i;
	$assegnato_a = $proprietario;
	$status_vendita = array('Primo Contatto','Richiamare','Non interessato','Venduto','Incassato','Polizza Attiva');


	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array("note"); 
	$select = array('metodo_pagamento','assegnato_a','metodo_di_pagamento',"alimentazione","proprietario",'tipologia_veicolo','pagamento_veicolo');
	$select_text = array();
	$disabled = array("data_creazione");
	$hidden = array('veicolo_id',"data_creazione","data_aggiornamento","marchio","operatore");
	$radio = array();
	$text = array();
	$calendario = array('data_contatto','data_acquisto','data_consegna','anno_immatricolazione','data_saldo');	
	$file = array();
	$checkbox = array('status_vendita');
	$invisible = array('ultima_posizione','parent_id','workflow_id');

	$type = 1;
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$select_text)) { $type = 12; }	
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$invisible)){ $type = 7; }

	
	return $type;
	}
	
	
   $module_menu = '';
	$module_title = 'Ford Protect';

?>