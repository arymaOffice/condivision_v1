<?php 
	
	// Variabili Modulo
	$active = 1;
	$sezione_tab = 1;
	$tab_id = 90;
	
	$select = "*";
	$step = 2000; 
	$sezione_id = -1;
	$jorel = 0;
	$accordion_menu = 1;
	$formValidator = 1;
	$text_editor = 2;
	$jquery = 1;
	$filtri = 1;
	$calendar = 1;
	$fancybox = 1;
	$searchbox = 'Cerca..';
	$documentazione_auto = 18;
	$tabella = $tables[$tab_id];
	$tab_div_labels = array('id'=>"Dati Attività",'../mod_import/mod_user.php?id=[*ID*]'=>'Upload CSV');

	 $action = (isset($_GET['action'])) ? check($_GET['action']) : 0;

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-d H:i',time()-999204800); 
	 $data_a = date('Y-m-d H:i',time()+86400); 
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	$proprietario_id = (isset($_GET['proprietario'])) ? check($_GET['proprietario']) : -1;
	$responsabili = array(8,12,26,16);
	if($_SESSION['usertype'] > 0 && !in_array($_SESSION['profilo_funzione'],$responsabili)) $proprietario_id = $_SESSION['number'];

	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("data_inizio DESC","data_creazione ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1  ";
	//if(isset($data_da_t)) 	$tipologia_main .= " AND (data_fine BETWEEN '$data_da' AND '$data_a')";
	if(isset($_GET['incorso'])) 	$tipologia_main .= " AND data_inizio <= '".date('Y-m-d')."' AND data_fine > '".date('Y-m-d')."' ";
	if(isset($_GET['concluse'])) 	$tipologia_main .= " AND data_fine < '".date('Y-m-d')."' ";
	if(isset($_GET['future'])) 	$tipologia_main .= " AND data_inizio > '".date('Y-m-d')."' ";
	if(isset($_GET['action']) && @check($_GET['action']) == 24) $tipologia_main .= " AND data_inizio <= '".date('Y-m-d')."' ";
    $menu_vendite = array('0','10','11','14','15','19','20');
    


	if($_SESSION['usertype'] > 0 && !isset($_GET['action']) && !isset($_GET['all']) && !in_array($_SESSION['profilo_funzione'],$menu_vendite) ) $tipologia_main .= " AND (processo_id < 2 OR processo_id = ".$_SESSION['processo_id'].' )';
	$sediFiltro = ($_SESSION['sedi_id'] != 0) ? ' AND id IN('.$_SESSION['sedi_id'].') ' : '';


	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('descrizione','descrizione');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$campagna_id = $data_set->data_retriever('fl_campagne','descrizione',"WHERE id != 1",'id DESC');
	$tipo_campagna = $data_set->get_items_key('tipo_campagna');
	$processo_id = $data_set->data_retriever('fl_processi','processo',"WHERE id != 1 ",'processo ASC');
	$processo_id[1] = 'Tutti';
	$profilo_funzione = $data_set->data_retriever('fl_profili_funzione','funzione',"WHERE id != 1 ",'funzione ASC');
	$gruppo_id =  $data_set->data_retriever('fl_gruppi','nome_gruppo',"WHERE id != 1 ",'nome_gruppo ASC');
	$operatoribdc =  $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND tipo = 3   ",'nominativo ASC');
	$operatoridgt = $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND  (tipo = 5)  ",'nominativo ASC');
	$operatoribdc[0]  = $proprietario[0] = $operatoridgt[0] = 'Non Assegnato'; 
    $supervisor_id = array_replace($operatoribdc, $operatoridgt);
	$tipo_campagna = array('Lead Generation','Upselling','QoS');
	$assegnazione_automatica = array('Manuale','Automatica','A Basket');
	
	$sedi_id = $data_set->data_retriever('fl_sedi','sede,citta',"WHERE id != 1 $sediFiltro",'sede ASC');
	$sedi_id[0] = 'Tutte';

	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("descrizione"); 
	$select = array('assegnazione_automatica','supervisor_id','gruppo_id','campagna_id','tipo_campagna','stato','provincia','anagrafica_id');
	$disabled = array("visite");
	$hidden = array('processo_id','profilo_funzione','operatore','data_aggiornamento','data_creazione');
	$radio = array("");
	$text = array();
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario","attivo","status_anagrafica","marchio","account_affiliato");
	} else { $radio  = array("attivo");	};
	$calendario = array('data_inizio','data_fine','data_emissione','data_nascita');	
	$file = array("upfile");
	$checkbox = array();
    if(defined('CAMPI_INATTIVI')) array_push($hidden,'centro_di_costo','pagamenti_f24','pin_cassetto_fiscale','data_scadenza_pec'); // Campi disabilitati per cliente governati da file customer.php
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
    if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	$module_title = 'Attivit&agrave; delle Campagne ';
	if(!isset($_GET['all'])) $module_title .= ' '.@$operatoribdc[$proprietario_id].' ';
	if($_SESSION['processo_id'] > 1) $module_title .= "<span class=\"msg blue\">".$processo_id[$_SESSION['processo_id']]."</span> ";
	if( $_SESSION['usertype'] > 0 ) $new_button = '';

	if(isset($_GET['tipoCampagna'])) $action .= '&tipoCampagna='.check($_GET['tipoCampagna']);

    $module_menu = '
	<li><a href="./?action='.$action.'">Tutte</a></li>
	<li><a href="./?incorso&action='.$action.'">In corso</a></li>
	<li><a href="./?future&action='.$action.'" >Future</a></li>	
	<li><a href="./?concluse&action='.$action.'" >Concluse</a></li>';

?>