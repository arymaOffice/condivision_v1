<?php 
	
	$modulo_uid = 40;
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

	$evento_filter = (isset($_GET['evento_id'])) ? ' AND evento_id = '.check($_GET['evento_id']) : ' AND evento_id = 0';
	

	
	//$module_title = 'Madre di tutti i moduli'; //Titolo del modulo
	//$new_button = '';  //Solo se la funzione new richiede un link diverso da quello standard  
    $module_menu = '<li><a href="../mod_eventi/?action=17&b=Lista Eventi">Lista Eventi</a></li>'; //Menu del modulo

	
	/*Impostazione automatica da tabella */
	$campi = gcolums($tabella); //Ritorna i campi della tabella
	$tipologia_main = gwhere($campi,'WHERE id != 1 '.$evento_filter,'');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
    

	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('evento_id','descrizione_menu');
	$ordine_mod = array("id DESC"); // Tipologie di ordinamento disponobili 
  
 
	/* Filtri personalizzati */

	// Filtro manuale da sessione

	
	/* Inclusione classi e dati */	
	require('../../fl_core/dataset/array_statiche.php'); // Liste di valori statiche
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
	$data_set = new ARY_dataInterface();
    $evento_id = $data_set->data_retriever($tables[6],'titolo_ricorrenza'); //Crea un array con i valori X2 della tabella X1
	$confermato = array('Attivo','Bozza');

	/*Funzione di merda per gestione dei campi da standardizzare in una classe e legare ad al DB o XML config*/	
	function select_type($who){
	
	$textareas = array(); 
	$select = array();
	$disabled = array();
	$hidden = array('evento_id',"proprietario",'operatore','data_creazione','data_aggiornamento');
	$radio  = array('confermato');	
	$selectbtn  = array();
	$multi_selection  = array();	
	$calendario = array();	
	$file = array();
	//if(!defined('MULTI_MENU')) $hidden[] = 'descrizione_menu';
	
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
	
	if(isset($_GET['evento_id'])) $module_title .= ' - '.$evento_id[check($_GET['evento_id'])];
?>
