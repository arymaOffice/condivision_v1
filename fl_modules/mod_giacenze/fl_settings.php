<?php 
	
	$modulo_uid = 37;
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


	$workflow_id = (isset($_GET['workflow_id'])) ? check(@$_GET['workflow_id']) : 0;
	$parent_id   = (isset($_GET['parent_id']))   ? check(@$_GET['parent_id'])   : 0;
	$account_id  = (isset($_GET['account_id']))  ? check(@$_GET['account_id'])  : 0;
	


	
	$module_title = 'Giacenze'; //Titolo del modulo
	$new_button = '';  //Solo se la funzione new richiede un link diverso da quello standard  
    $module_menu = '
    <li><a href="../mod_materieprime/">Anagrafica</a></li>
    <li><a href="../mod_giacenze/">Giacenza</a></li>
    <li><a href="../mod_giacenze/intro.php">Movimenti</a></li>'; //Menu del modulo


	if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	} else {
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	}

	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('descrizione','tipo_materia','categoria_materia','codice_articolo');
	$ordine_mod = array("id DESC"); // Tipologie di ordinamento disponobili 
	$ordine = $ordine_mod[0];

	/*Impostazione automatica da tabella */
	$campi = gcolums($tabella); //Ritorna i campi della tabella
	$tipologia_main = gwhere($campi,'WHERE id != 1 ');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica

	

  
 
	/* Filtri personalizzati */


	
	/* Inclusione classi e dati */	
	require_once('../../fl_core/dataset/array_statiche.php'); // Liste di valori statiche
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
	$data_set = new ARY_dataInterface();
    $fornitore = $data_set->data_retriever('fl_anagrafica','ragione_sociale'); //Crea un array con i valori X2 della tabella X1
	$tipo_materia = $data_set->get_items_key("tipo_materia");//Crea un array con gli elementi figli dell'elemento con tag X1	
	$categoria_materia = $data_set->data_retriever('fl_categorie','label','WHERE id > 1 AND workflow_id = 115','id ASC');
	$tipo_movimentazione = array('DDT - Entrata Merce','DDT - Uscita Merce','Reso cliente','Reso Fornitore','Inventario','Carico di Produzione');

	/*Funzione di merda per gestione dei campi da standardizzare in una classe e legare ad al DB o XML config*/	
	function select_type($who){
	
	$textareas = array(); 
	$select = array('tipo_materia','categoria_materia');
	$select_text = array();
	$disabled = array();
	$hidden = array('anagrafica_id','marchio','operatore','data_creazione','data_aggiornamento');
	$radio  = array('attivo');	
	$selectbtn  = array('unita_di_misura');
	$multi_selection  = array();	
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
	
	
?>
