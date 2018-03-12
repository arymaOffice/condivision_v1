<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>



<?php if(!isset($_GET['external'])) include('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/menu.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/module_menu.php'); ?>



<?php /* Inclusione Pagina */ if(isset($_GET['action'])) { 
include($pagine[$_GET['action']]); } else {
	
	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

($_SESSION['usertype'] == 0) ? include("mod_home.php") : include("mod_visualizza.php") ; 

} ?>


<?php if(!isset($_GET['external'])) include("../../fl_inc/footer.php"); ?>