<?php 
	// Variabili Modulo
	$module_uid = 14;
	check_auth($module_uid);

	
	$tab_id = 58;
	$tabella = $tables[$tab_id];
	$where = "";
    $ordine = "id DESC";	
	$tipologia = 0;	
	$step = 50; 
	$highslide = 0;
	$text_editor = 0;
	
	$module_title = 'Aliquote IVA';
	
	
	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	
	
	$tipologia_main = "WHERE id > 1";
	if(isset($_GET['cerca'])){
	$where = ricerca_semplice('descrizione','descrizione');
	$tipologia_main .= $where.") ";
	} 
	
	function select_type($who){
	$textareas = array(); 
	$select = array("prodotto_id","tipo_prodotto");
	$disabled = array();
	$hidden = array("id");
	$radio  = array("attivo");	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
		
	return $type;
	}

?>