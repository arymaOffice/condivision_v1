<?php 
	
	// Variabili Modulo
	$modulo_uid = 26;
	$tab_id = 92;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 100; 
	$text_editor = 2;
	$jquery = 1;
	$fancybox = 1;
	$searchbox = 'Cerca utente..';
	$workflow_id = $modulo_uid;
	$tab_div_labels = array('id'=>"Richiesta",'mail_verificata'=>'Verifiche');

	$module_title = 'Adeguata Verifica ';


    $module_menu = '';
	
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	 } else {
	 $data_da = date('Y-m-d',time()-2592000); 
	 $data_a = date('Y-m-d',time()+86400); 
	 $data_da_t = date('d/m/Y',time()-2592000); 
	 $data_a_t = date('d/m/Y'); 
	 }
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("nome ASC","user ASC","operatore ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND operatore = ".$_SESSION['number']." ";
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($userid) && @$userid != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND operatore = $userid ";	 }
	if(isset($identitficato_id) && @$identitficato_id != 0) {  $tipologia_main .= " AND operatore = $identitficato_id ";	 }

	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('cognome','cognome');
	$tipologia_main .= ricerca_avanzata('user','user');
	$tipologia_main .= ricerca_avanzata('codice_fiscale','codice_fiscale');
	$tipologia_main .= ")";
	}

	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');

		
	function select_type($who){
	/* Gestione Oggetto Statica */
	$textareas = array("note"); 
	$select = array("proprietario","status");
	$disabled = array('ip_richiesta','codice_verifica_mail','data_rilevazione','punto_rilevato','data_rilevazione','ip_rilevazione','ip_verifica_email','ip_verifica_tel','data_verifica_email','data_verifica_tel','codice_verifica',"visite");
	$hidden = array('data_chiusura',"data_creazione","id","ip","operatore","data_aggiornamento");
	$radio = array('telefono_verificato','mail_verificata','posizione_verificata');
	$text = array();
	$checkbox = array("identificato","marchio");
	$calendario = array('data_richiesta');	
	
	
	
	$type = 1;
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$hidden)){ $type = 5; }
	return $type;
	}
	
	
?>