<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 





if(isset($_POST['anagrafica_id'])) {

$anagrafica_id = check($_POST['anagrafica_id']);
$product_id = check($_POST['product_id']);
$status_assegnazione = check($_POST['status_assegnazione']);
$valore = check($_POST['valore']);


$sql = "
INSERT INTO `fl_magazzino_assegnazioni` (`id`, `anagrafica_id`, `prodotto_id`, `valore`, `status_assegnazione`, `centro_supporto`, `data_creazione`, `data_aggiornamento`, `operatore`) 
VALUES (NULL,  '$anagrafica_id', '$product_id', '$valore', '$status_assegnazione','', NOW(), NOW(), ".$_SESSION['number'].");";

mysql_query($sql);

}



mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;

?>
