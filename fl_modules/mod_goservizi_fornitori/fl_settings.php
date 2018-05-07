<?php 
	// Variabili Modulo
	$module_uid = 12;
	check_auth($module_uid);

	$sezione_tab = 1;
	$tab_id = 22;
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
	

	$module_title = 'Fornitori';
	
	if($_SESSION['usertype'] == 0) {

	$module_menu = '
	   <li><a href="../mod_goservizi_tipo/">Tipi di Prodotto</a></li>
  	   <li><a href="../mod_goservizi_fornitori/">Fornitori</a></li>
  	   <li class=""><a href="../mod_goservizi/">Listino Completo</a></li>';
  	   
	} else { 	

		$module_menu = '<li class=""><a href="../mod_prodotti/">Listino Prodotti</a></li>';

	}


	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("label ASC","codice ASC","id ASC");
	$ordine = $ordine_mod[2];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('label','label');
	
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;
	
	

	
	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/data_manager/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $categoria_prodotto = $data_set->data_retriever('fl_cat_prodotti','label');


		
	function select_type($who){
	$textareas = array(); 
	$select = array('categoria_prodotto',"tipo_prodotto");
	$disabled = array();
	$hidden = array("id");
	$radio  = array();	
	$file = array("upfile");

	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$file)){ $type = 18; }

		
	return $type;
	}
	
	
?>
