<?php 
	
	$modulo_uid = 35;
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
	$new_button = '';

	$tab_div_labels = array('id'=>"Preventivo",
	'note'=>"Note e Condizioni",'mod_richieste.php?reQiD=[*ID*]'=>'Follow up');
	
		

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-d H:i',time()-999204800); 
	 $data_a = date('Y-m-d H:i',time()+86400); 
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	if(!isset($_SESSION['proprietario_id'])) $_SESSION['proprietario_id'] = 0;
	if(isset($_GET['cmy'])) $_SESSION['proprietario_id'] = check(base64_decode($_GET['cmy']));
	$proprietario_id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : $_SESSION['proprietario_id'];

	
	/*Impostazione automatica da tabella */
	$campi = gcolums($tabella); //Ritorna i campi della tabella
	$tipologia_main = gwhere($campi,'WHERE id != 1 ','','id','data_creazione',31536000,0);//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
    if(isset($_GET['potential_id']))  $tipologia_main .= ' AND potential_id = '.check($_GET['potential_id']).' '; 
	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('note','testo_preventivo','tipo_preventivo');
	$ordine_mod = array("id DESC"); // Tipologie di ordinamento disponobili 
	$ordine = $ordine_mod[0];
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	//$tipo_preventivo = $data_set->get_items_key("interessato_a");	
	$tipo_preventivo  = $data_set->data_retriever('fl_cg_res','codice,label','WHERE parent_id = 0 AND tipo_voce = 1');
	$status_preventivo = array('Attesa Esito','Valuta','Rifiuto','Vendita','A Concorrenza');
	$mandatory = array("id");
	$supervisore = $proprietario;
	$tipo_richiesta = array('Call','Email','Follow up','Rifiutato','Concorrenza','Venduta','');	
	$produzione = $data_set->data_retriever('fl_sedi','sede,citta',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'sede ASC');
	$produzione[0] = 'Tutte';

	$cliente_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale',"WHERE id != 1 AND tipo_profilo = 2",'ragione_sociale ASC ');
	$potential_id = $data_set->data_retriever($tables[106],'nome,cognome',"WHERE id != 1 ",'cognome ASC');

	$venditore =  $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND (tipo = 4)  ",'nominativo ASC');
	$venditore[0] = 'Chiunque'; 

	if(defined('MULTI_LOCATION')) {
	$ambiente_principale =  $data_set->data_retriever('fl_ambienti','nome_ambiente',"WHERE id != 1",'tipo_ambiente ASC');
	unset($ambiente_principale[0]);
	unset($ambiente_principale[1]);	
	$notturno = $ambiente_1 = $ambiente_2 = $ambiente_principale;
	}

	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("testo_preventivo","note","condizioni_preventivo","informativa_privacy","condizioni_pagamento"); 
	$select = array('notturno','ambiente_principale','ambiente_1','ambiente_2','tipo_preventivo','venditore','supervisore','anno_fiscale','produzione','cliente_id','potential_id');
	$disabled = array("visite");
	$hidden = array('data_scadenza',"potential_id",'importo_ordine','stima','descrizione','produzione','venditore','operatore','proprietario','anno_fiscale','supervisore','cliente_id','data_creazione','data_aggiornamento','account_id','tipo_utente','marchio','operatore','ip','proprietario',"relation");
	$radio = array();
	$text = array();
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario","attivo");
	} else { $radio  = array("attivo");	};
	$calendario = array('data_preventivo','data_emissione','data_apertura');	
	$file = array("upfile");
	$checkbox = array('status_preventivo','status_preventivo','prog_shore','sesso',"tipo_profilo","forma_giuridica");
	$invisible = array();
	if($_SESSION['usertype'] > 3){ array_push($hidden,"data_scadenza"); }
	$multi_selection = array("mesi_di_interesse","giorni_di_interesse");	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$disabled)){ $type = 4; }	
   	if(in_array($who,$multi_selection)){ $type = 23; }
	if(in_array($who,$invisible)){ $type = 7; }
    if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	$module_menu = '';
   foreach($tipo_preventivo as $valores => $label){ // Recursione Indici di Categoria
		$selected = (isset($_GET['tipo_preventivo']) && check($_GET['tipo_preventivo']) == $valores) ? " class=\"selected\"" : "";
		if($valores > 1) $module_menu .= "<li $selected><a href=\"./?tipo_preventivo=$valores\">".ucfirst($label)."</a></li>\r\n"; 
	}	
	
	$selected = (isset($_GET['scaduti'])) ? " class=\"selected\"" : "";
	$module_menu .= "<li $selected ><a href=\"./?scaduti\">Scaduti</a></li>\r\n"; 
?>