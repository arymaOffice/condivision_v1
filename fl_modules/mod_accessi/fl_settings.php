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
	
	$module_title = 'Registro Accessi';
	$new_button = '';	if($_SESSION['usertype'] == 0) { 
    $module_menu = '';
	} 

	
	
	include('../../fl_core/category/cms.php');
	include('../../fl_core/category/proprietario.php');
	
	
	$tipologia_main = "WHERE id != 0";
	if(isset($_GET['cerca'])){
	$tipologia_main .= " AND utente = '".check($_GET['cerca'])."' ";
	} 
 
	


?>