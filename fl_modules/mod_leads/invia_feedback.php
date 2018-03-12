<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];



$query = "SELECT email,telefono,id,data_visita FROM $tabella WHERE id > 1 AND data_visita < (NOW() - INTERVAL 10 DAY) AND status_potential = 0";
$risultati = mysql_query($query,CONNECT);
$totx = 0;
/*
while($riga = mysql_fetch_assoc($risultati)){
	
//sms();
//smail();

$totx++;

echo $query2 = "UPDATE $tabella SET status_potential = 1 WHERE id = ".$riga['id']." AND status_potential = 0 LIMIT 1";
//mysql_query($query2,CONNECT);

}
*/

mysql_close(CONNECT);
//header("location: ".$_SESSION['POST_BACK_PAGE']);
exit;




?>
