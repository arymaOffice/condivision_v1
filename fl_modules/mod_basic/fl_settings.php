<?php 

	$accordion_menu = 1;
	$posta = 1;
		$text_editor = 2;

  	 $module_menu = '
	<ul>
     </ul>';



	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	include('../../fl_core/dataset/gruppo.php');
	include('../../fl_core/dataset/stato.php');
	include('../../fl_core/dataset/provincia.php');
	
	$mandatory = array("id");
	
	
	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("informazioni_pagamento","informazioni_fattura"); 
	$select = array("provincia");
	$disabled = array("visite");
	$hidden = array('data_aggiornamento');
	$radio = array('fattura_importi_zero','piattaforma_test');
	$text = array();
	$calendario = array('data_scadenza','data_emissione','data_nascita');	
	$file = array("upfile");
	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	
	return $type;
	}	
	


?>