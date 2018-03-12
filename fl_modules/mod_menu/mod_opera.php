<?php 

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 


if(isset($_POST['elemento'])) {



$type = check($_POST['type']);
$priority = (isset($_POST['priority'])) ? check($_POST['priority']) : '';
$elemento = check($_POST['elemento']);
$valore =  (isset($_POST['valore'])) ? check($_POST['valore']) : '';
$note =  (isset($_POST['note'])) ?  check($_POST['note']) : '';

$query = "INSERT INTO `fl_parametri` (`id`, `type`, `priority`, `elemento`, `valore`, `note`) 
VALUES (NULL, '$type', '$priority', '$elemento', '$valore', '$note');";
mysql_query($query,CONNECT);

}

mysql_close(CONNECT);
header("Location: mod_parametri.php?id=".$elemento); 
exit;
					
?>  

