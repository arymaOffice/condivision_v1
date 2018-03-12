<?php 
	
	$modulo_uid = 39;
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
	$folder = DMS_PUBLIC.'ricettario/';


	$tab_div_labels = array('id'=>"Ricetta",'preparazione'=>"Preparazione e Servizio");


	$workflow_id = (isset($_GET['workflow_id'])) ? check(@$_GET['workflow_id']) : 0;
	$parent_id   = (isset($_GET['parent_id']))   ? check(@$_GET['parent_id'])   : 0;
	$account_id  = (isset($_GET['account_id']))  ? check(@$_GET['account_id'])  : 0;
	$portata_id  = (isset($_GET['portata']))  ? check(@$_GET['portata'])  : -1;
	


	
	//$module_title = ''; //Titolo del modulo
	//$new_button = '';  //Solo se la funzione new richiede un link diverso da quello standard  
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

	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('attivo','portata','categoria_ricetta','famiglia_ricetta','nome');
	$ordine_mod = array("id DESC"); // Tipologie di ordinamento disponobili 
	$ordine = $ordine_mod[0];
  
 
	/* Filtri personalizzati */
	//if(isset($_GET['qualificati'])) { $qualificati_id = check($_GET['qualificati']);  } else {    $qualificati_id = -1; }

	// Filtro manuale da sessione
	//if($_SESSION['anagrafica'] > 1) $tipologia_main .= ' AND anagrafica_id = '.$_SESSION['anagrafica'];	


	
	/* Inclusione classi e dati */	
	require_once('../../fl_core/dataset/array_statiche.php'); // Liste di valori statiche
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
	$data_set = new ARY_dataInterface();
	$tipo_materia = $data_set->get_items_key("tipo_materia");//Crea un array con gli elementi figli dell'elemento con tag X1	
	$semilavorato_id  = $data_set->data_retriever('fl_ricettario','nome',"WHERE id != 1 AND portata = 8 ",'nome ASC');
	$categoria_materia = $data_set->get_items_key("categoria_materia");//Crea un array con gli elementi figli dell'elemento con tag X1	
	$categoria_ricetta = $data_set->get_items_key("categoria_ricetta");//Crea un array con gli elementi figli dell'elemento con tag X1	
	$famiglia_ricetta = $data_set->data_retriever('fl_cg_res','codice,label','WHERE  attivo = 1 AND parent_id = 0 AND tipo_voce = 1','id ASC');
	$tipo_servizio_evento = $data_set->get_items_key("tipo_servizio_evento");
	$tipo_ricetta = array('Ricetta','Ricetta Componibile','Torta Componibile','Buffet','Set Componibile');

	/*Funzione di merda per gestione dei campi da standardizzare in una classe e legare ad al DB o XML config*/	
	function select_type($who){
	
	$textareas = array('note','preparazione','cottura','presentazione','servizio'); 
	$select = array('tipo_ricetta','portata','tipo_materia','categoria_materia','unita_di_misura','tipo_servizio_evento');
	$select_text = array();
	$disabled = array('revisione');
	$hidden = array('anagrafica_id','marchio','operatore','data_creazione','data_aggiornamento');
	$radio  = array('attivo');	
	$selectbtn  = array('categoria_ricetta');
	$multi_selection  = array('famiglia_ricetta');	
	$calendario = array();	
	$file = array();
	
	
	$type = 1; // Default input text
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$select_text)) { $type = 12; }
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
	
	
    foreach($portata as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($portata_id == $valores) ? " class=\"selected\"" : "";
			$action = (isset($_GET['action'])) ? '&action='.check($_GET['action']) : "";
			$module_menu .= "<li $selected><a href=\"./?portata=$valores$action\">".ucfirst($label)."</a></li>\r\n"; 
		 }
	    //$module_menu .= '<li><a href="../../fl_app/MenuElegance/">Componi Menu</a></li>'; 

?>
