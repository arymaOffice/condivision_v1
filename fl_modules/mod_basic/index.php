<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include('../../fl_inc/module_menu.php'); ?>

<?php 

if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else { 

include("mod_contact.php"); 

} ?>


