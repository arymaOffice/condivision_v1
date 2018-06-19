<?php 
	
	// Variabili Modulo da gestire con DB 
	$tab_id = 38;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 50; 
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 0;
	$filtri = 1;
	//$searchbox = 'Cerca...';
	$tab_div_labels = array('id'=>"Dettagli",'priorita'=>"Richiesta",'../mod_dms/uploader.php?PiD='.base64_encode(FOLDER_ATTIVAZIONI).'&workflow_id='.$tab_id.'&NAme[]=Allegati&record_id=[*ID*]'=>'Allegati');

	
	$workflow_id = (isset($_GET['workflow_id'])) ? check(@$_GET['workflow_id']) : 0;
	$parent_id   = (isset($_GET['parent_id']))   ? check(@$_GET['parent_id'])   : 0;
	$account_id  = (isset($_GET['account_id']))  ? check(@$_GET['account_id'])  : 0;
	
	if(!isset($_SESSION['anagrafica'])) $_SESSION['anagrafica'] = 1;
	if(isset($_GET['ANiD']) && $_SESSION['usertype'] < 2) $_SESSION['anagrafica'] = check($_GET['ANiD']);

	
	$module_title = 'Madre di tutti i moduli'; //Titolo del modulo
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

	
	/*Crea i campi, ordinamemnti e modalista */
	$campi = "SHOW COLUMNS FROM `$tabella`";
	$risultato = mysql_query($campi, CONNECT);
	$x = 0;
	$campi = array(); //E' possibile aggiungere campi head tabella statici
	while ($riga = mysql_fetch_assoc($risultato)) 
	{					
	    if(select_type($riga['Field']) != 5 ) { 
		$ordinamento[$x] = record_label($riga['Field'],CONNECT,1); 
		$ordine_mod[$x] = $riga['Field'];
		$campi[$riga['Field']] = record_label($riga['Field'],CONNECT,1); 
		$x++; 
		}
	}
	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = $ordine_mod; //array('solo_campi_che_vuoi');
	$ordine = $ordine_mod[0];	

	/* Strutturazione della query */
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND account_id = ".$_SESSION['number']; // Restrige selezione per utenza maggiore di 0
	foreach($_GET as $chiave => $valore){
		$chiave = check($chiave);
		$valore = check($valore);
		if(isset($campi[$chiave]) && $chiave != 'id' && $chiave != 'data_a' && $chiave != 'data_da' && $chiave != 'start' && $chiave != 'a' && $chiave != 'action'){
		if(select_type($chiave) == 1 && select_type($chiave) == 3  && $valore != '') $tipologia_main .=  " AND LOWER($chiave) LIKE '%$valore%' ";
		if(select_type($chiave) == 2 && $valore > -1)  $tipologia_main .=   " AND $chiave = '$valore' ";
		if(select_type($chiave) == 19 && $valore > -1) $tipologia_main .=  " AND $chiave = '$valore' ";
		}
		
	}

	if(isset($data_da) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_prenotazione`  BETWEEN '$data_da' AND '$data_a' ";

	// Filtro manuale da sessione
	if($_SESSION['anagrafica'] > 1) $tipologia_main .= ' AND anagrafica_id = '.$_SESSION['anagrafica'];	


	
	/* Inclusione classi e dati */	
	require('../../fl_core/dataset/array_statiche.php'); // Liste di valori statiche
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
	$data_set = new ARY_dataInterface();
    $anagrafica_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale'); //Crea un array con i valori X2 della tabella X1
	$tipologia_hd = $data_set->get_items_key("tipologia_hd");//Crea un array con gli elementi figli dell'elemento con tag X1	
	$reparto_hd = $data_set->get_items_key("reparto_hd");
	$stato_hd = array('Aperto','Attesa cliente','Chiuso'); // Valori manuali (sconsigliato)
	
	/*Funzione di merda per gestione dei campi da standardizzare in una classe e legare ad al DB o XML config*/	
	function select_type($who){
	
	$textareas = array('commento','note'); 
	$select = array('stato_hd','reparto_hd','tipologia_hd','stato','anagrafica_id','marchio');
	$disabled = array();
	$hidden = array('workflow_id','account_id',"proprietario",'operatore','data_creazione','data_aggiornamento');
	$radio  = array('attivo');	
	$selectbtn  = array('priorita');
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
