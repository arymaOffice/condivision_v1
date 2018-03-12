<?php

session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 


if(isset($_POST['movimento_cassa'])){

$conto_id = check($_POST['evento_id']);
$movimento_cassa = check($_POST['movimento_cassa']);
$descrizione = check($_POST['descrizione']);
$importo = check($_POST['importo']);
if($movimento_cassa > 0) $importo = -$importo;
$rif_id = ($movimento_cassa > 0) ? 'A-' : 'A+';


$query = "INSERT INTO `fl_registro_cassa` (`rif_id`,`conto_id`, `movimento_cassa`, `descrizione`,`quantita`, `importo`, `data_operazione`) 
VALUES ( '$rif_id', '$conto_id', '$movimento_cassa', '$descrizione',1, '$importo', NOW());";

mysql_query($query);



}




mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;



?>
