<?php 
	
	// Variabili Modulo
	$active = 4;
	$tab_id = 46;
	$tabella = $tables[$tab_id];
	$select = "* ";
	$step = 500; 
	$text_editor = 2;
	$jquery = 1;
	$ajax = 1;
	$fancybox = 1;
	$searchbox = 'Ricerca';
	$calendar = 1;
	$filtri = 1;

	$tabs_div = 0;
	$tab_div_labels = array('id'=>"Dati Fattura",'imponibile'=>"Riepilogo",'pagato'=>"Pagamenti");

	$anno = date('Y');
	if(!isset($_SESSION['anno_fiscale'])) $_SESSION['anno_fiscale']  = $anno;
	if(isset($_GET['anno_fiscale'])) $_SESSION['anno_fiscale'] = check($_GET['anno_fiscale']);
	$anno_corrente =  $_SESSION['anno_fiscale'];


    $module_menu = '
	
  	   <li class=""><a href="./?tipo_doc_acquisto=0">Fatture <span class="subcolor">Acquisto </span></a>      </li>
	      	   <li class=""><a href="./?tipo_doc_acquisto=1">Ricevute  <span class="subcolor">Fiscali </span></a>      </li>

	   <li class=""><a href="./?tipo_doc_acquisto=2">Note di  <span class="subcolor">Credito </span></a>      </li>
	     <li class=""><a href="./?tipo_doc_acquisto=3">DDT  <span class="subcolor">Acquisto </span></a>      </li>
	      <li class=""><a href="./?tipo_doc_acquisto=4">Costi  <span class="subcolor">Generici </span></a>      </li>
	   ';
		
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != '') { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	if(isset($_GET['tipo_doc_acquisto']) && check(@$_GET['tipo_doc_acquisto']) != -1 && check(@$_GET['tipo_doc_acquisto']) != '') { $tda_id = check($_GET['tipo_doc_acquisto']); } else {  $tda_id = -1; }

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
		 
	 $data_da = date($_SESSION['anno_fiscale'].'-1-1 00:00'); 
	 $data_a = date($_SESSION['anno_fiscale'].'-12-31 23:59',time()+86400); 
	 
	 $data_da_t = date('1/1/'.$_SESSION['anno_fiscale']); 
	 $data_a_t = date('31/12/'.$_SESSION['anno_fiscale']); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("data_documento DESC, id DESC","anagrafica_id ASC","proprietario ASC","id DESC",'numero_documento ASC');
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	
	if(isset($data_da) && !isset($_GET['cerca']))  	$tipologia_main .= " AND `data_documento`  BETWEEN '$data_da' AND '$data_a' ";

	if(isset($proprietario_id) && @$proprietario_id != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND proprietario = $proprietario_id ";	 }
	if(isset($tda_id) && @$tda_id != -1) {  $tipologia_main .= " AND tipo_doc_acquisto = $tda_id ";	 } 

	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	
	$tipologia_main .= ricerca_semplice('ragione_sociale','ragione_sociale');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $anagrafica_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale',"WHERE id != 1 AND (tipo_profilo = 1 || tipo_profilo = 3)");
	$paese = $data_set->data_retriever('fl_stati','descrizione','WHERE `DataFineVal` IS NULL');
	$centro_di_costo = $data_set->data_retriever('fl_cg_res','codice,label','WHERE tipo_voce = 0 AND parent_id = 0');
	$centro_di_costo_secondario = $data_set->data_retriever('fl_cg_res','codice,label','WHERE tipo_voce = 0 AND parent_id > 0');
	$centro_di_costo[-1] = '--';


	$account_id = $proprietario;
	$mandatory = array("id");
	
	
	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array('informazioni_cliente'); 
	$select = array('centro_di_costo','centro_di_costo_secondario','valuta','paese','metodo_di_pagamento','anagrafica_id');
	$disabled = array();
	$hidden = array('tipo_doc_acquisto','lingua_documento','categoria_doc_acquisto',"anno_di_competenza","contrassegnata",'template','valore_conversione','operatore','proprietario','marchio','anno_di_imposta',"data_creazione",'data_aggiornamento');
	$radio = array('fattura_elettronica','arrotondamento','marca_da_bollo','iva_su_cassa_e_rivalsa',"pagato");
	$labelbox = array('');
	$text = array();
	$checkbox = array();
	$calendario = array('data_scadenza','data_documento','data_pagamento','data_documento');	
	$file = array('upfile');
	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
		if(in_array($who,$labelbox)){ $type = 9; }

	return $type;
	}
	
	$module_title = '<span>'.$tipi_doc_acquisto[$tda_id].'</span> <span class="msg blue">'.$_SESSION['anno_fiscale'].'</span>';

?>