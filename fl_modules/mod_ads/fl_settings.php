<?php 
	
	$modulo_uid = 49;
	$parametri_modulo = check_auth($modulo_uid); /* Nuova preconfigurazione dei moduli (da convertire in classe?) */
	// Variabili Modulo
	$module_title = $parametri_modulo['label'];
	$permesso    = $parametri_modulo['permesso'];
	$tab_id   = $tab_parent_id   = $parametri_modulo['tab_id'];
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

	/*Se esite questa array, la scheda modifica viene suddivisa all'occorenza del campo specificato o si possono aggiungere sotto schede */
	//$tab_div_labels = array('id'=>"Dettagli",'oggetto'=>"Richiesta",'../mod_dms/uploader.php?PiD='.base64_encode(FOLDER_ATTIVAZIONI).'&workflow_id='.$tab_id.'&NAme[]=Allegati&record_id=[*ID*]'=>'Allegati');

	
	$workflow_id = (isset($_GET['workflow_id'])) ? check(@$_GET['workflow_id']) : 0;
	$parent_id   = (isset($_GET['parent_id']))   ? check(@$_GET['parent_id'])   : 0;
	$account_id  = (isset($_GET['account_id']))  ? check(@$_GET['account_id'])  : 0;
	
	if(!isset($_SESSION['anagrafica'])) $_SESSION['anagrafica'] = 1;
	if(isset($_GET['ANiD']) && $_SESSION['usertype'] < 2) $_SESSION['anagrafica'] = check($_GET['ANiD']);


	$new_button = '<a href="../mod_dms/?c=NQ==&a=ads" style="color:gray;" ><i class="fa fa-plus-circle"></i></a>';  //Solo se la funzione new richiede un link diverso da quello standard  
    $module_menu = ''; //Menu del modulo


	if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	} else {
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	}

	
	/*Impostazione automatica da tabella */
	$campi = gcolums($tabella); //Ritorna i campi della tabella
	$tipologia_main = gwhere($campi,'WHERE id != 1 ','');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
    if(isset($_GET['potential_id']))  $tipologia_main .= ' AND potential_id = '.check($_GET['potential_id']).' '; 
	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('categoria_conto','descrizione_conto');
	$ordine_mod = array("id DESC"); // Tipologie di ordinamento disponobili 
	$ordine = $ordine_mod[0];
  
 
	/* Filtri personalizzati */
	if(isset($_GET['qualificati'])) { $qualificati_id = check($_GET['qualificati']);  } else {    $qualificati_id = -1; }
	if(isset($qualificati_id) && @$qualificati_id == 1) {  $tipologia_main .= " AND (email != '' AND telefono != '' AND nome != '') ";	 }
	if(isset($qualificati_id) && @$qualificati_id == 0) {  $tipologia_main .= " AND (email = '' OR telefono = '' OR nome = '' ) ";	 }



	
	/* Inclusione classi e dati */	
	require('../../fl_core/dataset/array_statiche.php'); // Liste di valori statiche
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
	$data_set = new ARY_dataInterface();
	$anagrafica_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale'); //Crea un array con i valori X2 della tabella X1
	
	$link_cat = $data_set->data_retriever('fl_link_cat','descrizione'); //Crea un array con i valori X2 della tabella X1
	$cartelle_pubblicita = $data_set->data_retriever('fl_dms','label',' WHERE parent_id = \'5\' AND file = "" '); //Crea un array con i valori X2 della tabella X1
	$tipologia_hd = $data_set->get_items_key("tipologia_hd");//Crea un array con gli elementi figli dell'elemento con tag X1	
	$reparto_hd = $data_set->get_items_key("reparto_hd");
	$stato_hd = array('Aperto','Attesa cliente','Chiuso'); // Valori manuali (sconsigliato)
	
	/*Funzione di merda per gestione dei campi da standardizzare in una classe e legare ad al DB o XML config*/	
	function select_type($who){
	
	$textareas = array('messaggio','commento','note'); 
	$select = array('reparto_hd','tipologia_hd','stato','anagrafica_id','marchio');
	$disabled = array();
	$hidden = array('workflow_id','account_id',"proprietario",'operatore','data_creazione','data_aggiornamento');
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
