<?php 
	// Variabili Modulo
	$sezione_tab = 1;
	$tab_id = 24;
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
	$folder = DMS_PUBLIC.'categorie_prodotti/';
	$tab_div_labels = array('id'=>'Categoria','ordinamento_predefinito'=> "Caratteristiche dei Prodotti");

	$module_title = 'Categorie di prodotto';
    $module_menu = '
  	  
	   <li class=""><a href="../mod_linee_prodotti/">Linee Prodotti</a></li>
	  
	';

	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("parent_id ASC","informazioni ASC","id ASC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('label','label');
	
	

	
	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$parent_id = $data_set->data_retriever('fl_cat_prodotti','label',"WHERE id != 1 AND parent_id < 2",'id ASC');
	$parent_id[0] = "Nessuno";
	$ordinamento_predefinito = array('Nessuno','Valore1','Valore2','Valore3','Valore4','Valore5','Valore6','Valore7','Valore8','Valore9','Valore10','Valore11');
		
	function select_type($who){
	$textareas = array('informazioni'); 
	$select = array('parent_id','ordinamento_predefinito');
	$disabled = array();
	$hidden = array("id");
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
