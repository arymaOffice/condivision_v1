<?php 
	// Variabili Modulo
	/*$tab_id = 1;
	$tabella = $tables[$tab_id];
	$where = "WHERE id = 1 ";
    $ordine = "id DESC";	
	$tipologia = 0;	
	$operatore = $_SESSION['idh'];	
	$step = 10; 
	$txt_new = "Nuovo ";
	$txt_soggetto = "Articolo "; 
	$text_home = "Riepilogo Articoli";
	$txt_operazione = array('Gestisci ','Inserisci ','Modifica '); */
	$time = time();
	//$newitem = "INSERT INTO `$tabella` ( `id` , `codice` , `tipologia_turistica` , `categoria` , `titolo` , `sottotitolo` , `articolo` , `note` , `status` , `nazione` , `regione` , `provincia` , `citta` , `lat` , `lon` , `visite` , `data_aggiornamento` , `operatore` , `ip` ) VALUES ('', '1', '1', '1', 'Nuovo Inserimento', '', '', '', '1', '1', '1', '1', '1', '1', '1', '1', '0', '$operatore', '$time');";
	$accordion_menu = 1;
	$posta = 1;
	$text_editor = 2;

  	 $module_menu = '
	
  	  <li class=""><a href="../mod_basic/action_config.php">Configurazione <span class="subcolor"></span></a></li>
	  
	    <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_moduli/">Moduli <span class="subcolor"></span></a></li>
	    <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_permessi/">Permessi <span class="subcolor"></span></a></li>
	 
     ';
	if($_SESSION['number'] != 1) $module_menu = '';


	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');

	
	$mandatory = array("id");
	
	
	function select_type($who){		
	/* Gestione Oggetto Statica */	
	$textareas = array("informazioni_pagamento","informazioni_fattura"); 
	$select = array();
	$select_text = array("provincia","citta");
	$disabled = array("licence_key","ip_autorizzato");
	$hidden = array('data_aggiornamento');
	$radio = array('google_login','servizio_sms','fattura_importi_zero','piattaforma_test');
	$text = array();
	$calendario = array('data_scadenza','data_emissione','data_nascita');	
	$file = array("upfile");
	
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$select_text)) { $type = 12; }	
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$selectbtn)){ $type = 9; }
	if(in_array($who,$multi_selection)){ $type = 23; }
	if(in_array($who,$checkbox)){ $type = 19; }
	if(in_array($who,$datePicker)){ $type = 11; }
	if(in_array($who,$ifYesText)){ $type = 13; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$invisible)){ $type = 7; }
	
	return $type;
	}	
	


?>