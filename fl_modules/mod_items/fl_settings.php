<?php 
	// Variabili Modulo
	$sezione_tab = 1;
	$tab_id = 49;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 2000; 
	$sezione = 0;
	$jquery = 1;
	$text_editor = 2;

	$tab_div_labels = array('id'=>"Elemento");
	
	if(isset($id) && @$id != 1) { 
	$tab_div_labels['./mod_parametri.php?id=[*ID*]'] = "Parametri";
	}


	if(isset($_GET['item_rel']) && is_numeric($_GET['item_rel'])) { $sezione_id = check($_GET['item_rel']); $rel = $sezione;  } else {  $sezione_id = 0;}

	$module_title = 'Liste Parametri ';
    $module_menu = ' <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_items/">Liste Parametri </a></li>
    <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_campagne/">Canali CRM</a></li>
    <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_campagne_attivita/">Attività CRM</a></li>';

        if(isset($sezione_id) && $sezione_id != 0) {  
		$new_button = '<a class="" style="color: gray" href="'.ROOT.$cp_admin.'fl_modules/mod_items/mod_inserisci.php?id=1&item_rel='.$sezione_id.'" ><i class="fa fa-plus-circle"></i></a>';
		$item = GRD($tabella, $sezione_id);
		$module_title =  "Valori per ".$item['label'];
		} else if($_SESSION['usertype'] == 0){
	 	$new_button = '<a class="" style="color: gray"  href="'.ROOT.$cp_admin.'fl_modules/mod_items/mod_inserisci.php?item_rel=0&amp;id=1" ><i class="fa fa-plus-circle"></i></a>';
		}
	
	 
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("label ASC","relation ASC","id ASC");
	$ordine = $ordine_mod[2];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('label','label');
	if(isset($sezione_id)) $sezione = " AND item_rel = $sezione_id ";
	
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;
	
	
	
	/* Dati */
	$txt_new = "Nuovo ";
	$h_home = "Mostra ";
	$txt_soggetto = "Categoria "; 
	$text_home = "Riepilogo Categorie";
	$txt_operazione = array('Gestisci ','Modifica ','Modifica '); 
	$time = time();
	$operatore = $_SESSION['number'];
	$ip = $_SERVER['REMOTE_ADDR'];
	
	
	/* Inclusioni Oggetti Categorie */
	
	include('../../fl_core/dataset/categoria.php');
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	include('../../fl_core/dataset/items_rel.php');
	$item_rel = get_items(0);
	

	
		
	function select_type($who){
	$textareas = array("articolo_di_categoria","articolo"); 
	$select = array("relation","item_rel");
	$disabled = array("visite");
	$hidden = array("id","codice","type","ip","operatore","data_aggiornamento");
	if(isset($_GET['item_rel']) && check($_GET['item_rel']) != 0) { array_push($hidden,"chiave"); }else{ array_push($hidden,"item_rel"); }
	$radio  = array("attiva");	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
		
	return $type;
	}
	
	
?>
