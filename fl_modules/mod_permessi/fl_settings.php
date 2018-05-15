<?php 
	// Variabili Modulo
	$sezione_tab = 1;
	$tab_id = 61;
	$sezione = 0;
	$jorel = 0;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 2000; 


  	 $module_menu = '
	<ul>
	      	  <li class=""><a href="../mod_basic/action_config.php">Configurazione <span class="subcolor"></span></a></li>
<li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_permessi/">Permessi <span class="subcolor"></span></a></li>
           <li><a href="'.ROOT.$cp_admin.'fl_modules/mod_permessi/?action=1&id=1">Nuovo </a></li>


	 
     </ul>';
	$modulo_id_id = (isset($_GET['modulo'])) ? check($_GET['modulo']) : -1;
	$account_id_id = (isset($_GET['account'])) ? check($_GET['account']) : -1;

	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id ASC","livello_accesso ASC","account_id ASC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('label','label');
	
	if($modulo_id_id  > 1) $tipologia_main .= ' AND modulo_id = '.$modulo_id_id.' ';	
	if($account_id_id  > 1) $tipologia_main .= ' AND account_id = '.$account_id_id.' ';	
	if(@$where != "") $tipologia_main .= $where;
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $modulo_id = $data_set->data_retriever('fl_moduli','label','WHERE id > 1 AND attivo = 1');

	$account_id = $proprietario;
		
	function select_type($who){
	$textareas = array(); 
	$select = array('livello_accesso','account_id','modulo_id');
	$disabled = array();
	$hidden = array("id","codice","type","ip","operatore","data_aggiornamento");
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