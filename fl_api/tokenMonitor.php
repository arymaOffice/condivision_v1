<?php

/* allowed ip
IP Server: 94.177.160.228
IP Sviluppo: 130.25.35.13
*/

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
  $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
  $ip = $_SERVER['REMOTE_ADDR'];
}

if( $ip != '94.177.160.228' &&  $ip != '130.25.35.13' ){echo 'FALSE'; exit;}

header("Access-Control-Allow-Origin: *");

ini_set('display_errors',0);
error_reporting(E_ALL);
//define('TESTING',1);

include('../fl_core/core.php'); // Variabili Modulo

if(isset($_GET['t'])){

  include_once('../fl_core/class/tokenauth.php');
  $tokenauth = new tokenauth();
  if($tokenauth->valToken($_GET['t'])){
    echo 'TRUE';
  }else{
    echo 'FALSE';
 
  }

}



exit;




?>
