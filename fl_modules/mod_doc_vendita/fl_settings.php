<?php 
	
	// Variabili Modulo
	$active = 3;
	$tab_id = 45;
	$tabella = $tables[$tab_id];
	$select = "* ";
	$step = 100; 
	$text_editor = 2;
	$jquery = 1;
	$ajax = 1;
	$fancybox = 1;
	$searchbox = 'Ricerca';
	$calendar = 1;
	$filtri = 1;
	$tabs_div = 0;
	$tab_div_labels = array('id'=>"Cliente",'data_documento'=>"Dettagli Documento",'mod_voci.php?DAiD=[*ID*]'=>"Specifica",'pagato'=>"Pagamento");
	$defaultDocVendita = 1;
	
	$anno = date('Y');
	if(!isset($_SESSION['anno_fiscale'])) $_SESSION['anno_fiscale']  = $anno;
	if(isset($_GET['anno_fiscale'])) $_SESSION['anno_fiscale'] = check($_GET['anno_fiscale']);
	$anno_corrente =  $_SESSION['anno_fiscale'];
    

		
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != '') { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	if(isset($_GET['tipo_doc_vendita']) && check(@$_GET['tipo_doc_vendita']) != -1 && check(@$_GET['tipo_doc_vendita']) != '') { $tdv_id = check($_GET['tipo_doc_vendita']); } else {  $tdv_id = $defaultDocVendita; }
	if(isset($_GET['pagato']) && check(@$_GET['pagato']) != -1 && check(@$_GET['pagato']) != '') { $pagato = check($_GET['pagato']); } else {  $pagato = -1; }
	

	$nextNumber = get_next_number(date('Y'),$tdv_id);
	$new_button = '<a href="./mod_inserisci.php?id=1&tipo_doc_vendita='.$tdv_id.'&numero_documento='.$nextNumber.'" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a>';

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
    $ordine_mod = array("data_documento DESC, numero_documento DESC","anagrafica_id DESC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	
	if(isset($data_da) && !isset($_GET['cerca'])) 	$tipologia_main .= " AND `data_documento`  BETWEEN '$data_da' AND '$data_a' ";

	if(isset($proprietario_id) && @$proprietario_id != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND proprietario = $proprietario_id ";	 }
	if(isset($tdv_id) && @$tdv_id != -1) {  $tipologia_main .= " AND tipo_doc_vendita = $tdv_id ";	 } 
	if(isset($pagato) && @$pagato != -1) {  $tipologia_main .= " AND pagato = $pagato ";	 } 


	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('oggetto_documento','oggetto_documento');
	$tipologia_main .= ricerca_avanzata('nota_interna','nota_interna');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $anagrafica_id = $data_set->data_retriever('fl_anagrafica','nome,cognome','','ragione_sociale ASC');
	$paese = $data_set->data_retriever('fl_stati','descrizione','WHERE `DataFineVal` IS NULL');
	$categoria_doc_vendita  = $data_set->data_retriever('fl_cg_res','codice,label','WHERE parent_id = 0 AND tipo_voce = 1');
	$centro_di_ricavo  = $data_set->data_retriever('fl_cg_res','codice,label','WHERE  parent_id > 0 AND tipo_voce = 1');
	$centro_di_ricavo[-1] = '--';
	$folder = DMS_ROOT.'fatture/';
	if(!is_dir(DMS_ROOT.'fatture/')) @mkdir(DMS_ROOT.'fatture/');

	$account_id = $proprietario;
	$mandatory = array("id");
	
	
	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array('informazioni_cliente'); 
	$select = array('anagrafica_id','categoria_doc_vendita','centro_di_ricavo','valuta','paese','metodo_di_pagamento');
	$disabled = array();
	$hidden = array('competenze','rivalsa_inps','rivalsa_cassa','iva_su_cassa_e_rivalsa','ritenuta_su_percentuale','ritenuta_previdenziale','arrotondamento','tipo_doc_vendita','imponibile','non_imponibile','iva','lingua_documento',"anno_di_competenza","contrassegnata",'template','valore_conversione','operatore','proprietario','marchio','anno_di_imposta',"data_creazione",'data_aggiornamento');
	$radio = array('fattura_elettronica','arrotondamento','marca_da_bollo','iva_su_cassa_e_rivalsa',"pagato");
	$checkbox = array();
	$labelbox = array();
	$text = array();
	$calendario = array('data_scadenza','data_documento','data_pagamento','data_documento');	
	$file = array('upfile');
	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$labelbox)){ $type = 9; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$disabled)){ $type = 4; }

	return $type;
	}
	
		$module_title = '<span>'.$tipi_doc_vendita[$tdv_id].'</span> <span class="msg blue">'.$_SESSION['anno_fiscale'].'</span>';

    $module_menu = ''; /*'
    	   	   <li class=""><a href="./?tipo_doc_vendita=1">Ricevute <span class="subcolor">Vendita </span></a>      </li>

	   <li class=""><a href="./?tipo_doc_vendita=0">Fatture <span class="subcolor">Vendita </span></a>      </li>

	   <li class=""><a href="./?tipo_doc_vendita=2">Note di  <span class="subcolor">Credito </span></a>      </li>
	    <li class=""><a href="./?tipo_doc_vendita=4">Proforma  <span class="subcolor"> </span></a>      </li>
	   <li class=""><a href="./?tipo_doc_vendita=3">DDT  <span class="subcolor">Vendita </span></a>      </li>';*/
 

?>
