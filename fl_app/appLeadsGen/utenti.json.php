<?php 

session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once("../fl_core/settings.php"); 
$client = 1;

$items = array();

$query = "SELECT id,nome,cognome,telefono,email,citta FROM fl_potentials WHERE id > 1 AND nome != '' ORDER BY id DESC limit 100;";
$result = mysql_query($query);

while ($riga = mysql_fetch_assoc($result)){
$item = array('nome'=>$riga['nome'].' '.$riga['cognome'],'telefono'=>$riga['telefono']); 
$items[] = $item;
}

mysql_close(CONNECT);
echo json_encode($items);


?>