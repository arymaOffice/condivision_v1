<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
include('../../fl_inc/module_menu.php');

 /* Inclusione Pagina */ if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else {
	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
 
 include("mod_home.php"); }

if(isset($_SESSION['synapsy'])) {
	echo '<div class="info_alert right">
	'.$_SESSION['synapsy_info'].' <br>Clicca icona <i class="fa fa-link" aria-hidden="true"></i> del lead a cui vuoi associare</a><a href="mod_opera.php?unset" onClick="return conferma(\'Annullare?\');" style="color: white;" class="elimina">x</a></div>';
} 


 ?>