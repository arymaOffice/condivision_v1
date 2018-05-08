<?php 

header("Access-Control-Allow-Origin: *");

ini_set('display_errors',0);
error_reporting(E_ALL);
define('NOSSL',1);
include('../fl_core/core.php'); // Variabili Modulo



$msg = '';
foreach($_REQUEST as $chiave => $valore){


$msg .= $chiave .' = '.$valore.' / ';

}

echo $msg;
mail('supporto@aryma.it','Invio dati',$msg);
mail('raffylauciello2@gmail.com','Invio dati',$msg);



?>