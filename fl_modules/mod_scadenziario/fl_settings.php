<?php 
	
	// Variabili Modulo
	$active = 2;
	$sezione_tab = 1;
	$tab_id = 25;
	$tabella = $tables[$tab_id];
	$select = "* ";
	$step = 100; 
	$sezione_id = -1;
	$jorel = 0;
	$text_editor = 2;
	$jquery = 1;
	$fancybox = 1;
	$searchbox = 'Search';
	$calendar = 1;
	$documentazione_auto = 18;

	if(isset($_GET['new'])){ new_inserimento($tabella,'./'); }

	$module_title = '';
    $module_menu = '
	<ul>
  	  <li class=""><a href="">Scadenze <span class="subcolor"> F24</span></a>
	  <li class=""><a href="">Scadenze <span class="subcolor"> Pagamenti</span></a>
	  <li class=""><a href="">Scadenze <span class="subcolor"> Manutenzioni</span></a>
	  </ul>';
		

  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 $data_da = date('Y-m-d',time()); 
	 $data_a = date('Y-m-d',time()+86400); 
	 
	 $data_da_t = date('d/m/Y',time()); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("data_scadenza DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('descrizione','descrizione');
	$tipologia_main .= ricerca_avanzata('importo_totale','importo_totale');
	$tipologia_main .= ")";
	}
	
		
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	
	
	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("descrizione"); 
	$select = array('proprietario','item_rel');
	$disabled = array();
	$hidden = array("stato_f24","data_creazione","data_aggiornamento",'marchio','operatore','ip');
	$radio = array('fatto');
	$text = array();
	$calendario = array('data_scadenza');	
	$file = array('upfile');
	$timer = array();
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$timer)){ $type = 7; }
	return $type;
	}
	
	
?>