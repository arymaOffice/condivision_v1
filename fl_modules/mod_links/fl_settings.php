<?php 
	
	
    // Variabili Modulo
	$tab_id = 13;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 10; 
    $ordine_mod = array("id DESC");
	$_SESSION['active'] = 'centrodati';
	$ordine = $ordine_mod[0];	
	$cat = 0;
	
	//aggiustare ordinamento in get

	$where = "WHERE id != 1  ";
	
	
	include("../../fl_core/dataset/items_rel.php");
	include("../../fl_core/dataset/array_statiche.php");
	require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati
	$data_set = new ARY_dataInterface();
	
	$categorie = $data_set->data_retriever($tables[109],'label',"WHERE id != 1");

	$module_title = $categorie[$_GET['categoria_id']];

	$link_cat = $data_set->data_retriever($tables[135],'descrizione',"WHERE id != 1 AND attivo = 1"); //Crea un array con i valori X2 della tabella X1
	
	
	$module_menu = '';
	$new_button = ($_SESSION['usertype'] > 1) ? '' : '<a href="#" onclick="display_toggle(\'#filtri\');" style="color: gray"> <i class="fa fa-plus-circle"></i></a>';
	

	
?>