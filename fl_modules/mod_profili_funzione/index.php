<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 include('../../fl_inc/module_menu.php'); ?>

<?php 
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
include("mod_home.php"); 
?>


