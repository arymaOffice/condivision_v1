<?php 
		// Variabili Modulo
	$module_uid = 12;
	check_auth($module_uid);

	$sezione_tab = 1;
	$tab_id = 23;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 500; 
	$sezione = 0;
	$jorel = 0;
	$accordion_menu = 1;
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 0;
	$prodotto_id_id = (isset($_GET['prodotto_id'])) ? check($_GET['prodotto_id']) : 0;
	
	if(isset($_GET['new'])){ new_inserimento($tabella,ROOT.$cp_admin."fl_modules/mod_prodotti/mod_inserisci.php"); }

	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("label ASC","offercode ASC","id ASC");
	$ordine = $ordine_mod[2];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('label','label');
	
	if($prodotto_id_id  > 1) $tipologia_main .= ' AND prodotto_id = '.$prodotto_id_id.' ';	
	if($where != "") $tipologia_main .= $where;
	

	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/data_manager/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$aliquota_iva = $data_set->data_retriever('fl_aliquote','descrizione');
    $module_title = 'Prodotti ';
    $prodotto_id = $data_set->data_retriever('fl_prodotti','label');
    $attivo = array('Non Attivo','Attivo');
    if($prodotto_id_id  > 1) $module_title .= @$prodotto_id[$prodotto_id_id];
    

	if($_SESSION['usertype'] == 0) {

	$module_menu = '
	   <li><a href="../mod_goservizi_tipo/">Tipi di Prodotto</a></li>
  	   <li><a href="../mod_goservizi_fornitori/">Fornitori</a></li>
  	   <li class=""><a href="../mod_goservizi/">Listino Completo</a></li>';

	} else { 	

		$module_menu = '<li class=""><a href="../mod_prodotti/">Listino Prodotti</a></li>';
	 }
		
	function select_type($who){
	$textareas = array(); 
	$select = array("aliquota_iva","prodotto_id","tipo_prodotto");
	$disabled = array();
	$hidden = array("id");
	$radio  = array("attivo");	
	$file = array("upfile");

	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$radio)){ $type = 8; }
		
	return $type;
	}
	
	
?>
