<?php

session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 


if(isset($_POST['movimento_caparra'])){

$evento_id = check($_POST['evento_id']);
$movimento_caparra = check($_POST['movimento_caparra']);
$descrizione = check($_POST['descrizione']);
$importo = check($_POST['importo']);
if($movimento_caparra > 0) $importo = -$importo;

 $query = "INSERT INTO `fl_registro_caparre` (`evento_id`, `movimento_caparra`, `descrizione`, `importo`, `data_operazione`) 
VALUES ( '$evento_id', '$movimento_caparra', '$descrizione', '$importo', NOW());";

mysql_query($query);

}


mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;



?>
