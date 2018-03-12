<?php 

header("Access-Control-Allow-Origin: *");

ini_set('display_errors',0);
error_reporting(E_ALL);
define('NOSSL',1);
include('../fl_core/core.php'); // Variabili Modulo
 
include "webservice.php";
$webservice = new webservice;


$dataset = (isset($_GET)) ? $_GET : $_POST;
$message = $_SERVER['REQUEST_URI']."\r\n\r\n<br>";
//mail('michelefazio@aryma.it','APP',$message,intestazioni);


foreach($dataset as $chiave=>$valore){ $message .= $chiave." = ".$valore."\r\n<br>"; }
function mandatory_fileds ($array){
foreach($array as $chiave=>$valore){
if(!isset($_GET[$valore])) { echo json_encode(array('esito'=>0,'info_txt'=>"Manca $valore"));  exit; } 
}
}


if(isset($dataset['usr_sign_up'])){
sleep(3);
$webservice->token = check($dataset['token']);
$webservice->signup();

}




if(isset($dataset['app_login'])){
mandatory_fileds(array('time','request_id'));

session_cache_limiter( 'private_no_expire' );
session_cache_expire(time()+5259200); 
session_start();		

$time = check($dataset['time']);
$request_id = check($dataset['request_id']);

$correct = sha1($time.$webservice->secret);
if($request_id != $correct && !isset($_GET['demo']) && $webservice->demo == true) {
echo json_encode(array('esito'=>0,'info_txt'=>"Request Id Errato"));
exit;
}


$deviceuid = check($dataset['request_id']);
$_SESSION['deviceuid'] = $deviceuid;
echo json_encode(array('token'=>session_id(),'esito'=>1,'info_txt'=>'OK'));
exit;

}


if(isset($dataset['usr_login'])){
mandatory_fileds(array('token','user','password'));
$webservice->user = check($dataset['user']);
$webservice->password = check($dataset['password']);
$webservice->token = check($dataset['token']);
$webservice->do_login();
foreach($webservice->contenuto as $chiave => $valore) { $_SESSION[$chiave] = $valore; } ;
exit;
}

if(isset($dataset['lead_info'])){
mandatory_fileds(array('token'));
$webservice->token = check($dataset['token']);
$webservice->lead_info(check($dataset['user_id']));
}

if(isset($dataset['get_items'])){
mandatory_fileds(array('token','item_rel'));
$webservice->token = check($dataset['token']);
$webservice->get_items(check($dataset['item_rel']));
}


if(isset($dataset['insert_lead'])){
mandatory_fileds(array('token'));
$webservice->token = check($dataset['token']);
$webservice->insert_lead();
}






if(isset($dataset['usr_logout'])){

mandatory_fileds(array('token','usr_id'));
if(isset($dataset['token'])) $webservice->token = check($dataset['token']);

// Logout
session_start();
session_unset();
session_destroy();
setcookie('user','');
echo json_encode(array('esito'=>1,'info_txt'=>"Logged out.")); exit;

}


if(isset($dataset['get_page'])){

mandatory_fileds(array('token','page_id'));
$webservice->token = check($dataset['token']);
$webservice->page_id = check($dataset['page_id']);
$webservice->get_page();

}

if(isset($dataset['lost_password'])){
mandatory_fileds(array('token','email'));
if(isset($dataset['token'])) $webservice->token = check($dataset['token']);
if(isset($dataset['email'])) $webservice->email = check($dataset['email']);
$webservice->send_login();
exit;
}


echo json_encode(array('esito'=>0,'info_txt'=>"Specifica un metodo o autentica client"));
exit;

?>