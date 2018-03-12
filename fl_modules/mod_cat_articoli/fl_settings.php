<?php 
	
	$modulo_uid = 33;
	$parametri_modulo = check_auth($modulo_uid); /* Nuova preconfigurazione dei moduli (da convertire in classe?) */
	// Variabili Modulo
	$module_title = $parametri_modulo['label'];
	$permesso    = $parametri_modulo['permesso'];
	$tab_id   = $tab_parent_id   = $parametri_modulo['tab_id'];
	$tabella     = $tables[$tab_id];
	$select      = "*";
	$ordine      = $parametri_modulo['ordine_predefinito']; 
	$step        = $parametri_modulo['risultati_pagina']; 
	$text_editor = $parametri_modulo['editor_wysiwyg'];
	$jquery      = $parametri_modulo['jquery'];
	$fancybox    = $parametri_modulo['fancybox'];
	$filtri      = $parametri_modulo['filtri'];
	$toggleOn    = $parametri_modulo['menu_aperto'];
	$calendar    = $parametri_modulo['calendari'];
	if($parametri_modulo['ricerca'] == 1) $searchbox = $parametri_modulo['placeholder_ricerca'];
	$checkRadioLabel = '<i class="fa fa-check-square"></i><i class="fa fa-square-o"></i>'; // Pulsante checkbox radio button Toggle apple

	/*Se esite questa array, la scheda modifica viene suddivisa all'occorenza del campo specificato o si possono aggiungere sotto schede */
	//$tab_div_labels = array('id'=>"Dettagli",'oggetto'=>"Richiesta",'../mod_dms/uploader.php?PiD='.base64_encode(FOLDER_ATTIVAZIONI).'&workflow_id='.$tab_id.'&NAme[]=Allegati&record_id=[*ID*]'=>'Allegati');

	
	$workflow_id = (isset($_GET['workflow_id'])) ? check(@$_GET['workflow_id']) : 0;
	$parent_id_id   = (isset($_GET['parent_id']))   ? check(@$_GET['parent_id'])   : 0;
	$account_id  = (isset($_GET['account_id']))  ? check(@$_GET['account_id'])  : 0;
	
	
	//$new_button = '';  //Solo se la funzione new richiede un link diverso da quello standard  
    $module_menu = '<li><a href="./">Categorie</a></li>'; //Menu del modulo
    if($parent_id_id  > 1) $new_button = '<a href="./mod_inserisci.php?id=1&'.check($_SERVER['QUERY_STRING']).'"&parent_id='.$parent_id_id.'"" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a>';


  	//Ricerca per data da sistemare con i filtri automatici con selezione 1 giorno o periodo da a
	if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	} else {
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	}

	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC");
	
	/*Crea i campi, ordinamemnti e modalista */
	$campi = "SHOW COLUMNS FROM `$tabella`";
	$risultato = mysql_query($campi, CONNECT);
	$x = 0;
	$campi = array();
	while ($riga = mysql_fetch_assoc($risultato)) 
	{			
		
	if(select_type($riga['Field']) != 5 ) { 
						$ordinamento[$x] = record_label($riga['Field'],CONNECT,1); 
						$ordine_mod[$x] = $riga['Field'];
						$campi[$riga['Field']] = record_label($riga['Field'],CONNECT,1); $x++; 
				}
	}
	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('titolo','descrizione','attivo');
	$ordine = $ordine_mod[0];	

	/* Strutturazione della query */
	$tipologia_main = "WHERE id != 1 AND parent_id < 2";
	if($parent_id_id != -1 && $parent_id_id > 1)  $tipologia_main = " WHERE id != 1 AND parent_id = $parent_id_id";


	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1) { $proprietario_id = check($_GET['proprietario']);  } else {    $proprietario_id = -1; }
    if(isset($proprietario_id) && @$proprietario_id != -1) {  $tipologia_main .= " AND proprietario = $proprietario_id "; } else if($_SESSION['usertype'] > 4 && !isset($_GET['proprietario'])) { $tipologia_main .= " AND proprietario = ".$_SESSION['number']." "; }

	foreach($_GET as $chiave => $valore){
			
			if(isset($campi[$chiave]) && $chiave != 'data_a' && $chiave != 'qualificati' && $chiave != 'data_da' && $chiave != 'start' && $chiave != 'a' && $chiave != 'action' && $chiave != 'cerca' && $chiave != 'ricerca_avanzata'){
			  if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9) && $chiave != 'id') {
				    if(!is_array($valore)) { 
						if(trim(@$valore) != '-1') $tipologia_main .=   " AND $chiave = '$valore' "; 
					} else {
				  		$valore = implode("','",$valore);
						$tipologia_main .= " AND $chiave IN ( '-1','$valore' )"; 
					}
			  } else if ($chiave != 'id' && trim($valore) != '' && trim($valore) != '-1') {  
				  $tipologia_main .=  " AND LOWER($chiave) LIKE '%$valore%' ";
			  }

			}
	}
	
	if(isset($_GET['cerca'])) { 
	$valore = strtolower(check($_GET['cerca']));
	if($valore != '') {
	$tipologia_main .= " AND (";
	$x = 0;
	foreach($campi as $chiave => $val){		
		$chiave = check($chiave);
		if(select_type($chiave) != 5 && select_type($chiave) != 2 && select_type($chiave) != 19 && $chiave != 'id' && $valore != '') { if($x > 0) { $tipologia_main .= ' OR '; } $tipologia_main .=  " LOWER($chiave) LIKE '%$valore%' "; $x++; }
	}
	$tipologia_main .= ")";
	}}

	if(isset($data_da) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	
	/* Filtri personalizzati */


	
	/* Inclusione classi e dati */	
	require('../../fl_core/dataset/array_statiche.php'); // Liste di valori statiche
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
	$data_set = new ARY_dataInterface();
    $parent_id = $data_set->data_retriever('fl_categorie','label','WHERE id > 1'); //Crea un array con i valori X2 della tabella X1
    $parent_id[0] = "Principale";
    if($parent_id_id > 1) $module_title = 'Sottocategorie per '.$parent_id[$parent_id_id];
	
	/*Funzione di merda per gestione dei campi da standardizzare in una classe e legare ad al DB o XML config*/	
	function select_type($who){
	
	$textareas = array('descrizione','commento','note'); 
	$select = array('parent_id','reparto_hd','tipologia_hd','stato','anagrafica_id','marchio');
	$disabled = array();
	$hidden = array('anagrafica_id','foto','workflow_id','account_id',"proprietario",'operatore','data_creazione','data_aggiornamento');
	$radio  = array('attivo');	
	$selectbtn  = array('stato_hd','priorita');
	$multi_selection  = array();	
	$calendario = array('data_chiusura');	
	$file = array("upfile");
	
	if($_SESSION['usertype'] > 1) array_push($hidden,'anagrafica_id'); // Eccezioni in base al tipo di utenza
	
	
	$type = 1; // Default input text
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$selectbtn)){ $type = 9; }
	if(in_array($who,$multi_selection)){ $type = 23; }

	if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	
?>
