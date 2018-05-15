<?php 
	// Variabili Modulo
	$tab_id = 66;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 50; 
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	//$text_editor = 0;
	$tabs_div = 0;
	$searchbox = 'Cerca..';
	//$tab_div_labels = array('id'=>"Proprietà");//,'#tab2'=>"Commenti",'#tab3'=>"Permessi",'#tab4'=>"Versioni");
    $module_title = 'DMS';

	

	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("label ASC,resource_type ASC","workflow_id ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	$folder = (isset($_GET['c'])) ? base64_decode(check($_GET['c'])) : 0;
	
	$folder_fileAccess = 0; //se = 1 mostra tutti i files presenti in una cartella di sistema
	if($folder > 0) { $folder_info = GRD($tabella,$folder);  $folder_fileAccess = @$folder_info['account_id']; }
	
	
	if(!isset($_SESSION['account_id'])) $_SESSION['account_id'] = 0;
	if(isset($_GET['proprietario'])) $_SESSION['account_id'] = check($_GET['proprietario']);
	$proprietario_id = ($_SESSION['usertype'] > 1 || $folder == 2 || @$folder_info['parent_id'] == 2) ? $_SESSION['number'] : $_SESSION['account_id'];
	
	$contenuto_id = 0;
	if(!isset($_SESSION['record_id'])) $_SESSION['record_id'] = 0;
	if(isset($_GET['record_id'])) $_SESSION['record_id'] = $contenuto_id = check($_GET['record_id']);


	if(!isset($_SESSION['workflow_id'])) $_SESSION['workflow_id'] = 0;
	if(isset($_GET['workflow_id'])) $_SESSION['workflow_id'] = check($_GET['workflow_id']);
	$workflow_id = $_SESSION['workflow_id'];
	if($workflow_id > 1 && isset($module_title)) { 
		$element = @GRD('fl_cms',$workflow_id);
 		$module_title .= ' '.@$element['titolo'];
		}

	/* RICERCA */
	$tipologia_main = "WHERE id > 1";
	

	if(isset($_GET['cerca'])) {
	$vars = "cerca=".check($_GET['cerca'])."&";	
	$tipologia_main  .= " AND (LOWER(label) LIKE '%".check($_GET['cerca'])."%' OR LOWER(descrizione) LIKE '%".check($_GET['cerca'])."%' OR LOWER(tags) LIKE '%".check($_GET['cerca'])."%')";
	} else { $tipologia_main .= " AND parent_id = ".$folder; }
	
	if(@$folder == 2 || @$folder_info['parent_id'] == 2 ) $tipologia_main .= " AND proprietario = ".$_SESSION['number'];
	if(@$proprietario_id > 0 && $folder_fileAccess != 1) $tipologia_main .= " AND ( account_id = 0 OR account_id = ".$proprietario_id.')';

	if($workflow_id > 1) { $tipologia_main .= " AND ( workflow_id = 0 OR workflow_id = ".$workflow_id.')'; }
	if($contenuto_id > 1) { $tipologia_main .= " AND  record_id = '".$contenuto_id."' "; }
	if($_SESSION['usertype'] > 2) { $tipologia_main .= " AND  id != 5 AND  parent_id != 5 "; }

	
	/* Inclusioni Oggetti Modulo */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$modulo = $data_set->data_retriever('fl_moduli','label','','label ASC');

	
	$account_id = $destinatario;
	unset($account_id[1]);
	
	function select_type($who){
	$textareas = array(); 
	$select = array("");
	$disabled = array("visite");
	$hidden = array("data_creazione",'record_id','parent_id','resource_type','lang','file',"id","workflow_id","proprietario","account_id","operatore","data_aggiornamento");
	$radio  = array();	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
		
	return $type;
	}
	
	$module_title = @$folder_info['label'].' '.@$proprietario[$proprietario_id];
	$module_menu = '';
    if(defined('PROGETTI'))  $module_menu .= '<li class=""><a href="../mod_cms/">Progetti</a></li>';
	$module_menu .= '
	<li><a href="mod_opera.php?cw='.base64_encode('mod_box').'"><i class="fa fa-folder"></i> Cartelle</a></li>
	<li><a href="mod_opera.php?cw='.base64_encode('mod_home').'"><i class="fa fa-bars"></i> Elenco</a></li>';
	
	
	//if($_SESSION['usertype'] < 2) $module_menu .= '<li><a href="../../?d='.base64_encode('document_dashboard').'"><i class="fa fa-upload"></i> Caricamento massivo</a></li>';


	$new_button = ($folder == 3 || ($folder > 2 && $_SESSION['usertype'] < 2)) ?   '<a  href="mod_inserisci.php?n&AiD='.base64_encode($proprietario_id).'&PiD='.base64_encode($folder).'" style="color: gray"><i class="fa fa-plus-circle"></i> </a>' : '';


	
?>
