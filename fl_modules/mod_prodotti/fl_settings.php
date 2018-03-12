<?php 
	// Variabili Modulo
	$sezione_tab = 1;
	$tab_id = 23;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 100; 
	$sezione = 0;
	$jorel = 0;
	$accordion_menu = 1;
	$jquery = 1;
	$filtri  = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 1;
	$prodotto_id_id = (isset($_GET['prodotto_id'])) ? check($_GET['prodotto_id']) : 0;
	if(isset($_GET['categoria_id'])) $_SESSION['categoria_id'] =  check($_GET['categoria_id']);
	$folder = DMS_PUBLIC.'prodotti/';
	$folder2 = DMS_PUBLIC.'disegni/';

	
	/*Impostazione automatica da tabella */
	$campi = gcolums($tabella); //Ritorna i campi della tabella
	$tipologia_main = gwhere($campi,'WHERE id != 1 ','');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
    if(isset($_GET['potential_id']))  $tipologia_main .= ' AND potential_id = '.check($_GET['potential_id']).' '; 
	
	//Filtri di base (da strutturare quelli avanzati)
	$basic_filters = array('prodotto_id','label','descrizione');
	$ordine_mod = array("id DESC"); // Tipologie di ordinamento disponobili 
	$ordine = $ordine_mod[0];

	

	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
    $prodotto_id = $data_set->data_retriever($tables[22],'label');
    $produttore = $data_set->get_items_key('produttore');
	$vetrina = array('Nessuna','In Primo piano');
	$module_title = 'Linea ';
	$module_title .= @$prodotto_id[$prodotto_id_id];
    $module_menu = '<li class=""><a href="../mod_linee_prodotti">Linee Prodotti</a><ul>';
	$new_button = '<a href="./mod_inserisci.php?id=1&prodotto_id='.@$prodotto_id_id.'" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a>';

		
	function select_type($who){
	$textareas = array('descrizione'); 
	$select = array('produttore','vetrina','produttore',"prodotto_id");
	$disabled = array();
	$radio  = array('quantita_variabile','richiedi_note','prezzo_variabile','attivo');
	$file  = array('upfile');	
	$hidden = array('data_creazione','data_aggiornamento',"codice","aliquota_iva","id","tipo_prodotto");
	$multi_selection = array();	
	if(defined('MULTI_PRODUTTORE')) $multi_selection = array("produttore");	

	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$multi_selection)){ $type = 23; }
	if(in_array($who,$hidden)){ $type = 5; }
		
	return $type;
	}
	
?>
