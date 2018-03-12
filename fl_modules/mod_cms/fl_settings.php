<?php 
	// Variabili Modulo
	$tab_id = 37;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 50; 
	$sezione = 0;
	$jorel = 0;
	$accordion_menu = 1;
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 0;
	$parent_id = (isset($_GET['parent_id'])) ? check(@$_GET['parent_id']) : 0;
	$element = ($parent_id > 1) ? GRD('fl_cms',$_GET['parent_id']) : '';

	if(!isset($_SESSION['anagrafica'])) $_SESSION['anagrafica'] = 1;
	if(isset($_GET['ANiD']) && $_SESSION['usertype'] < 2) $_SESSION['anagrafica'] = check($_GET['ANiD']);

	$module_title = ($parent_id > 0) ? $element['titolo'] : 'Content Management System';
   
   
    $module_menu = '
	<ul>
  	   <li class=""><a href="./"><i class="fa fa-home"></i></a></li>
	    <li class=""><a href="./mod_inserisci.php?id=1&PiD='.base64_encode($parent_id).'&ANiD='.base64_encode($_SESSION['anagrafica']).'" class="create_new"><i class="fa fa-plus-circle"></i>Nuovo Elemento</a></li>
		
	  
	</ul>';
	
	if(isset($_GET['product']) && @$id != 1) { 
	$tab_div_labels = array('id'=>'Dettaglio','./mod_prezzi.php?product_id=[*ID*]'=>"Prezzi");
	}

	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("titolo ASC","attivo ASC","id ASC");
	$ordine = $ordine_mod[2];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('title','title');
	
	$tipologia_main .= ' AND parent_id = '.$parent_id;	
	if($_SESSION['anagrafica'] > 1) $tipologia_main .= ' AND anagrafica_id = '.$_SESSION['anagrafica'];	
	if(@$where != "") $tipologia_main .= $where;
	
	

	
	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $anagrafica_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale');


		
	function select_type($who){
	$textareas = array('contenuto'); 
	$select = array('anagrafica_id');
	$disabled = array();
	$hidden = array('categoria_id','priority','immagine','parent_id','category_ids',"id",'operatore','data_creazione','data_aggiornamento');
	$radio  = array('attivo');	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
		
	return $type;
	}
	
	
?>
