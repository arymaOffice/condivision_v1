<?php 

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 

if(isset($_POST['funzione_id'])) {

$funzione_id = check($_POST['funzione_id']);
$persona_id = check($_POST['persona_id']);
mysql_query("DELETE FROM fl_competenze_val WHERE persona_id = $persona_id;");

foreach($_POST['competenze'] as $chiave => $valore){
$valore_competenza = (isset($_POST['valutazione'.$valore])) ? check($_POST['valutazione'.$valore]) : 0;
$query = "INSERT INTO `fl_competenze_val` (`competenza_id`, `persona_id`, `valore`, `data_aggiornamento`, `operatore`) 
VALUES ('".$valore."', '".$persona_id."', '".$valore_competenza."', NOW(), '".$_SESSION['number']."')";
mysql_query($query,CONNECT);
}


mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 

exit;
}


if(isset($_POST['link'])) {

if (isset($_POST['link']) && !filter_var($_POST['link'], FILTER_VALIDATE_URL)    ) {
echo "Inserisci Link Corretto";
//json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire un link corretto"));
exit;
}

$link = check($_POST['link']);
$link_type = check($_POST['link_type']);
$label = check($_POST['label']);
$anagrafca_id = check($_POST['anagrafica_id']);
$workflow_id = check($_POST['workflow_id']);
$account_id = check($_POST['account_id']);

$query = "INSERT INTO `fl_links` (`id`, `anagrafica_id`, `workflow_id`, `account_id`, `link_type`, `label`, `link`) 
VALUES (NULL, '$anagrafca_id', '$workflow_id', '$account_id', '$link_type', '$label', '$link');";
mysql_query($query,CONNECT);

}

mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;
					
?>  
