<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 

if(isset($_GET['pagato'])){
	
	$id = check($_GET['pagato']);
	
	$query = "UPDATE $tabella SET data_aggiornamento = '".date('Y-m-d H:i')."', pagato = 1, operatore = '".$_SESSION['number']."' WHERE id = '$id';";
	
	mysql_query($query,CONNECT);
	
	}
	

mysql_close(CONNECT);
header("location: ".check($_SERVER['HTTP_REFERER']));
exit;

					
?>  
