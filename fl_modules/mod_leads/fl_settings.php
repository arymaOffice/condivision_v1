<?php 
	
	//$active = 'callcencer';
	//$sezione_tab = 1;
	
	$modulo_uid = 26;
	$parametri_modulo = check_auth($modulo_uid); /* Nuova preconfigurazione dei moduli (da convertire in classe?) */
	
	// NUOVA QUERY DA SOSTITUIRE SELECT pot.id,(SELECT GROUP_CONCAT(marca) FROM fl_veicoli WHERE id IN(SELECT DISTINCT id FROM fl_veicoliTest WHERE parent_id = pot.id)) FROM fl_potentialsTest pot WHERE pot.id > 1
	// Variabili Modulo
	$permesso    = $parametri_modulo['permesso'];
	$tab_id      = $parametri_modulo['tab_id'];
	$tabella     = $tables[$tab_id];
	$tabella     = $tabella.' AS tb1 '; //.' AS tb1 LEFT JOIN fl_veicoli AS tb2 ON tb1.id = tb2.parent_id';
	$select      = "*"; //"tb1.*,tb2.*,tb1.id as id,tb2.id as did, tb1.data_creazione as data_creazione";
	$ordine      =  $parametri_modulo['ordine_predefinito']; 
	$step        = (isset($_SESSION['step'])) ? $_SESSION['step'] : $parametri_modulo['risultati_pagina']; 
	//$text_editor = $parametri_modulo['editor_wysiwyg'];
	$jquery      = $parametri_modulo['jquery'];
	$fancybox    = $parametri_modulo['fancybox'];
	$filtri      = $parametri_modulo['filtri'];
	$toggleOn    = $parametri_modulo['menu_aperto'];
	$calendar    = $parametri_modulo['calendari'];
	if($parametri_modulo['ricerca'] == 1) $searchbox = $parametri_modulo['placeholder_ricerca'];
	$checkRadioLabel = '<i class="fa fa-check-square"></i><i class="fa fa-square-o"></i>'; // Pulsante checkbox radio button Toggle apple
 	$dateTimePicker = 1;

 	$tab_div_labels = array('id'=>"Dati Personali",'tipo_interesse'=>"Interesse Vettura",'note'=>'Gestione');
	if(isset($_GET['id']) && check(@$_GET['id']) != 1) $tab_div_labels = array('mod_richieste.php?reQiD=[*ID*]'=>'Gestione BDC','id'=>"Dati Personali",'../mod_appuntamenti/mod_user.php?history&potential_rel=[*ID*]'=>"Agenda Incontri", 'tipo_interesse'=>"Interesse Vettura",'../mod_preventivi/mod_user.php?potential_id=[*ID*]'=>"Preventivi",);
		
	
	if(!isset($_SESSION['status_potential_id'])) $_SESSION['status_potential_id'] = -1;
	if(isset($_GET['status_potential'])) $_SESSION['status_potential_id'] = check($_GET['status_potential']);
	$status_potential_id = $_SESSION['status_potential_id'];


  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	 } else {
	 $data_da_t = date('Y-m-d',time()-99204800); 
	 $data_a_t = date('Y-m-d'); 
	 $data_da_t = date('d/m/Y',time()-99204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	
 	/*Impostazione automatica da tabella */
	$campi = gcolums('fl_potentials'); //Ritorna i campi della tabella
    $tipologia_main = gwhere($campi,'WHERE tb1.id != 1 ','tb1.');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
    /*$campi2 = gcolums('fl_veicoli'); //Ritorna i campi della tabella
    $tipologia_main .= gwhere($campi2,'','tb2.');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
	*/

	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('source_potential','tipo_interesse','permuta','promo_pneumatici','test_drive','campagna_id','telefono');
	$basic_filters2 = array();//array('alimentazione','anno_immatricolazione','pagamento_veicolo');
	if(!isset($_SESSION['ordine_type'])) $_SESSION['ordine_type'] = 'DESC';
	$ordine_mod = array("tb1.data_associazione_attivita ".$_SESSION['ordine_type'].",tb1.data_creazione ".$_SESSION['ordine_type'],'tb1.nome '.$_SESSION['ordine_type'],'tb1.proprietario '.$_SESSION['ordine_type'],'tb1.venditore '.$_SESSION['ordine_type']); // Tipologie di ordinamento disponobili 
	$ordine = (isset($_SESSION['ordine_mode'])) ? $ordine_mod[$_SESSION['ordine_mode']] :  $ordine_mod[0];

	/* Personalizzazione della query */
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1) { $proprietario_id = check($_GET['proprietario']);  } else {    $proprietario_id = -1; }
    
    if(!isset($_GET['all']) && isset($proprietario_id) && @$proprietario_id > 1 && $_SESSION['usertype'] != 4 ) {  
    $tipologia_main .= " AND (tb1.proprietario = $proprietario_id OR tb1.venditore = $proprietario_id)  "; 
    } 

    if($status_potential_id != 0 && !isset($_GET['daAssegnare']) && !isset($_GET['all']) && $_SESSION['usertype'] != 0 && $_SESSION['profilo_funzione'] != 8 && $_SESSION['usertype'] != 5){ 
    $tipologia_main .= " AND (tb1.proprietario = ".$_SESSION['number']." OR tb1.venditore = ".$_SESSION['number'].")"; 
	} else { 
	$tipologia_main .= ''; 
	}
  	
  	/*if($_SESSION['usertype'] > 0 && isset($_GET['all'])) {
  	$proprietariStesseSedi = getAccountSede($_SESSION['sedi_id']);
  	if($proprietariStesseSedi != '') $tipologia_main .= ' AND (tb1.proprietario IN('.$proprietariStesseSedi.') OR tb1.venditore IN('.$proprietariStesseSedi.') )';
  	}*/

  	if(isset($_GET['venditore']) && check(@$_GET['venditore']) != -1) { $venditore_id = check($_GET['venditore']);  } else {    $venditore_id = -1; }
    //if(isset($venditore_id) && @$venditore_id != -1) {  $tipologia_main .= " AND tb1.venditore = $venditore_id "; } else if($_SESSION['usertype'] == 4 && !isset($_GET['venditore'])) { $tipologia_main .= " AND tb1.venditore = ".$_SESSION['number']." "; }
	if(isset($_GET['todayCreated']))  $tipologia_main .= " AND DATE(tb1.`data_associazione_attivita`) = CURDATE()";
	if(isset($_GET['today']))  $tipologia_main .= " AND DATE(tb1.`data_scadenza_venditore`) = CURDATE()";
    if(isset($_GET['tomorrow']))  $tipologia_main .= "  AND DATE(tb1.`data_scadenza_venditore`) = CURDATE() + INTERVAL 1 DAY";
    if(isset($_GET['next']))  $tipologia_main .= "  AND DATE(tb1.`data_scadenza_venditore`) > CURDATE() + INTERVAL 1 DAY";
    if(isset($_GET['gestioneBdc']))  $tipologia_main .= "  AND proprietario > 0";
    if(isset($_GET['gestioneVendita']))  $tipologia_main .= " AND tb1.status_potential < 2 AND (venditore > 0 AND proprietario > 0)";
    if(isset($_GET['gestioneDirectSales']))  $tipologia_main .= "  AND (venditore > 0 AND proprietario < 1)";
    if(isset($_GET['daAssegnare']))  $tipologia_main .= "  AND (proprietario < 1)";


	$proprie = ($_SESSION['usertype'] > 2 && (!isset($_GET['status_potential']) || @$_GET['status_potential'] != 0) && !isset($_GET['daAssegnare']))  ?  " AND proprietario = ".$_SESSION['number']." " : ''; // Aggiunta per i counters e il title counter
    if($_SESSION['usertype'] == 4)   $proprie =  " AND venditore = ".$_SESSION['number']; // Aggiunta per i counters e il title counter
	if(isset($data_da) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) $tipologia_main .= " AND `tb1`.`data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	
	/* Filtri personalizzati */
	if(isset($_GET['qualificati'])) { $qualificati_id = check($_GET['qualificati']);  } else {    $qualificati_id = -1; }
	if(isset($qualificati_id) && @$qualificati_id == 0) {  $tipologia_main .= " AND (tb1.telefono = '' OR tb1.nome = '' ) ";	 }
	if(isset($qualificati_id) && @$qualificati_id == 1) {  $tipologia_main .= " AND (CHAR_LENGTH(tb1.email) > 5 AND CHAR_LENGTH(tb1.telefono) > 7 AND CHAR_LENGTH(tb1.nome) > 3) ";	 }
	if(isset($qualificati_id) && @$qualificati_id == 2) {  $tipologia_main .= " AND (CHAR_LENGTH(tb1.email) > 5 AND CHAR_LENGTH(tb1.telefono) > 7 AND CHAR_LENGTH(tb1.nome) > 3 AND permuta = 1) ";	 }
	
	if(!isset($_GET['cerca'])) {
	$tipologia_main .= ($status_potential_id >= 0)  ? " AND tb1.status_potential = $status_potential_id" :  " AND (tb1.status_potential != 9)";	
	}


	$tipologia_main .= ' GROUP BY tb1.id ';
	$where_count = str_replace('WHERE ','tb1.id > 1 AND ', $tipologia_main);

	
	/* Inclusioni dataset */
	include('../../fl_core/dataset/array_statiche.php'); //Array statiche
	include('../../fl_core/dataset/proprietario.php'); //Array statiche
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di data provisioning
	$data_set = new ARY_dataInterface();
	$mansione = $data_set->get_items_key("mansione");	
	$tipo_interesse = $data_set->get_items_key("tipo_interesse");	
	$pagamento_vettura = $data_set->get_items_key("pagamento_vettura");	
	$stato_nascita = $stato_sede = $stato_residenza = $stato_punto = $paese = $data_set->data_retriever('fl_stati','descrizione',"WHERE id != 1",'descrizione ASC');
	$campagna_id = $data_set->data_retriever('fl_campagne','descrizione',"WHERE id != 1",'descrizione ASC');
	$source_potential = $data_set->data_retriever('fl_campagne_attivita','oggetto',"WHERE id != 1",'oggetto ASC');
	$sede = $data_set->data_retriever('fl_sedi','sede',"WHERE id != 1",'sede ASC');
	$priorita_contatto = array('Bassa','Media','Alta');
	$qualificati = array('1 stella','2 Stelle','3 Stelle');
	$test_drive = $permuta = $promo_pneumatici = array('No','Si');
	$tipo_richiesta = array('Chiamata','Email','Follow up','Rifiutato','Concorrenza','Appuntamento','Conversione','Preventivo','No Show','SMS','Modifica');	

	$operatoribdc =  $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND tipo = 3   ",'nominativo ASC');
	$venditore = $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND  tipo = 4  ",'nominativo ASC');
	$operatoridgt = $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND  (tipo = 5)  ",'nominativo ASC');
	$operatoribdc[0]  = $proprietario[0] = $venditore[0] = $operatoridgt[0] = 'Non Assegnato'; 
	$categoria_interesse = $data_set->get_items_key("categoria_preventivo");	

	$status_potential = array('-1'=>"Tutti")+$status_potential;

	$template = $data_set->data_retriever('fl_msg_template','oggetto',"WHERE id != 1",'oggetto ASC');
	$mittente = $data_set->get_items_key("mittente");	
	$tag_sms = $data_set->get_items_key("tag_sms");
	
	$tipologia_veicolo = $data_set->get_items_key("tipo_interesse");	
	$pagamento_veicolo = $data_set->get_items_key("pagamento_vettura");	
	$data_acquisto = array(); for($i=1995;$i<date('Y')+1;$i++) $data_acquisto[$i] = $i;
	$anno_immatricolazione = array(); for($i=1950;$i<date('Y');$i++) $anno_immatricolazione[$i] = $i;


	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array("messaggio","note"); 
	$select = array('sede',"alimentazione",'tipologia_veicolo','pagamento_veicolo','campagna_id','pagamento_vettura',"source_potential",'vettura_posseduta_alimentazione','pagamento_vettura','experience_level',"mansione","paese","proprietario","status_pagamento","causale","metodo_di_pagamento");
	$select_text = array("provincia","citta",'data_acquisto','anno_immatricolazione');
	$disabled = array("data_creazione","visite","data_assegnazione");
	$hidden = array("data_aggiornamento");
	$radio = array('promo_pneumatici','test_drive','vettura_promo','permuta');
	$text = array();
	$calendario = array('data_scadenza','data_acquisto_vettura','data_test_drive','periodo_cambio_vettura');	
	$file = array();
	$checkbox = array('categoria_interesse','tipo_interesse','priorita_contatto');
	$invisible = array('data_assegnazione','data_scadenza_venditore','venditore','vettura_posseduta_marca', 'vettura_posseduta_modello', 'vettura_posseduta_immatricolazione', 'vettura_posseduta_km', 'vettura_posseduta_alimentazione', 'vettura_posseduta_targa', 'quotazione_vettura', 'data_acquisto_vettura',"website","fatturato_annuo","mansione","company","job_title","numero_dipendenti","data_creazione","sent_mail","in_use","status_potential",'is_customer',"marchio","ip","operatore");
	$datePicker = array('data_scadenza');
	if(isset($_GET['id'])) { if(check($_GET['id']) > 1) { $invisible[] = 'priorita_contatto'; $invisible[] = "note"; $invisible[] = 'proprietario'; }  }
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$select_text)) { $type = 12; }	
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$datePicker)){ $type = 11; }
	if(in_array($who,$invisible)){ $type = 7; }

	
	return $type;
	}
	
	
   $module_menu = '';

  
 /* if($_SESSION['usertype'] == 2){

 $liste_leads = array('Leads Placement','Leads Facebook','Leads Preventivi','Leads Parco Circolante','Leads Convenzioni','Leads Officine Autorizzate','Leads Segnalatori','Leads Scuole Guide');
   foreach($liste_leads as $valores => $label){ // Recursione Indici di Categoria
			$selected = '';//($status_potential_id == $valores) ? " class=\"selected\"" : "";
			$module_menu .= "<li $selected><a href=\"./?source_potential=$valores\">".ucfirst($label)."</a></li>\r\n"; 
	}
 
  } else {*/

 foreach($status_potential as $valores => $label){ // Recursione Indici di Categoria
			
			$selected = ($status_potential_id == $valores) ? " class=\"selected\"" : "";
			$action = (isset($_GET['action'])) ? '&action='.check($_GET['action']) : "";
			$source = '';
			if(isset($_GET['source_potential'])) { foreach ($_GET['source_potential'] as $key => $value) {
				$source .= '&source_potential[]='.check($value);
			}}
			//$querycount = 'status_potential != 4 ';
			//$querycount .= ($valores < 0) ? '' : ' AND status_potential = '.$valores;
			//$querycount .= ($valores > 1) ? '' : $proprie;
			
			//$count = mk_count('fl_potentials AS tb1',$querycount,'',0);
			if($valores != 9 || $_SESSION['number'] == 1) $module_menu .= "<li $selected><a href=\"./?status_potential=$valores$action$source\">".ucfirst($label)."  </a></li>\r\n"; 
	}

	  
	  //}
?>