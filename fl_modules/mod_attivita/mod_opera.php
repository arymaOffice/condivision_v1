<?php 



require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$baseref = explode('?', $_SERVER['HTTP_REFERER']);

$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


// Modifica Stato se è settata $stato	
if(isset($_GET['pubblica'])) { 

if(!is_numeric($_GET['id'])) exit;

$id = $_GET['id'];	
$stato = check($_GET['pubblica']);

$query1 = "UPDATE $tabella SET fatto = '$stato' WHERE id = '$id'";
mysql_query($query1, CONNECT);	

$activity = GRD('fl_attivita',$id);
$admin_num = GRD('fl_account',$activity['proprietario']);
$operatore_num = GRD('fl_account',$activity['operatore']);
//sms($admin_num['telefono'],$operatore_num['nominativo'].' has done: '.$activity['oggetto'].' on '.date('H:i d/m/Y'));

mysql_close(CONNECT);

header("Location: $rct?$vars");
exit;	

}



?>
