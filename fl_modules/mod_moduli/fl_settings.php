<?php 
	// Variabili Modulo
	$sezione_tab = 1;
	$module_uid = 15;
	check_auth($module_uid);
	$tab_id = 12;
	$sezione = 0;
	$jorel = 0;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 100; 


  	 $module_menu = '
	
   	  <li class=""><a href="../mod_basic/action_config.php">Configurazione <span class="subcolor"></span></a></li>
	  
	    <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_moduli/">Moduli <span class="subcolor"></span></a></li>
           <li><a href="'.ROOT.$cp_admin.'fl_modules/mod_moduli/?action=1&id=1">Nuovo Modulo</a></li>


	 
     ';

	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id ASC","attivo ASC","label ASC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('label','label');

	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	$accesso_predefinito = $livello_accesso;
	$editor_wysiwyg = array('Nessuno','Avanzato','Standard');
		
	function select_type($who){
	$textareas = array(); 
	$select = array('accesso_utenza','accesso_predefinito');
	$disabled = array();
	$hidden = array("id","codice","type","ip","operatore","data_aggiornamento");
	$radio  = array("attivo",'filtri','jquery','calendari','fancybox','validatore_campi','ricerca','menu_aperto');	
	$checkbox = array('editor_wysiwyg');
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$hidden)){ $type = 5; }
	
	return $type;
	}
	
	
?>