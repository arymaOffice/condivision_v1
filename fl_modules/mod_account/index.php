<?php 
$change_password = 1;

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
include('../../fl_inc/module_menu.php'); ?>



<?php /* Inclusione Pagina */ if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else { 

if($_SESSION['usertype'] > 0 || @$_SESSION['aggiornamento_password'] < -90){
include("mod_user.php"); 
}else{
include("mod_home.php"); 
}

 } ?>
