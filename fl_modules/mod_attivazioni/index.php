<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>

<?php include('../../fl_inc/testata.php'); ?>
<?php include('../../fl_inc/menu.php'); ?>
<?php include('../../fl_inc/module_menu.php'); ?>

<?php 

if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else { 
($_SESSION['usertype'] > 0) ? include("mod_user.php") : include("mod_home.php");
}


include("../../fl_inc/footer.php"); ?>