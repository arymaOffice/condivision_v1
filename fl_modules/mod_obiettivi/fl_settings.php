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
	
	if(!isset($_SESSION['proprietario_id'])) $_SESSION['proprietario_id'] = $_SESSION['number'];
	if(isset($_GET['proprietario'])) $_SESSION['proprietario_id'] = check($_GET['proprietario']);
	$proprietario_id = ($_SESSION['usertype'] > 1) ? $_SESSION['number'] : $_SESSION['proprietario_id'];
	if(isset($_GET['mese'])) { $mese_sel = check($_GET['mese']); } else { $mese_sel = date("m");}
	if(isset($_GET['anno'])) { $anno_sel = check($_GET['anno']); } else { $anno_sel = date("Y");}

	$module_title = 'Obiettivi Vendita';
	if($_SESSION['usertype'] == 0) { 
    $module_menu = '
	<ul>
	  <li><a href="./" class="">Lista Obiettivi</a></li>
   <li><a href="././mod_inserisci.php?id=1&AiD='.base64_encode($proprietario_id).'" class="create_new"><i class="fa fa-plus-circle"></i> Nuovo</a></li>

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
	if(@$proprietario_id > 0) $tipologia_main .= " AND ( account_id = 0 OR account_id = ".$proprietario_id.')';
	$tipologia_main .= " AND (mese = $mese_sel AND anno =  $anno_sel)";
		
	function select_type($who){
	$textareas = array(); 
	$select = array("mese","account_id","tipo_obiettivo","aliquota");
	$disabled = array();
	$hidden = array("id","operatore","data_aggiornamento");
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