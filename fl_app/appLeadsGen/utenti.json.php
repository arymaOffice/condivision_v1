<?php 

session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');


require_once("../../fl_core/core.php"); 
$client = 1;
ini_set('display_errors',0);
error_reporting(E_ALL);
$items = array();

$query = "SELECT id,nome,cognome,telefono,email,citta FROM fl_leads_hrc WHERE id > 1 AND campagna_id = 10 AND nome != '' ORDER BY id DESC limit 100;";
$result = mysql_query($query);

while ($riga = mysql_fetch_assoc($result)){
$item = array('nome'=>$riga['nome'].' '.$riga['cognome'],'telefono'=>$riga['telefono']); 
$items[] = $item;
}

mysql_close(CONNECT);
echo json_encode($items);


?>