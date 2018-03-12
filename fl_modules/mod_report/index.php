<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>

<?php include('../../fl_inc/testata.php'); ?>
<?php include('../../fl_inc/menu.php'); ?>
<?php include('../../fl_inc/module_menu.php'); ?>

<?php /* Inclusione Pagina */
if(isset($_GET['report'])) { include('mod_'.check($_GET['report']).'.php'); } else { include("mod_report.php"); 
} 
include("../../fl_inc/footer.php"); ?>