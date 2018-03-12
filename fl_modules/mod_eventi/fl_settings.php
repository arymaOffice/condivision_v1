<?php 
	
	// Variabili Modulo
	$active = 1;
	$sezione_tab = 1;
	$tab_id = 6;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$sezione_id = -1;
	$jorel = 0;
	//$text_editor = 2;
	$jquery = 1;
	$searchbox = "Cerca nome...";
	$fancybox = 1;
	$calendar = 1;
	$documentazione_auto = 8;
	$dateTimePicker = 1;
	$filtri = 1; 
	$export = 1;
  	if(!isset($_GET['lead_id'])) $new_button = '<a href="../mod_leads/?action=4" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a>';
  	if(isset($_GET['action']) && isset($_GET['action']) == 24) $new_button = '';

		
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != '') { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	if($_SESSION['usertype'] != 0 && !isset($_GET['proprietario'])) $proprietario_id = $_SESSION['number'];
	if(isset($_GET['lead_id'])) { $lead_id = check($_GET['lead_id']);  } else {    $lead_id = 0; }
	

	

	if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	} else {
	 $data_da_t = date('1/m/Y'); 
	 $data_a_t = date('t/m/Y'); 
	}
	$anno = substr($data_da_t,-4,4);

	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("data_evento ASC","potential_rel ASC","operatore ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/*Impostazione automatica da tabella */
	$campi = gcolums($tabella); //Ritorna i campi della tabella
	$tipologia_main = gwhere($campi,'WHERE id != 1 ','','id','data_evento',0,9592000);//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
	
	if(isset($_GET['tipo_evento_s'])) { 
	$tipo_evento_var = $_GET['tipo_evento_s'];
	$tipo_evento_ids = implode("','",$tipo_evento_var);
	$tipologia_main .= " AND tipo_evento IN ( '-1','$tipo_evento_ids' )"; 
	}
	if(!isset($_GET['stato_evento']) || check($_GET['stato_evento']) < 0) $tipologia_main .= " AND stato_evento != 4 "; 


	$basic_filters = array('centro_di_ricavo','ambienti','periodo_evento','stato_evento');
	if(isset($_GET['action']) && check($_GET['action']) != 24) $basic_filters[] = 'titolo_ricorrenza';

	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();


	$promoter = $proprietario;

	$location_evento = $data_set->data_retriever('fl_sedi','sede',"WHERE id != 1",'sede ASC');
	$anagrafica_cliente = $anagrafica_cliente2 = $data_set->data_retriever('fl_anagrafica','cognome,nome',"WHERE id != 1 AND tipo_profilo = 2 ",'id DESC,cognome ASC');
	$meeting_location[0] = 'Tutte';
	$proprietario['-1'] = "Tutti";
	$periodo_evento = $data_set->get_items_key("tipo_interesse");
	$tipo_servizio_evento = $data_set->get_items_key("tipo_servizio_evento");
	$codici_ambiente = $ambienti = $data_set->get_items_key("ambienti");
	if(defined('MULTI_LOCATION')) $ambienti =  $data_set->data_retriever('fl_ambienti','nome_ambiente',"WHERE id != 1",'priority ASC,tipo_ambiente ASC, id ASC');
	if(defined('MULTI_LOCATION')) $ambienti_disponibilta =  $data_set->data_retriever('fl_ambienti','nome_ambiente',"WHERE id != 1 AND gestione_disponibilita = 1",'priority ASC,tipo_ambiente ASC, id ASC');
	if(defined('MULTI_LOCATION')) $codici_ambiente =  $data_set->data_retriever('fl_ambienti','codice_ambiente',"WHERE id != 1",'priority ASC,tipo_ambiente ASC, id ASC');
	if(defined('MULTI_LOCATION')) {
	$ambiente_principale =  $data_set->data_retriever('fl_ambienti','nome_ambiente',"WHERE id != 1",'tipo_ambiente ASC');
	unset($ambiente_principale[0]);
	unset($ambiente_principale[1]);	
	$notturno = $ambiente_1 = $ambiente_2 = $ambiente_principale;
	}

	if(!isset($stato_evento)) $stato_evento = array('Bozza','Attesa Contratto','Confermato','Archiviato','Annullato');
	unset($ambienti[0]);
	unset($ambienti[1]);
	
	unset($ambienti_disponibilta[0]);
	unset($ambienti_disponibilta[1]);	
	

	$tipo_evento  = $data_set->data_retriever('fl_cg_res','codice,label','WHERE  attivo = 1 AND parent_id = 0 AND tipo_voce = 1','id ASC');
	$centro_di_ricavo  = $data_set->data_retriever('fl_cg_res','codice,label','WHERE  attivo = 1 AND parent_id > 0 AND tipo_voce = 1','parent_id ASC, id ASC');
	$colors  = $data_set->data_retriever('fl_cg_res','colore','WHERE attivo = 1 AND id > 1 AND parent_id = 0 AND tipo_voce = 1 ','id ASC');


    $module_title = "Agenda Eventi";
	$module_title .= ' '.$anno;
   	if(isset($_GET['action']) && check($_GET['action']) == 24) $module_title = "Planning Eventi ";

	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array(); 
	$select = array('notturno','ambiente_principale','ambiente_1','ambiente_2','tipo_servizio_evento','centro_di_ricavo','preventivo_collegato','tipo_evento','periodo_evento','location_evento',"mansione","paese","proprietario");
	$disabled = array();
	$hidden = array('anagrafica_cliente','anagrafica_cliente2',"colore","descrizione",'altro','evento_id','customer_id',"data_creazione",'proprietario','marchio','contract_id',"data_arrived",'lead_id','is_customer',"data_aggiornamento","marchio","ip","operatore",'condizioni_aggiuntive','estremi_acconto','ricevuta_numero','importo_ricevuta','note');
	$radio = array('all_day','multievento');
	$text = array();
	$calendario = array();	
	$checkbox = array('stato_evento','fiori','gruppo_musicale');
	$file = array();
	$timer = array();
	$touch = array();
	$datePicker = array('data_fine_evento','data_evento','start_date','end_date');
	$multi_selection = array("ambienti");	
	$ifYesText = array("segnaposti", "bomboniere", "dolci", "ospiti_serali");
	//if(defined('MULTI_AMBIENTE')) $hidden[] = 'ambienti';
	/*	
	if(isset($_GET['id']) && check($_GET['id']) == 1) { array_push($hidden,'anagrafica_cliente','anagrafica_cliente2','condizioni_aggiuntive','estremi_acconto'); }
	*/
	//if(isset($_GET['id']) && check($_GET['id']) > 1) $disabled = array('numero_adulti','numero_bambini','nuomero_operatori');

	if(isset($_GET['tipo_evento']) && $_GET['tipo_evento'] != 9  && $_GET['tipo_evento'] != 5) array_push($hidden,'wedding_planner','recapiti_wedding_planner','foto_e_auguri','fotografo','recapiti_fotografo','bomboniere','ospiti_serali','segnaposti','dolci');
  

	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$timer)){ $type = 7; }
	if(in_array($who,$multi_selection)){ $type = 23; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$datePicker)){ $type = 11; }
	if(in_array($who,$ifYesText)){ $type = 13; }
	if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	$module_menu = '';
	if(isset($_GET['ambienti']) && check($_GET['ambienti']) > 0) $module_title .= ' - '.$ambienti[check($_GET['ambienti'])];

	$menu = array('Calendario'=>'../mod_eventi/?','Planning'=>'../mod_eventi/?action=24&closed','Conteggio Ospiti'=>'../mod_eventi/?action=24&closed&totaleOspiti');
	if(defined('MULTI_AMBIENTE')) $menu['Disponibilità Ambienti'] = '../mod_eventi/mod_seleziona_disponibilita_ambienti.php?closed&';

	$b = (!isset($_GET['b'])) ? '' : check($_GET['b']);
	//if( ($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 3) && ($proprietario_id < 1 || isset($_GET['intro']))) { 
	foreach($menu as $label => $link){ // Recursione Indici di Categoria
				$selected = ($b  == $label) ? " class=\"selected\"" : "";
			    $module_menu .= "<li $selected><a href=\"$link&b=$label\">".ucfirst($label)."</a></li>\r\n"; 
	}//}




	
?>
