<?php 
	// Variabili Modulo
	$module_uid = 14;
	check_auth($module_uid);

	
	$tab_id = 10;
	$tabella = $tables[$tab_id];
	$where = "";
    $ordine = "id DESC";	
	$tipologia = 0;	
	$step = 50; 
	$highslide = 0;
	$text_editor = 0;
	
	$module_title = 'Obiettivi Vendita';
	if($_SESSION['usertype'] == 0) { 
    $module_menu = '
	<ul>
	  <li><a href="./" class="">Lista Obiettivi</a></li>
   <li><a href="./?action=1&id=1" class="create_new"><i class="fa fa-plus-circle"></i> Nuovo</a></li>

    </ul>';
	} 

	
	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	include('../../fl_core/dataset/items_rel.php');
	$tipo_obiettivo = get_items_key("tipo_obiettivo");	
	$account_id = $proprietario;
	$aliquota = array('10');

	
	$tipologia_main = "WHERE id > 1";
	if(isset($_GET['cerca'])){
	$where = ricerca_semplice('note','note');
	$tipologia_main .= $where.") ";
	} 
	
	function select_type($who){
	$textareas = array(); 
	$select = array("mese","account_id","tipo_obiettivo","aliquota");
	$disabled = array();
	$hidden = array('resource_type','lang','file',"id","workflow_id","proprietario","parent_id","account_id","operatore","data_aggiornamento");
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