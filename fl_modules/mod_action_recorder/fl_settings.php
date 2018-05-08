<?php 
	// Variabili Modulo
	$module_uid = 11;
	check_auth($module_uid);

	$active = 4;
	$tab_id = 57;
	$tabella = $tables[$tab_id];
	$where = "";
    $ordine = "id DESC";	
	$tipologia = 0;	
	$step = 50; 
	$highslide = 0;
	$text_editor = 0;
	
	$module_title = 'Registro Azioni';
	$new_button = '';
	if($_SESSION['usertype'] == 0) { 
    $module_menu = '
	
   	  <li><a href="'.ROOT.$cp_admin.'fl_modules/mod_account/" class="">Account</a></li>
	  <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_accessi/">Registro Accessi</a></li>
	   <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_action_recorder/">Registro Azioni</a></li>
    ';
	} 

	
	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	
	
	$tipologia_main = "WHERE 1";
	if(isset($_GET['cerca'])){
	$tipologia_main .= " AND  utente = '".check($_GET['cerca'])."' ";
	} 
	


?>