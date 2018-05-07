<?php 
	
	$module_uid = 8;
	check_auth($module_uid);
	$active = 2;
	$sezione_tab = 1;
	$tab_id = 3;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$sezione_id = -1;
	$jorel = 0;
	$text_editor = 2;
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$searchbox = 'Cerca..';
	$documentazione_auto = 8;
	$filtri = 1;
	$module_menu = '
	
	 <ul>
  	  
      <li class=""><a href="./">Registro <span class="subcolor">Commissioni</span></a> 
	     <li class=""><a href="./?operatore=1">Ricavi <span class="subcolor">Gestore</span></a>     </li>
     </ul>';
  
		
	if(isset($_GET['new'])){ new_inserimento($tabella,ROOT.$cp_admin."fl_modules/mod_commissioni/"); }
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = -1; }
	if(isset($_GET['status_fatturazione']) && check(@$_GET['status_fatturazione']) != -1) { $status_fatturazione_id = check($_GET['status_fatturazione']);  } else {    $status_fatturazione_id = -1; }
	if(isset($_GET['rif_operazione']) && check(@$_GET['rif_operazione']) >= 0) { $rif_operazione_id = check($_GET['rif_operazione']);  } else {    $rif_operazione_id = -1; }
	if(isset($_GET['tipo_operazione']) && check(@$_GET['tipo_operazione']) >= 0) { $tipo_operazione_id = check($_GET['tipo_operazione']);  } else {    $tipo_operazione_id = -1; }

	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1). ' 00:00:00';  $data_a = convert_data($_GET['data_a'],1). ' 23:59:59'; 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-d H:i',time()-9204800); 
	 $data_a = date('Y-m-d H:i',time()+86400); 
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC","user ASC","proprietario ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_creazione`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($userid) && @$userid != -1) {  $tipologia_main .= " AND proprietario = $userid ";	 } else {  $tipologia_main .= " AND proprietario > 1 "; }
	if(isset($status_pagamento_id) && @$status_pagamento_id >= 0) {  $tipologia_main .= " AND status_pagamento = $status_pagamento_id ";	 }
	if(isset($rif_operazione_id) && @$rif_operazione_id >= 0) {  $tipologia_main .= " AND prodotto_ref = $rif_operazione_id ";	 }
	if(isset($tipo_operazione_id) && @$tipo_operazione_id >= 0) {  $tipologia_main .= " AND tipo_operazione = $tipo_operazione_id ";	 }
	if(isset($status_fatturazione_id) && @$status_fatturazione_id >= 0) {  $tipologia_main .= " AND status_fatturazione = $status_fatturazione_id ";	 }
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_avanzata('estremi_di_pagamento','estremi_di_pagamento');	
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
	$select = array('status_fatturazione','tipo_operazione',"proprietario","status_pagamento","causale","metodo_di_pagamento");
	$select_disabled = array("status_fatturazione","proprietario");
	$disabled = array("visite");
	$hidden = array("rif_fattura","data_creazione","data_aggiornamento","marchio","ip","operatore");
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
	if(in_array($who,$select_disabled)){ $type = 19; }

	
	return $type;
	}
	
	
?>