<?php 
	
	// Variabili Modulo
	$active = 2;
	$sezione_tab = 1;
	$tab_id = 7;
	$tabella = $tables[$tab_id];
	$select = "* ";
	$step = 100; 
	$sezione_id = -1;
	$jorel = 0;
	$text_editor = 2;
	$jquery = 1;
	$fancybox = 1;
	$searchbox = 'Ricerca nota';
	$calendar = 1;
	$documentazione_auto = 18;
	
	$anno_di_imposta = date('Y');
	
	if(!isset($_SESSION['proprietario_id'])) $_SESSION['proprietario_id'] = 1;
	if(isset($_GET['cmy'])) $_SESSION['proprietario_id'] = check(base64_decode($_GET['cmy']));
	$proprietario_id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : $_SESSION['proprietario_id'];
	if(isset($_GET['causale']) && check(@$_GET['causale']) != -1 && check(@$_GET['causale']) != '') { $causale_id = check($_GET['causale']); } else {  $causale_id = -1; }
	if(isset($_GET['cId']) && check(@$_GET['cId']) != -1 && check(@$_GET['cId']) != '') {  $conto_id = base64_decode(check($_GET['cId'])); } else {  $conto_id = -1; }



    $module_menu = '';
	$new_button = '<a href="./mod_inserisci.php?id=1&AiD='.base64_encode($proprietario_id).'&cId='.base64_encode($conto_id).'" style="color: gray;"><i class="fa fa-plus-circle"></i></a>';
		

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-1 H:i'); 
	 $data_a = date('Y-m-d H:i',time()+86400); 
	 
	 $data_da_t = date('1/m/Y'); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("data_operazione ASC","data_registrazione DESC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	
	if(isset($_GET['data_da']) && !isset($_GET['cerca'])) 	$tipologia_main .= " AND `data_operazione`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($proprietario_id) && @$proprietario_id != -1) {  $propid = $tipologia_main .= " AND account_id = $proprietario_id ";	 }
	if(isset($causale_id) && @$causale_id != -1) {  $tipologia_main .= " AND causale_operazione = $causale_id ";	 } 
	if(isset($conto_id) && @$conto_id != -1) {  $tipologia_main .= " AND conto = $conto_id ";	 } 

	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('descrizione','descrizione');
	$tipologia_main .= ricerca_avanzata('dare','dare');
	$tipologia_main .= ricerca_avanzata('avere','avere');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	include('../../fl_core/dataset/items_rel.php');
	include('../../fl_core/dataset/anagrafica.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	
	$conto = $data_set->data_retriever('fl_conti','descrizione',"WHERE id = 1 OR anagrafica_id = ".$proprietario_id,'id ASC');
	unset($conto[0]);
	
	$account_id = $proprietario;
	$mandatory = array("id");
	
	
	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array(); 
	$select = array('conto','categoria_operazione','metodo_di_pagamento','status_pagamento','causale_operazione','status_profilo','mother_tongue','second_language','third_language','paese','english_level','experience','disponibilita_impiego','mansione','mansione_alternativa1','mansione_alternativa2','mansione_alternativa3','mansione_alternativa4','sesso','provincia_residenza','regione_residenza','tipologia_attivita','stato_nascita','stato_punto','stato_sede','stato_residenza','provincia_nascita','account_id',"provincia","tipo_documento","punto_vendita","regione_sede","provincia_sede","regione_punto","provincia_punto","tipo_profilo","forma_giuridica","status_anagrafica","proprietario","status","regione","nazione");
	$disabled = array("anno_di_imposta","visite");
	$hidden = array('customer_id','operatore','account_id','marchio','anno_di_imposta','causale_operazione','riferimento','anagrafica_id',"data_creazione","customer_rel",'data_aggiornamento');
	$radio = array("fattura");
	$text = array();
	$valuta = array('dare','avere');
	$calendario = array('scadenza_pagamento','data_operazione','data_emissione','data_nascita');	
	$file = array();
	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$valuta)){ $type = 10; }
	
	return $type;
	}
	
	
	$module_title = 'Prima nota '.$anagrafica[$proprietario_id];

?>