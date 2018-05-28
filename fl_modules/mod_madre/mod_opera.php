<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


// Modifica Stato se è settata $stato	
if(isset($_GET['faiqualcosa'])) { 

	//fai qualcosa e torna a casa o restituisci JSON 
}




mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;

?>
