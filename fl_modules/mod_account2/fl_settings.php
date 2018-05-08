<?php 
	
	$module_uid = 9;
	check_auth($module_uid);
	
	
	$active = 1;
	$tab_id = 8;
	$tabella = $tables[$tab_id];
	$select = "*";
	$fancybox = 1;
	$jquery = 1;
	$filtri = 1;
	$module_menu = '';
	
	if($_SESSION['usertype'] == 0) { 
    $searchbox = "Cerca account";
	$module_title = "Account";
	
	$module_menu = '
   	  <li><a href="'.ROOT.$cp_admin.'fl_modules/mod_account/" class="">Account</a></li>
	  <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_accessi/">Registro Accessi</a></li>
	   <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_action_recorder/">Registro Azioni</a></li>
    ';
	} 


	$ordine_mod = array("id ASC","marchio DESC","tipo ASC","attivo DESC","gruppo ASC","user DESC");
	$ordine = $ordine_mod[0];
	
  
	$step = 30; 
	$usertype = $_SESSION['usertype'];
	
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
	$basic_filters = array('email','user','nominativo','tipo','attivo');
	
	$tipologia_main = "WHERE id != 1 ";
	foreach($_GET as $chiave => $valore){
		$chiave = check($chiave);
		$valore = check($valore);
		if(isset($campi[$chiave]) && $chiave != 'data_a' && $chiave != 'data_da' && $chiave != 'start' && $chiave != 'a' && $chiave != 'action' && $chiave != 'cerca' && $chiave != 'ricerca_avanzata'){
			  if(is_numeric($valore) && $valore > -1) { $tipologia_main .=  " AND $chiave = '$valore' "; }
			  else if($valore != '' && $valore != '-1') $tipologia_main .=  " AND LOWER($chiave) LIKE '%$valore%' ";
			}
	}
	
	if(isset($_GET['cerca'])) { 
	$valore = strtolower(check($_GET['cerca']));
	if($valore != '') {
	$tipologia_main .= " AND (";
	$x = 0;
	foreach($campi as $chiave => $val){		
		$chiave = check($chiave);
		if(select_type($chiave) != 5 && select_type($chiave) != 2 && select_type($chiave) != 19 && $chiave != 'id' && $valore != '') { if($x > 0) { $tipologia_main .= ' OR '; } $tipologia_main .=  " LOWER($chiave) LIKE '%$valore%' "; $x++; }
	}
	$tipologia_main .= ")";
	}}

	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$modulo = $data_set->data_retriever('fl_moduli','label','','label ASC');
	if($_SESSION['usertype'] > 0 && check(@$_GET['action'] > 0)) { unset($tipo[0]);  unset($tipo[1]); }
	$anagrafica = $data_set->data_retriever('fl_anagrafica','ragione_sociale',"WHERE id != 1",'ragione_sociale ASC ');
	if(defined('AFFILIAZIONE')) $anagrafica = $data_set->data_retriever('fl_anagrafica','ragione_sociale',"WHERE id != 1",'ragione_sociale ASC ');
	/*$persona_id = $data_set->data_retriever('fl_persone','nome,cognome',"WHERE id != 1",'nome ASC');
	$processo_id = $data_set->data_retriever('fl_processi','processo',"WHERE id != 1",'processo ASC');
	$profilo_funzione = $data_set->data_retriever('fl_profili_funzione','funzione',"WHERE id != 1 ",'funzione ASC');*/

	$attivo = array('1'=>'Attivo','0'=>'Sospeso');
	
	function select_type($who){
	$textareas = array("descrizione","note"); 
	$select = array('persona_id','tipo',"modulo");
	$checkbox = array();
	$disabled = array("total_scooring");
	$hidden = array('processo_id',"uid","cuid","anagrafica","sede","data_creazione","aggiornamento_password","proprietario","marchio","foto","data","password","id","codice","type","ip","continente","operatore","data_aggiornamento","visite");
	$selectbtn = array('attivo');	
	$radio = array("alert");	
	$multi_selection = array("giorni_lavorativi");	
	$calendario = array('data_scadenza','data_emissione','data_nascita');	
	$type = 1;

	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$checkbox)){ $type = 6; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$selectbtn)){ $type = 9; }
	if(in_array($who,$multi_selection)){ $type = 23; }
		
	return $type;
	}
	
?>