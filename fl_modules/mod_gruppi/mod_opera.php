<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


// Modifica Stato se è settata $stato	
if(isset($_GET['id2'])) { 
$type1 = $tab_id;	
$id1 = check($_GET['id1']);
$type2 = check($_GET['type2']);	
$id2 = check($_GET['id2']);
$valore = (isset($_GET['valore'])) ? check($_GET['valore']) : 0;


$query1 = "INSERT INTO `fl_synapsy` (`id`, `type1`, `id1`, `type2`, `id2`, `valore`)
 VALUES (NULL, '$tab_id', '$id1', '$type2', '$id2', '$valore');";
 
mysql_query($query1,CONNECT);	
}




mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;

?>
