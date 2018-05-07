<?php 
	
	// Variabili Modulo
	$module_uid = 2;
	check_auth($module_uid);

	$active = 1;
	$sezione_tab = 1;
	$tab_id = 48;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 2000; 
	$sezione_id = -1;
	$jorel = 0;
	$accordion_menu = 1;
	$formValidator = 1;
	$text_editor = 2;
	$jquery = 1;
	$tabs_div = 0;
	$calendar = 1;
	$ajax = 1;
	$fancybox = 1;
	$searchbox = 'Cerca..';
	$documentazione_auto = 18;
	$account_did = (isset($_GET['id'])) ? get_account(check($_GET['id'])) : 0;
	if(isset($_GET['id'])) $tab_div_labels = array('../mod_account/mod_password.php?user='.@$account_did['user'].'&didi=[*ID*]&id='.@$account_did['id'].'' =>'Account','../mod_account/mod_sicurezza.php?didi=[*ID*]&id='.@$account_did['id'].''=>'Sicurezza','id'=>"Profilo",'cognome'=>"Dati Anagrafici",'tipo_documento'=>"Dati Documento",'forma_giuridica'=>"Dati Fiscali",'tipologia_attivita'=>"Dati Sede Operativa",'telefono'=>"Contatti",'note'=>"Note",'../mod_documentazione/mod_user.php?external&modulo=0&cat=18&contenuto=[*ID*]'=>"Documentazione");//,'../mod_depositi/mod_user.php?proprietario='.$account_did['id'].'&external&anagrafica_id=[*ID*]'=>"Estratto Conto",'../mod_account/mod_scheda.php?anagrafica_id=[*ID*]&external'=>"Account");
	if(isset($_GET['id']) && check($_GET['id']) == 1) { $tab_div_labels = array('id'=>"Profilo",'cognome'=>"Dati Anagrafici",'tipo_documento'=>"Dati Documento",'forma_giuridica'=>"Dati Fiscali",'tipologia_attivita'=>"Dati Sede Operativa",'telefono'=>"Contatti",'note'=>"Note",'../mod_documentazione/mod_user.php?external&modulo=0&cat=18&contenuto=[*ID*]'=>"Documentazione"); }
	$form_cut = array('profilo_commissione');
	if(isset($_GET['new'])){ new_inserimento($tabella,ROOT.$cp_admin."fl_modules/mod_anagrafica/mod_inserisci.php"); }

	$module_title = 'Area Anagrafica';
	if(isset( $_GET['action']) && $_GET['action'] == 22) { $module_title = 'Gestione Fido';  $new_button = ''; }


	$module_menu = '';
  
   if($_SESSION['usertype'] > 1) { 

   	$module_menu = '';
	$module_title = "Anagrafica Profilo";
	unset($searchbox);

   }
	if(isset($_GET['tab_id'])) { $tab_id = check($_GET['tab_id']); }
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['status_anagrafica']) && check(@$_GET['status_anagrafica']) > -1) { $status_anagrafica_id = check($_GET['status_anagrafica']);  } else {    $status_anagrafica_id = -1; }
	$stato_account_id = (isset($_GET['status_account']) && check(@$_GET['status_account']) != -1) ? check(@$_GET['status_account']) : -1;
	$status_saldi_id = (isset($_GET['status_saldi']) && check(@$_GET['status_saldi']) != -1) ? check(@$_GET['status_saldi']) : -1;

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('2015-1-1 H:i'); 
	 $data_a = date('Y-m-d H:i',time()+86400); 
	 
	 $data_da_t = date('1/1/2015'); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("nome ASC","data_creazione ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] > 1) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(@$_GET['action'] == 12) $tipologia_main .= " AND status_anagrafica = 3 ";
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12)  && @check($_GET['action'] != 22) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($userid) && @$userid != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND proprietario = $userid ";	 }
	if(isset($status_anagrafica_id) && @$status_anagrafica_id > -1) {  $tipologia_main .= " AND status_anagrafica = $status_anagrafica_id ";	 }
	if(isset($_GET['id_sc'])) {  $tipologia_main .= " AND data_scadenza <= NOW() ";	 $new_button = ''; }
	if(isset($_GET['cn_sc'])) {  $tipologia_main .= " AND data_scadenza_contratto <= NOW() ";  $new_button = '';  }
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;
	

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('nome','nome');
	$tipologia_main .= ricerca_avanzata('cognome','cognome');
	$tipologia_main .= ricerca_avanzata('ragione_sociale','ragione_sociale');
	$tipologia_main .= ricerca_avanzata('user','user');
	$tipologia_main .= ricerca_avanzata('nominativo','nominativo');
	$tipologia_main .= ricerca_avanzata('telefono','telefono');
	$tipologia_main .= ricerca_avanzata('partita_iva','partita_iva');
	$tipologia_main .= ricerca_avanzata('email','email');
	$tipologia_main .= ricerca_avanzata('comune_sede','comune_sede');
	$tipologia_main .= ricerca_avanzata('comune_punto','comune_punto');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/data_manager/array_statiche.php');
	include('../../fl_core/category/anagrafica.php');
	include('../../fl_core/category/proprietario.php');
	include('../../fl_core/category/stato.php');
	include('../../fl_core/category/provincia.php');
	include('../../fl_core/category/items_rel.php');
	$tipologia_attivita = get_items_key("punto_vendita");	
	$profilo_genitore = $account_id = $proprietario;
	
	$mandatory = array("id");
	
	
	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("note"); 
	$select = array('garanzia_fido','profilo_commissione','profilo_genitore','sesso','provincia_residenza','regione_residenza','tipologia_attivita','stato_nascita','stato_punto','stato_sede','stato_residenza','provincia_nascita',"provincia","tipo_documento","punto_vendita","regione_sede","provincia_sede","regione_punto","provincia_punto","tipo_profilo","forma_giuridica","status_anagrafica","proprietario","status","regione","nazione");
	$disabled = array("visite");
	$hidden = array('user','account_id','nominativo','marchio','operatore','ip','proprietario',"relation");
	$selectbtn = array('concessione_fido');
	$radio = array();
	$text = array();
	if($_SESSION['usertype'] > 0){
	array_push($hidden,"proprietario","attivo","status_anagrafica","marchio","account_affiliato");
	} else { $radio  = array("attivo");	};
	$calendario = array('data_scadenza_contratto','data_scadenza','data_emissione','data_nascita');	
	$file = array("upfile");
	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$selectbtn)){ $type = 22; }
	
	return $type;
	}
	
	
?>