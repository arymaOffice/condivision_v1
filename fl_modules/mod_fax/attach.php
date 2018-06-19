<?php
ini_set('display_errors', 1);
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
if($_SESSION['usertype'] != 0) {  echo "Accesso Non Autorizzato"; exit; }

if (isset($_GET['id'])) 
{
  $var = $_GET['id'];
  $conn_id = check($_GET['conn_id']);
  $mail_conn = $mail_conns[$conn_id];
  $mail_account = $mail_accounts[$conn_id];
}
if (isset($_GET['item']))
{
  $item = $_GET['item'];
}
if (isset($_GET['file']))
{
  $file = $_GET['file'];



$attach = imap_fetchbody($mail_conn,$var,$item);
if(!is_dir($allegati)) { mkdir($allegati,0777); }
$jpg = base64_decode($attach);
$jpg_open = fopen($allegati.$file, "w");

fwrite($jpg_open, $jpg);
fclose($jpg_open);
} else { }




if(isset($_GET['archivia'])){
$x = 0;
$folder = FOLDER_FAX;

if(!is_dir(DMS_ROOT.$folder.'/')) { mkdir(DMS_ROOT.$folder.'/',0777); }
// Inserisci Record Attivazione 
$intestazioni=imap_header($mail_conn, $var);
$mittente = htmlentities($intestazioni->fromaddress);
$destinatario = htmlentities($intestazioni->toaddress);
$oggetto = quoted_printable_decode($intestazioni->subject);
$data_ricezione = date('Y-m-d H:i',strtotime($intestazioni->date));

$query_insert_data = "INSERT INTO `$tabella` (`id`, `attivo`, `proprietario`, `account`, `mittente`,`identificato`, `data_ricezione`,`data_creazione`,`data_aggiornamento`, `operatore`) VALUES (NULL, '1', '0',  '$mail_account','$mittente', '4', '$data_ricezione' ,NOW(),NOW(), ".$_SESSION['number'].");";

if(mysql_query($query_insert_data,CONNECT)){
$contenuto = mysql_insert_id();
} else {  $error = 1; echo "Impossibile inserire fax in archivio!"; }


/* Sposta Allegati */
foreach($_GET['allegati'] as $chiave => $valore){

$attach = imap_fetchbody($mail_conn,$var,$valore);
$jpg = base64_decode($attach);

$allegato = $contenuto.'_'.$_GET['filenames'][$x];
if(file_exists(DMS_ROOT.$folder.'/'.$allegato)) $allegato = $contenuto."_".$allegato;

if($jpg_open = fopen(DMS_ROOT.$folder.'/'.$allegato, "w")){

fwrite($jpg_open, $jpg);
fclose($jpg_open);
$query = "INSERT INTO `fl_dms` (`id`, `resource_type`, `account_id`, `workflow_id`, `record_id`, `parent_id`, `label`, `descrizione`, `tags`, `file`, `lang`, `proprietario`, `operatore`, `data_creazione`, `data_aggiornamento`) 
VALUES (NULL, '1', '0', '42', '$contenuto', '$folder', '$allegato', 'FAX', '', '$allegato', 'it', '".$_SESSION['number']."', '".$_SESSION['number']."',NOW(),NOW());	";
	
if(!mysql_query($query,CONNECT)) {  echo "Impossibile inserire in database allegati!"; $error = "Impossibile Spostare Allegato!"; exit;  } ;

} else { fclose($jpg_open); echo "Impossibile spostare allegati!"; $error = "Impossibile Spostare Allegato!";  exit; }
$x++;
}





if(!isset($error)){
if ((@imap_delete($mail_conn,$var)) && (@imap_expunge($mail_conn))){ }
}

imap_close($mail_conn);
mysql_close(CONNECT);
header("location: ../mod_fax/mod_inserisci.php?id=".$contenuto);

} else {

imap_close($mail_conn);
header("location: ".$allegati.$file);

}

exit;
?>