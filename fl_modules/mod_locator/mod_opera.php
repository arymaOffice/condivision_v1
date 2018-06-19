<?php 


ini_set('display_errors', 1);
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 



$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


header("location: ".$_SERVER['HTTP_REFERER']);
exit;




?>
