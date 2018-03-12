<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 





if(isset($_POST['id_materia'])) {

$id_materia = check($_POST['id_materia']);
$fornitore = check($_POST['fornitore']);
$prezzo_unitario = check($_POST['prezzo_unitario']);
$data_validita = (isset($_POST['data_validita'])) ? dbdatetime(check($_POST['data_validita'])) : 0;
$valuta = check($_POST['valuta']);
$data_scadenza = '2050-31-12';

$sql = "INSERT INTO `fl_listino_acquisto` (`id`, `id_materia`, `fornitore`, `valuta`, `prezzo_unitario`, `data_validita`, `data_scadenza`, `data_creazione`, `data_aggiornamento`, `operatore`) 
VALUES (NULL, '$id_materia', '$fornitore', '$valuta', '$prezzo_unitario', '$data_validita', '$data_scadenza', NOW(), NOW() , ".$_SESSION['number'].");";


mysql_query($sql);


}




mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;

?>
