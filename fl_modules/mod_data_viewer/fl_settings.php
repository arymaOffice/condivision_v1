<?php 
	
	/*Class Load */
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$tabelle = array('fl_istat_comuni','fl_stati');
	$tab_id = 115;
	// Variabili Modulo
	$select = "*";
	$step =  (isset($_SESSION['step'])) ? $_SESSION['step'] :  100; 
	$jquery = 1;
	$filtri = 1;
	if(isset($_GET['data'])) $_SESSION['tabella'] = base64_decode(check($_GET['data']));
	$tabella = (isset($_SESSION['tabella'])) ? $_SESSION['tabella'] : $tabelle[0];

	$module_menu = '';
	foreach($tabelle as  $chiave => $valore) {    $module_menu .= '<li class=""><a href="./?data='.base64_encode($valore).'">'.ucfirst(str_replace('bk_','',$valore)).' </a></li>'; }

	
	$campi = "SHOW COLUMNS FROM `$tabella`";
	$risultato = mysql_query($campi, CONNECT);
	$x = 0;
	$campi = array(); //E' possibile aggiungere campi head tabella statici
	while ($riga = mysql_fetch_assoc($risultato)) 
	{					
	if(select_type($riga['Field']) != 5 ) { 
						$ordinamento[$x] = record_label($riga['Field'],CONNECT,1); 
						$ordine_mod[$x] = $riga['Field'];
						$campi[$riga['Field']] = record_label($riga['Field'],CONNECT,1); $x++; }
	}
	$nofilter = array('data_apertura','data_chiusura','first_feedback_date','id');


	/* Tipologie di ordinamento disponobili */
	$ordine_type = (isset($_SESSION['ordine_type'])) ? ' '.$_SESSION['ordine_type'] : ' ASC';
	$ordine = (isset($_SESSION['ordinamento'])) ? $ordine_mod[$_SESSION['ordinamento']].$ordine_type : $ordine_mod[0].$ordine_type;
	$ordine = (isset($_SESSION['ordinamento2'])) ? $ordine.','.$ordine_mod[$_SESSION['ordinamento2']].$ordine_type : $ordine;
		
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1 ";
	foreach($_GET as $chiave => $valore){
		$chiave = check($chiave);
		$valore = check($valore);
		if(isset($campi[$chiave]) && $chiave != 'data_a' && $chiave != 'data_da' && $chiave != 'start' && $chiave != 'a' && $chiave != 'action'){
			  if(select_type($chiave) != 5 && select_type($chiave) != 2 && select_type($chiave) != 19 && $chiave != 'id' && $valore != '') $tipologia_main .=  " AND LOWER($chiave) LIKE '%$valore%' ";
			  if(select_type($chiave) == 2 && $chiave != 'id' && $valore > 1) $tipologia_main .=   " AND $chiave = '$valore' ";
			  if(select_type($chiave) == 19 && $chiave != 'id' && $valore > -1) $tipologia_main .=  " AND $chiave = '$valore' ";
			}
		
		}




	/* Inclusioni Oggetti Categorie */		
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	$data_set = new ARY_dataInterface();
	$priority = array('Low','High','Top Urgent');
	$Status = $status = $data_set->get_items_key('status');
	$responsibility = $data_set->get_items_key('responsibility');
	$type_of_issue = $data_set->get_items_key('type_of_issue');
	$change_order = array('No','Yes','Possible','To be received');
	$backcharge_to_supplier = array('No','Yes','Only Replaced','To be done');
	$managed_by = $proprietario;

	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("solution","issue","note"); 
	$select = array('proprietario','regione');
	$disabled = array();
	$hidden = array('data_creazione','data_aggiornamento','marchio','operatore');
	$radio = array();
	$text = array();
	$calendario = array('data_apertura','data_chiusura','first_feedback_date');
	$file = array("upfile");
	$checkbox = array('priority','change_order','backcharge_to_supplier');
	
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