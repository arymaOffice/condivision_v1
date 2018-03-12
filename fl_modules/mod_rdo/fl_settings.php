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
	$tab_div_labels = array('id'=>"Riferimenti",'descrizione'=>"Dettagli RdO",
	'note'=>"Note",'mod_richieste.php?reQiD=[*ID*]'=>'Follow up');//,	'../mod_documentazione/mod_user.php?external&operatore=1&modulo=0&cat=17&contenuto=[*ID*]'=>"Allegati");
	}
	
	if(isset($_GET['anno_fiscale'])) $_SESSION['anno_fiscale'] = check($_GET['anno_fiscale']);
	$anno_corrente = $anno = date('Y');
	$anno_fiscale = (isset($_SESSION['anno_fiscale'])) ? $_SESSION['anno_fiscale'] : $anno_corrente;
	
	$module_title = 'RdO/Preventivi';
    $module_menu = '
	<ul>
  	   <li class=""><a href="#" onclick="display_toggle(\'#menu_modulo\');"><i class="fa fa-th-large"></i></a></li>
		<li><a href="./?anno_fiscale='.$anno_fiscale.'">'.$anno_fiscale.'</a>
		<ul>';
		 while($anno >= ($anno_corrente-3)){  $module_menu .= '<li><a href="./?anno_fiscale='.$anno.'">'.$anno.'</a>';  $anno--; }
		 $module_menu .=' </ul>
		</li>
		</li>
	  <li><a href="./mod_inserisci.php?id=1" class="create_new"><i class="fa fa-plus-circle"></i> Nuova RdO </a></li>

     </ul>';
		
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['tipo_rdo']) && check(@$_GET['tipo_rdo']) != 0) { $tipo_rdo_id = check($_GET['tipo_rdo']);  } else {    $tipo_rdo_id = 0; }
	$status_rdo_id = (isset($_GET['status_rdo']) && check(@$_GET['status_rdo']) != -1) ? check(@$_GET['status_rdo']) : -1;
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-d H:i',time()-999204800); 
	 $data_a = date('Y-m-d H:i',time()+86400); 
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("anno_fiscale DESC, numero_progressivo DESC","data_creazione ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$statuses = array();
	$tipologia_main = "WHERE id != 1 ";
	//if($_SESSION['usertype'] > 1) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(@$_GET['action'] == 12) $tipologia_main .= " AND status_rdo = 3 ";
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";

	if(isset($anno_fiscale)) {  $tipologia_main .= " AND anno_fiscale = $anno_fiscale ";	 }
	if(isset($tipo_rdo_id) && @$tipo_rdo_id > 0) {  $tipologia_main .= " AND tipo_rdo = $tipo_rdo_id ";	 }
	if(isset($status_rdo_id) && @$status_rdo_id != -1) {  $tipologia_main .= " AND status_rdo = $status_rdo_id ";	 }
	if(isset($_GET['statuses_rdo'])) { 
	$statuses = $_GET['statuses_rdo'];
	$statusesval = implode("','",$statuses);
	$tipologia_main .= " AND status_rdo IN ( '-1','$statusesval' )"; }
	
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('descrizione','descrizione');
	$tipologia_main .= ricerca_avanzata('note','note');
	$tipologia_main .= ricerca_avanzata('riferimento_cliente','riferimento_cliente');
	$tipologia_main .= ricerca_avanzata('numero_progressivo','numero_progressivo');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$tipo_rdo = $data_set->get_items_key("tipo_rdo");	
	$settore_id = $data_set->get_items_key("settore_id");
	$divisione_id = $data_set->get_items_key("divisione_id");
	$produzione = $data_set->get_items_key("produzione");
	$settore_cliente = $data_set->get_items_key("settore_cliente");
	$prog_shore = array("Onshore","Offshore");
	$status_rdo = array('AP','ATT','DF','NO','RIF','SI','SOS','ANN');
	$mandatory = array("id");
	$preventivista = $proprietario;
	$tipo_richiesta = array('Call','Email','Follow up','Qt. Rifiutata','Perso','Vinto','');	

	$cliente_id = $data_set->data_retriever('fl_anagrafica','codice_cliente,ragione_sociale',"WHERE id != 1",'codice_cliente ASC');
	$codice_cliente = $data_set->data_retriever('fl_anagrafica','codice_cliente',"WHERE id != 1",'codice_cliente ASC');

	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("descrizione","note"); 
	$select = array('settore_cliente','anno_fiscale','produzione','settore_id','tipo_rdo','cliente_id','divisione_id');
	$disabled = array("visite");
	$hidden = array('data_creazione','preventivista','data_aggiornamento','account_id','tipo_utente','marchio','operatore','ip','proprietario',"relation");
	$radio = array();
	$text = array();
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario","attivo");
	} else { $radio  = array("attivo");	};
	$calendario = array('data_offerta','data_scadenza','data_emissione','data_apertura');	
	$file = array("upfile");
	$checkbox = array('status_rdo','prog_shore','sesso',"tipo_profilo","forma_giuridica");
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
	
	
?>