<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>

<?php include('../../fl_inc/testata_mobile.php'); ?>
<?php 
if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else { 
include("mod_home.php"); 
} 
include("../../fl_inc/footer.php"); ?>