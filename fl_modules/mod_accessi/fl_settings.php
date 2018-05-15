<?php 
	$module_uid = 10;
	check_auth($module_uid);
	$active = 4;
	$tab_id = 15;
	$tabella = $tables[$tab_id];
	$where = "";
    $ordine = "id DESC";	
	$tipologia = 0;	
	$step = 50; 
	$highslide = 0;
	$text_editor = 0;
	
	$module_title = 'Registro Acessi';
	if($_SESSION['usertype'] == 0) { 
    $module_menu = '
   	  <li><a href="'.ROOT.$cp_admin.'fl_modules/mod_account/" class="">Account</a></li>
      <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_account/mod_inserisci.php"  data-fancybox-type="iframe" class="fancybox">Nuovo Account</a></li>
	  <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_accessi/">Registro Accessi</a></li>
	   <li class=""><a href="'.ROOT.$cp_admin.'fl_modules/mod_action_recorder/">Registro Azioni</a></li>
    ';
	} 

	
	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	
	
	$tipologia_main = "WHERE id != 0";
	if(isset($_GET['cerca'])){
	$tipologia_main .= " AND utente = '".check($_GET['cerca'])."' ";
	} 
 
	


?>