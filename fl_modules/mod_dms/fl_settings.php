<?php 
	// Variabili Modulo
	$tab_id = 66;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 50; 
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 0;
	$tabs_div = 0;
	$searchbox = 'Cerca..';
	$tab_div_labels = array('id'=>"Proprietà");//,'#tab2'=>"Commenti",'#tab3'=>"Permessi",'#tab4'=>"Versioni");

	$module_title = 'Document Management';
    $module_menu = '
  	   <li class=""><a href="./"><i class="fa fa-home"></i></a></li>
	   <li class=""><a href="mod_opera.php?cw='.base64_encode('mod_box').'"><i class="fa fa-folder"></i> Cartelle</a></li>
       <li class=""><a href="mod_opera.php?cw='.base64_encode('mod_home').'"><i class="fa fa-bars"></i> Elenco</a></li>
	   ';
	   
	   
	   if(defined('PROGETTI'))  $module_menu .= '<li class=""><a href="../mod_cms/">Progetti</a></li>';
	
	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("id ASC, resource_type ASC, label ASC","workflow_id ASC","id DESC");
	$ordine = $ordine_mod[0];	
	
	$folder = (isset($_GET['c'])) ? base64_decode(check($_GET['c'])) : 0;
	
	if($folder > 0) { $folder_info = folder_info($folder); }
	
	
	if(!isset($_SESSION['account_id'])) $_SESSION['account_id'] = 0;
	if(isset($_GET['proprietario'])) $_SESSION['account_id'] = check($_GET['proprietario']);
	$proprietario_id = ($_SESSION['usertype'] > 1 || $folder == 2 || @$folder_info['parent_id'] == 2) ? $_SESSION['number'] : $_SESSION['account_id'];

	if(!isset($_SESSION['workflow_id'])) $_SESSION['workflow_id'] = 0;
	if(isset($_GET['workflow_id'])) $_SESSION['workflow_id'] = check($_GET['workflow_id']);
	$workflow_id = $_SESSION['workflow_id'];
	if($workflow_id > 1) { 
		$element = GRD('fl_cms',$workflow_id);
 		$module_title .= ' '.$element['titolo'];
		}

	/* RICERCA */
	$tipologia_main = "WHERE id > 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('label','label');
	
	$tipologia_main .= " AND parent_id = ".$folder;
	if(@$where != "") $tipologia_main .= $where;
	if(@$folder == 2 || @$folder_info['parent_id'] == 2) $tipologia_main .= " AND proprietario = ".$_SESSION['number'];
	if(@$proprietario_id > 0) $tipologia_main .= " AND ( account_id = 0 OR account_id = ".$proprietario_id.')';
	if($workflow_id > 1) { $tipologia_main .= " AND ( workflow_id = 0 OR workflow_id = ".$workflow_id.')'; }
	
	/* Inclusioni Oggetti Modulo */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	
	$account_id = $destinatario;
	unset($account_id[1]);
	
	function select_type($who){
	$textareas = array(); 
	$select = array("");
	$disabled = array("visite");
	$hidden = array('parent_id','resource_type','lang','file',"id","workflow_id","proprietario","account_id","operatore","data_aggiornamento");
	$radio  = array();	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
		
	return $type;
	}
	
	
?>
