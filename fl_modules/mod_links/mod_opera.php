<?php 

// Controllo Login
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo   


$ref = $_SERVER['HTTP_REFERER'];
$baseref = explode('?', $ref);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


//Inserisci Aggiorna
if(isset($_POST['link'])) { 
if(isset($_POST['relation'])){ $relation = (is_numeric($_POST['relation'])) ? $_POST['relation'] : exit; } else { $relation = "0"; };
$obbligatorio = array('link');
foreach($_POST as $chiave => $valore){

if(in_array($chiave,$obbligatorio)) {
if($valore == ""){
@mysql_close(CONNECT);
$chiave = ucfirst($chiave);
header("Location: $rct?$val&action=9&&ok=1&esito=Inserire valore per il campo $chiave");
exit;
exit;}}}


$web = check($_POST['link']);
$cat = check($_POST['cat']); 
$disponibilita_link = check($_POST['disponibilita_link']);
$tipo_link = check($_POST['tipo_link']);
$sottocategoria = check($_POST['sottocategoria']);
$sezione_link = check($_POST['sezione_link']);
$descrizione_link = check($_POST['descrizione_link']);
$codice_link = check($_POST['codice_link']);

$query2 = "INSERT INTO $tabella ( `id`,`sezione_link` , `relation` , `cat` ,`sottocategoria` ,`disponibilita_link`, `tipo_link`, `codice_link`, `descrizione_link`, `link`,`aggiornato`) VALUES ('','$sezione_link','$relation','$cat','$sottocategoria','$disponibilita_link','$tipo_link','$codice_link', '$descrizione_link','$web',NOW());";

if(mysql_query($query2, CONNECT)){

@mysql_close(CONNECT);
header("Location: ./?sezione_link_id=$sezione_link");
exit;	
} else {
@mysql_close(CONNECT);
header("Location: $rct?action=9&ok=1&esito=ERROR 1101: Errore di Inserimento!&articoli=1&id=$relation");
exit;}
}
?> 