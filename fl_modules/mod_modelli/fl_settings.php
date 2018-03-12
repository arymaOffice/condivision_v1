<?php 
	
	$modulo_uid = 34;
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

	
	$module_title .= ''; //Titolo del modulo
	//$new_button = '';  //Solo se la funzione new richiede un link diverso da quello standard  
    $module_menu = ''; //Menu del modulo


  	//Ricerca per data da sistemare con i filtri automatici con selezione 1 giorno o periodo da a
	if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	} else {
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	}

	
	/*Impostazione automatica da tabella */
	$campi = gcolums($tabella); //Ritorna i campi della tabella
	$tipologia_main = gwhere($campi,'WHERE id != 1 AND attivo = 1 ','');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
 
	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('titolo','contenuto','tipo_modello');
	$ordine_mod = array("id DESC"); // Tipologie di ordinamento disponobili 
	$ordine = $ordine_mod[0];

	
	
	/* Inclusione classi e dati */	
	require('../../fl_core/dataset/array_statiche.php'); // Liste di valori statiche
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
	$data_set = new ARY_dataInterface();
	//$categoria_modello = $data_set->get_items_key("interessato_a");
	$categoria_modello  = $data_set->data_retriever('fl_cg_res','codice,label','WHERE parent_id = 0 AND tipo_voce = 1');
	/*Funzione di merda per gestione dei campi da standardizzare in una classe e legare ad al DB o XML config*/	
	function select_type($who){
	
	$textareas = array('condizioni_generali','informative','condizioni_pagamento','contenuto'); 
	$select = array('tipo_modello','categoria_modello','marchio');
	$disabled = array();
	$hidden = array('operatore','data_creazione','data_aggiornamento');
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
	
	$module_menu = '';
   foreach($tipo_modello as $valores => $label){ // Recursione Indici di Categoria
		$selected = (isset($_GET['tipo_modello']) && check($_GET['tipo_modello']) == $valores) ? " class=\"selected\"" : "";
		 $module_menu .= "<li $selected><a href=\"./?tipo_modello=$valores\">".ucfirst($label)."</a></li>\r\n"; 
	}	
	
?>
