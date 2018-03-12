<?php 
	// Variabili Modulo
	$active = 6;
	$tab_id = 22;
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
	$searchbox = "Cerca...";
	$filtri = 1;
	$folder = DMS_PUBLIC.'linee_prodotti/';




	/*Impostazione automatica da tabella */
	$campi = gcolums($tabella); //Ritorna i campi della tabella
	$tipologia_main = gwhere($campi,'WHERE id != 1 ','');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
    if(isset($_GET['potential_id']))  $tipologia_main .= ' AND potential_id = '.check($_GET['potential_id']).' '; 
	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('codice','descrizione','label','categoria_prodotto');
	$ordine_mod = array("priority ASC"); // Tipologie di ordinamento disponobili 
	$ordine = $ordine_mod[0];



	
	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $categoria_prodotto = $data_set->data_retriever('fl_cat_prodotti','label','WHERE id > 1 ','label ASC');


		
	function select_type($who){
	$textareas = array('descrizione'); 
	$select = array('categoria_prodotto');
	$disabled = array();
	$hidden = array("id",'data_creazione','data_aggiornamento',"tipo_prodotto");
	$radio  = array('attivo');
	$file  = array('upfile');	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }

	return $type;
	}
	
	$module_title = 'Linee di Prodotti';
	if(isset($_GET['categoria_prodotto']))  $module_title = 'Linee per la categoria '.$categoria_prodotto[$_GET['categoria_prodotto']];
    $module_menu = '<li class=""><a href="../mod_cat_prodotti/">Categorie Prodotti</a></li>';
	if(isset($_GET['categoria_prodotto'])) $new_button = '<a href="./mod_inserisci.php?id=1&categoria_prodotto='.check($_GET['categoria_prodotto']).'" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a>';

?>
