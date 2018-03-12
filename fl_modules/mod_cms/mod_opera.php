<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
require('../../fl_core/settings.php'); 



if(isset($_POST['price_type'])) {

if (isset($_POST['link']) && !filter_var($_POST['link'], FILTER_VALIDATE_URL)    ) {
echo "Inserisci Link Corretto";
//json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire un link corretto"));
exit;
}

$link = check($_POST['link']);
$price_type = check($_POST['price_type']);
$label = check($_POST['label']);
$anagrafca_id = check($_POST['anagrafica_id']);
$workflow_id = check($_POST['workflow_id']);
$account_id = check($_POST['account_id']);

$query = "INSERT INTO `fl_links` (`id`, `anagrafica_id`, `workflow_id`, `account_id`, `link_type`, `label`, `link`) 
VALUES (NULL, '$anagrafca_id', '$workflow_id', '$account_id', '$link_type', '$label', '$link');";
mysql_query($query,CONNECT);

}

mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;

?>
