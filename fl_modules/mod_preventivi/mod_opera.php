<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(isset($_POST['tipo_richiesta'])) {

$workflow_id = 71;
$tipo_richiesta = check($_POST['tipo_richiesta']);
$note = (check($_POST['interlocutore']) != '') ? '[Interlocutore: '.check($_POST['interlocutore']).'] - '.check($_POST['note']) : check($_POST['note']);
$anagrafca_id = (isset($_POST['anagrafica_id'])) ? check($_POST['anagrafica_id']) : 0;
$data_scadenza = convert_data(check($_POST['data_scadenza']),1);
$parent_id = check($_POST['parent_id']);


$query = "INSERT INTO `fl_richieste` (`id`, `marchio`,`workflow_id`,`parent_id`, `anagrafica_rel`, `tipo_richiesta`, `data_apertura`, `data_chiusura`, `data_scadenza`, `note`, `operatore`, `data_creazione`, `data_aggiornamento`) 
VALUES (NULL, '0','$workflow_id', '$parent_id', '$anagrafca_id', '$tipo_richiesta', NOW(), '0000-00-00', '$data_scadenza', '$note', '".$_SESSION['number']."', NOW(), NOW());";
mysql_query($query,CONNECT);
}

if(isset($_POST['status_preventivo'])) {
$status = check($_POST['status_preventivo']);
$query = "UPDATE `$tabella` SET `status_preventivo` = '$status', `operatore` =  '".$_SESSION['number']."' WHERE id = '$parent_id';";
mysql_query($query,CONNECT);
}

mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER']).'&esito=Operazione Registrata!'); 
exit;

?>  
