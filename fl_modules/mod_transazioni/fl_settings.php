<?php 
	
	$module_uid = 5;
	check_auth($module_uid);
	$active = 2;
	$sezione_tab = 1;
	$tab_id = 25;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$sezione_id = -1;
	$jorel = 0;
	$text_editor = 2;
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	if($_SESSION['usertype'] == 0) $filtri = 1;
	if($_SESSION['usertype'] == 0) $searchbox = 'Cerca..';
	$documentazione_auto = 8;
	
	
	//Dati Connessione MySql Client
	$host2 = "192.168.101.235";
	$db2 = "vpnclien_db_client";
	$login2 = "vpnclien_client";
	$pwd2 = "InjuryLonerAidesTie19";
	

	


	define( 'CONNECT_CLIENT', connect($host2, $login2, $pwd2,$db2) );

	$menuvoce = ($_SESSION['usertype'] == 0) ?  'Lista  <span class="subcolor">Transazioni</span>' : 'Ricerca <span class="subcolor">Transazioni</span>';
	
	$module_menu = '
	
	 <ul>
      <li class=""><a href="./">'.$menuvoce.'</a></li>
     </ul>';
  
		
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['status_pagamento']) && check(@$_GET['status_pagamento']) >= 0) { $status_pagamento_id = check($_GET['status_pagamento']);  } else {    $status_pagamento_id = -1; }
	if(isset($_GET['rif_operazione']) && check(@$_GET['rif_operazione']) >= 0) { $rif_operazione_id = check($_GET['rif_operazione']);  } else {    $rif_operazione_id = -1; }

	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
 	 
	 $ora_da_id = (isset($_GET['ora_da'])) ? check($_GET['ora_da']) :  '00:00:00';
	 $ora_a_id = (isset($_GET['ora_a'])) ?  check($_GET['ora_a']) : '23:59:59';
	 
	 $data_da  .= ' '.$ora_da_id;
	 $data_a  .= ' '.$ora_a_id;
	 
	 
	 } else {
	
	 $ora_da_id = '00:00:00';
	 $ora_a_id = '23:59:59';
	 

	 $data_da = date('Y-m-d '.$ora_da_id.':00',time()-259200); 
	 $data_a = date('Y-m-d '.$ora_a_id.':59',time()); 
	 
	 $data_da_t = date('d/m/Y',time()-259200); 
	 $data_a_t = date('d/m/Y');

 
	 }

	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC","user ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND user = ".$_SESSION['number']." ";
	if(isset($_GET['find'])) $tipologia_main .=  " AND (userCode = '".base64_encode(check($_GET['find']))."' OR LOWER(origID) = '".check($_GET['find'])."' OR LOWER(PinCode) LIKE '".check($_GET['find'])."%' OR LOWER(Auth) = '".check($_GET['find'])."') ";
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($userid) && @$userid != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND user = $userid ";	 }

	if(isset($status_pagamento_id) && @$status_pagamento_id >= 0) {  $tipologia_main .= " AND stato_operazione = $status_pagamento_id ";	 }
	if(isset($rif_operazione_id) && @$rif_operazione_id >= 0) {  $tipologia_main .= " AND prodotto_id = $rif_operazione_id ";	 }
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca']) && $_SESSION['usertype'] == 0) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('origID','origID');	
	$tipologia_main .= ricerca_avanzata('PinCode','PinCode');	
	$tipologia_main .= ricerca_avanzata('RechargeID','RechargeID');	
	$tipologia_main .= ")";
	$tipologia_main .= " OR usercode = '".base64_encode(check($_GET['cerca']))."'";	
	}
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/data_manager/array_statiche.php');
	include('../../fl_core/category/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $causale = $data_set->data_get_items(82);
    $rif_operazione = $data_set->data_retriever('fl_sottoprodotti','label');
	$rif_operazione[0] = '';
	function select_type($who){
	
	/* Gestione Oggetto Statica */	
	$textareas = array(); 
	$select = array('rif_operazione',"proprietario","status_pagamento","causale","metodo_di_pagamento");
	$disabled = array("visite");
	$hidden = array("data_creazione","data_aggiornamento","marchio","ip","operatore");
	$radio = array();
	$text = array();
	if(@!is_numeric($_GET['action']) || $_SESSION['usertype'] > 0){
	array_push($hidden,"status_pagamento","proprietario","attivo","identificato","marchio");
	array_push($text,"note"); } else { $radio  = array("attivo");	};
	$calendario = array('data_operazione');	
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
	
	return $type;
	}
	
	
?>