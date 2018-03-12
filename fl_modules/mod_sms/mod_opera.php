<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
	
$esito = '';

if(isset($_POST['to'])) {

$to = '+39'.str_replace('+39','',check($_POST['to']));
$from = (isset($_POST['from'])) ? check($_POST['from']) : from;
$body = check($_POST['body']);
$esito = sms($to,$body,$from);



if($esito == 1){
	
$save_sms = "INSERT INTO `fl_sms` (`id`, `status`,`template`, `from`, `to`, `body`, `data_ricezione`, `data_creazione`) VALUES
(NULL, 1, '$from', '$to', '$body', '0000-00-00 00:00:00', NOW());";
$esito = "Messaggio inviato!";
$esito .= (!mysql_query($save_sms,CONNECT)) ? '&esito='.mysql_error() : '&success';

echo json_encode(array('action'=>'info','class'=>'green','url'=>'','esito'=>"SMS Inviato al ".$to)); 

} else { 

echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>'Errore invio'.$esito)); 

}

}


mysql_close(CONNECT);

exit;




?>
