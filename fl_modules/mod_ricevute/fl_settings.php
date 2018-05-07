<?php 
	// Variabili Modulo
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
	
	if(isset($_GET['new'])){ new_inserimento($tabella,ROOT.$cp_admin."fl_modules/mod_linee_prodotti/mod_inserisci.php"); }

	$module_title = 'Prodotti';
    $module_menu = '
	<ul>
  	   <li class=""><a href="./">Linee Prodotti</a></li>
	   <li class=""><a href="./?new">Nuova Linea Prodotto</a></li>
	</ul>';
	
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


		
	function select_type($who){
	$textareas = array(); 
	$select = array("tipo_prodotto");
	$disabled = array();
	$hidden = array("id");
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
