<?php 

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
if($_SESSION['idh'] != $_SERVER['REMOTE_ADDR']) { echo "Non autorizzato ".$_SESSION['idh']." NOT VALID ".$_SERVER['REMOTE_ADDR']; exit; }
require('../../fl_core/settings.php'); 



if(isset($_POST['ruolo'])) {

$workflow_id = check($_POST['workflow_id']);
$ruolo = check($_POST['ruolo']);
$anagrafica_id = (isset($_POST['anagrafica_id'])) ? check($_POST['anagrafica_id']) : 0;
$parent_id = check($_POST['parent_id']);


$query = "INSERT INTO `fl_associa_persone` (`id`, `workflow_id`, `parent_id`, `ruolo`, `anagrafica_id`) VALUES (NULL, '$workflow_id', '$parent_id','$ruolo', '$anagrafica_id');";
mysql_query($query,CONNECT);

}

mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;
					
?>  

