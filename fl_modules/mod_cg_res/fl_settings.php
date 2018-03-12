<?php 
	
	// Variabili Modulo da gestire con DB 
	$tab_id = 105;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 50; 
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 0;
	$filtri = 1;
	$searchbox = 'Cerca...';

	
	/* DA USARE SE CLIENTE GESTISCE + SOTTOCLIENTI
	$workflow_id = (isset($_GET['workflow_id'])) ? check(@$_GET['workflow_id']) : 0;
	$parent_id   = (isset($_GET['parent_id']))   ? check(@$_GET['parent_id'])   : 0;
	$account_id  = (isset($_GET['account_id']))  ? check(@$_GET['account_id'])  : 0;
	
	if(!isset($_SESSION['anagrafica'])) $_SESSION['anagrafica'] = 1;
	if(isset($_GET['ANiD']) && $_SESSION['usertype'] < 2) $_SESSION['anagrafica'] = check($_GET['ANiD']);
	*/
	
    $module_title = 'Centri di Responsabilit&agrave;'; //Titolo del modulo
	//$new_button = '';  //Solo se la funzione new richiede un link diverso da quello standard  
    $module_menu = ''; //Menu del modulo



	
	/*Impostazione automatica da tabella */
	$campi = gcolums($tabella); //Ritorna i campi della tabella
	$tipologia_main = gwhere($campi,'WHERE id != 1 ','');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
    if(isset($_GET['potential_id']))  $tipologia_main .= ' AND potential_id = '.check($_GET['potential_id']).' '; 
    if(!isset($_GET['parent_id']))  $tipologia_main .= ' AND parent_id = 0 '; 

	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('tipo_voce','titolo','codice','parent_id');
	$ordine_mod = array("id ASC, parent_id ASC"); // Tipologie di ordinamento disponobili 
	$ordine = $ordine_mod[0];
	


	
	/* Inclusione classi e dati */	
	require('../../fl_core/dataset/array_statiche.php'); // Liste di valori statiche
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
	$data_set = new ARY_dataInterface();
	$parent_id = $data_set->data_retriever($tabella,'label',"WHERE id != 1 AND parent_id < 2",'id ASC');
	$parent_id[0] = "Nessuno";

	$tipo_voce = array('Costo','Ricavo','Investimento','Profitto'); // Valori manuali (sconsigliato)
	
	/*Funzione di merda per gestione dei campi da standardizzare in una classe e legare ad al DB o XML config*/	
	function select_type($who){
	
	$textareas = array(); 
	$select = array('parent_id');
	$disabled = array();
	$hidden = array();
	$radio  = array('attivo');	
	$selectbtn  = array('tipo_voce');
	$multi_selection  = array();	
	$calendario = array();	
	$file = array();
	
	//if($_SESSION['usertype'] > 1) array_push($hidden,'anagrafica_id'); // Eccezioni in base al tipo di utenza
	
	
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


   foreach($tipo_voce as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@isset($_GET['tipo_voce']) && check($_GET['tipo_voce']) == $valores) ? " class=\"selected\"" : "";
			$action = (isset($_GET['action'])) ? '&action='.check($_GET['action']) : "";
			$module_menu .= "<li $selected><a href=\"./?tipo_voce=$valores$action\">".ucfirst($label)."</a></li>\r\n"; 
		 }	
	
?>
