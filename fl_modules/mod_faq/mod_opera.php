<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 



// Modifica Stato se è settata $stato	
if(isset($_GET['stato_prenotazione'])) { 
$stato_prenotazione = check($_GET['stato_prenotazione']);
$id = check($_GET['id']);
$query1 = "UPDATE fl_prenotazioni SET stato_prenotazione = $stato_prenotazione WHERE `id` = $id";
mysql_query($query1,CONNECT);	
}




mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;

?>
