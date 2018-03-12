<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
if($_SESSION['usertype'] != 0 || !isset($_SESSION['account_manage']) ) exit;
require('../../fl_core/core.php'); 




$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


if(isset($_GET['modulo']) && isset($_SESSION['account_manage']) ) {
	
$modulo = check($_GET['modulo']);
$valore = check($_GET['valore']);

$query_x = "UPDATE fl_permessi SET livello_accesso = $valore WHERE modulo_id = $modulo AND account_id = ".$_SESSION['account_manage'];

if(mysql_query($query_x,CONNECT)) { 
	action_record('Permesso $modulo:$valore ','fl_anagrafica',$_SESSION['account_manage'],base64_encode($query_x));
}
 
}


mysql_close(CONNECT);
header("location: ".$_SERVER['HTTP_REFERER']);
exit;





?>
