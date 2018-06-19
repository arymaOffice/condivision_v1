<?php 


ini_set('display_errors', 1);
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 



$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


if (isset($_GET['id'])) 
{
  $var = check($_GET['id']);
  $conn_id = check($_GET['conn_id']);
  $mail_conn = $mail_conns[$conn_id];
}



if(isset($_GET['delete'])){
if ((@imap_delete($mail_conn,$var)) && (@imap_expunge($mail_conn)))
{ 
//  echo "<p>Mail cancellata con successo.</p>";

}}


@imap_close($mail_conn);
header("location: ".$_SERVER['HTTP_REFERER']);
exit;




?>
