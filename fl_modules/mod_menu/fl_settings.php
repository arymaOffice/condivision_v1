<?php 
	// Variabili Modulo
	$sezione_tab = 1;
	$tab_id = 73;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 2000; 
	$sezione = 0;
	$jquery = 1;
	$text_editor = 2;

	$tab_div_labels = array('id'=>"Elemento");
	

	if(isset($_GET['item_rel']) && is_numeric($_GET['item_rel'])) { $sezione_id = check($_GET['item_rel']); $rel = $sezione;  } else {  $sezione_id = 0;}
	if(isset($_GET['modulo_id']) && is_numeric($_GET['modulo_id'])) { $modulo_id = check($_GET['modulo_id']);  } else {  $modulo_id = 0;}
	if(isset($_GET['menu_id']) && is_numeric($_GET['menu_id'])) { $menu_id = check($_GET['menu_id']);  } else {  $menu_id = 0;}
	
	$module_title = 'Gestore dei Menù';
    $module_menu = ' <li class=""><a href="./">Menù</a></li>';

	
	 
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 20; 
	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("label ASC","id ASC");
	$ordine = $ordine_mod[0];	
	
		
	/* RICERCA */
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('label','label');
	if(isset($sezione_id)) $sezione = " AND item_rel = $sezione_id ";
	if(@$sezione != "") $tipologia_main .= $sezione;
	if(@$modulo_id != "") $tipologia_main .= " AND modulo = $modulo_id ";
	if(@$where != "") $tipologia_main .= $where;

	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$modulo = $data_set->data_retriever('fl_moduli','label','','label ASC');
	$item_rel = $data_set->data_retriever('fl_menu','label','WHERE item_rel = 0','label ASC');

	
		
	function select_type($who){
	$textareas = array("articolo_di_categoria","articolo"); 
	$select = array("relation","item_rel");
	$disabled = array("visite");
	$hidden = array("menu_id","id","codice","type","ip","operatore","data_aggiornamento","modulo");
	if(check($_GET['item_rel']) != 0) { array_push($hidden,"chiave"); }else{ array_push($hidden,"item_rel"); }
	$radio  = array("attiva");	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
		
	return $type;
	}
	
	$res = mysql_query('SHOW TABLES LIKE '.$tabella.'',CONNECT);

	
	if(mysql_affected_rows() == 0) {
		echo "Modulo non inizializzato.<br>";
	  $install_tb = "CREATE TABLE IF NOT EXISTS `fl_menu` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`attiva` int(1) NOT NULL DEFAULT '1',
			`menu_id` tinyint(4) NOT NULL,
			`modulo` int(11) NOT NULL,
			`item_rel` int(11) NOT NULL DEFAULT '0',
			`label` varchar(100) NOT NULL DEFAULT '',
			`menu_link` varchar(255) NOT NULL,
			PRIMARY KEY (`id`)
		  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		  $insert_rc = "INSERT INTO `fl_menu` (`id`, `attiva`, `menu_id`, `modulo`, `item_rel`, `label`, `menu_link`) VALUES (1, 1, 0, 0, 0, '', '');";
	if(mysql_query($install_tb,CONNECT)){
	if(mysql_query($insert_rc,CONNECT)) echo "Modulo inizializzato. Premi F5.";
	
	} else { echo mysql_error(); }
	mysql_close(CONNECT);
	exit; } 

?>
