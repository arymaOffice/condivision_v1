<?php 
	
	// Variabili Modulo
	$active = 1;
	$sezione_tab = 1;
	$tab_id = 69;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 2000; 
	$sezione_id = -1;
	$jorel = 0;
	$accordion_menu = 1;
	$formValidator = 1;
	$text_editor = 2;
	$jquery = 1;
	
	$calendar = 1;
	$fancybox = 1;
	$searchbox = 'Cerca..';
	$documentazione_auto = 18;
	if(isset($_GET['tab_id'])){ $tabella = $tables[check($_GET['tab_id'])]; } else {
	$tabs_div = 0;
	$tab_div_labels = array('id'=>"Dettagli",
	'note'=>"Note",'mod_richieste.php?reQiD=[*ID*]'=>'Follow up');//,	'../mod_documentazione/mod_user.php?external&operatore=1&modulo=0&cat=17&contenuto=[*ID*]'=>"Allegati");
	}

	$module_title = 'Preventivi';
		
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['tipo_preventivo']) && check(@$_GET['tipo_preventivo']) != 0) { $tipo_preventivo_id = check($_GET['tipo_preventivo']);  } else {    $tipo_preventivo_id = 0; }
	$status_preventivo_id = (isset($_GET['status_preventivo']) && check(@$_GET['status_preventivo']) != -1) ? check(@$_GET['status_preventivo']) : -1;
	
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

	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("anno_fiscale DESC,id DESC","data_creazione ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$statuses = array();
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] > 3) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";

	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
    if(isset($_GET['scaduti'])) $tipologia_main .= " AND  status_preventivo != 4 AND  data_scadenza <= '".date('Y-m-d')."' ";
	if(isset($tipo_preventivo_id) && @$tipo_preventivo_id > 0) {  $tipologia_main .= " AND tipo_preventivo = $tipo_preventivo_id ";	 }
	if(isset($status_preventivo_id) && @$status_preventivo_id != -1) {  $tipologia_main .= " AND status_preventivo = $status_preventivo_id ";	 }

	if(isset($_GET['plus_status_preventivo'])) { 
	$statuses = $_GET['plus_status_preventivo'];
	$statusesval = implode("','",$statuses);
	$tipologia_main .= " AND status_preventivo IN ( '-1','$statusesval' )"; }
    if(isset($_GET['potential_id']))  $tipologia_main .= ' AND potential_id = '.check($_GET['potential_id']).' '; 
	
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('descrizione','descrizione');
	$tipologia_main .= ricerca_avanzata('note','note');
	$tipologia_main .= ricerca_avanzata('rif_cliente','rif_cliente');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$tipo_preventivo = $data_set->get_items_key("tipo_preventivo");	
	$categoria_preventivo = $data_set->get_items_key("categoria_preventivo");	

	
	$status_preventivo = array('Attesa Esito','Valuta','Rifiuto','Vendita','A Concorrenza');
	$mandatory = array("id");
	$supervisore = $proprietario;
	$tipo_richiesta = array('Call','Email','Follow up','Rifiutato','Concorrenza','Venduta','');	
	$produzione = $data_set->data_retriever('fl_sedi','sede,citta',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'sede ASC');
	$produzione[0] = 'Tutte';

	$cliente_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale',"WHERE id != 1 AND tipo_profilo = 2",'ragione_sociale ASC ');
	$potential_id = $data_set->data_retriever('fl_potentials','nome,cognome',"WHERE id != 1 ",'cognome ASC');

	$venditore =  $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND (tipo = 4)  ",'nominativo ASC');
	$venditore[0] = 'Chiunque'; 


	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("note"); 
	$select = array('venditore','supervisore','anno_fiscale','produzione','cliente_id','potential_id');
	$disabled = array("visite");
	$hidden = array('data_scadenza','data_emissione','importo_ordine','stima','descrizione','produzione','potential_id','venditore','operatore','proprietario','anno_fiscale','supervisore','cliente_id','data_creazione','data_aggiornamento','account_id','tipo_utente','marchio','operatore','ip','proprietario',"relation");
	$radio = array();
	$text = array();
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario","attivo");
	} else { $radio  = array("attivo");	};
	$calendario = array('data_offerta','data_emissione','data_apertura');	
	$file = array("upfile");
	$checkbox = array('categoria_preventivo','status_preventivo','tipo_preventivo','status_preventivo','prog_shore','sesso',"tipo_profilo","forma_giuridica");
	
	if($_SESSION['usertype'] > 3){ array_push($hidden,"data_scadenza"); }
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$disabled)){ $type = 4; }	
    if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	$module_menu = '';
	$new_button = '';
   
   foreach($tipo_preventivo as $valores => $label){ // Recursione Indici di Categoria
		$selected = ($tipo_preventivo_id == $valores) ? " class=\"selected\"" : "";
		if($valores > 1) $module_menu .= "<li $selected><a href=\"./?tipo_preventivo=$valores\">".ucfirst($label)."</a></li>\r\n"; 
	}	

	if(isset($_GET['tipo_preventivo'])) $module_title .= ' '.$tipo_preventivo[$tipo_preventivo_id];
	if(isset($_GET['status_preventivo'])) $module_title .= ' '.$status_preventivo[$_GET['status_preventivo']];
	if(isset($_GET['scaduti'])) $module_title .= ' Scaduti';

	$selected = (isset($_GET['scaduti'])) ? " class=\"selected\"" : "";
	$module_menu .= "<li $selected ><a href=\"./?scaduti\">Scaduti</a></li>\r\n"; 
?>