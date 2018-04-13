<?php 
	
	//$active = 'callcencer';
	//$sezione_tab = 1;
	
	$modulo_uid = 26;
	$parametri_modulo = check_auth($modulo_uid); /* Nuova preconfigurazione dei moduli (da convertire in classe?) */
	
	
	// Variabili Modulo
	$permesso    = $parametri_modulo['permesso'];
	$tab_id      = $parametri_modulo['tab_id'];
	$tabella     = $tables[$tab_id];
	$tabella     = $tabella.' AS tb1 LEFT JOIN fl_veicoli AS tb2 ON tb1.id = tb2.parent_id';
	$select      = "tb1.*,tb2.*,tb1.id as id,tb2.id as did, tb1.data_creazione as data_creazione";
	$ordine      =  $parametri_modulo['ordine_predefinito']; 
	$step        = (isset($_SESSION['step'])) ? $_SESSION['step'] : $parametri_modulo['risultati_pagina']; 
	//$text_editor = $parametri_modulo['editor_wysiwyg'];
	$jquery      = $parametri_modulo['jquery'];
	$fancybox    = $parametri_modulo['fancybox'];
	$filtri      = $parametri_modulo['filtri'];
	$toggleOn    = $parametri_modulo['menu_aperto'];
	$calendar    = $parametri_modulo['calendari'];
	if($parametri_modulo['ricerca'] == 1) $searchbox = $parametri_modulo['placeholder_ricerca'];
	$checkRadioLabel = '<i class="fa fa-check-square"></i><i class="fa fa-square-o"></i>'; // Pulsante checkbox radio button Toggle apple
 	$dateTimePicker = 1;


	
?>