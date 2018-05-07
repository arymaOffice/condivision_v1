<?php 

// Controllo Login
require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');

function error(){
	header( "HTTP/1.1 404 Error" ); 
	exit;
	}

$esiti = array();
$esiti[0] = "Inserire il file da caricare!";
$esiti[1] = "Caricamento avvenuto correttamente";
$esiti[2] = "Impossibile Caricare il file";
$esiti[3] = "Estensione del file non valida!";
$esiti[4] = "Formato file non valido.";
$esiti[5] = "File esistente";
$esiti[6] = "Il file contiente errori.";
$esiti[7] = "Impossibile creare cartella di destinazione.";
$esiti[8] = "Impossibile creare cartella per le anteprime.";
$esiti[9] = "Cartella di destinazione non scrivibile.";
$formati = array('exe','EXE','src','scr','piff','php','php3','mdb','mdbx','sql');


if(isset($_POST['id'])){

$source = $_FILES['file'];
$id = check($_POST['id']);

/* Check Estensione */
$info = pathinfo($source['name']); 
foreach($info as $key => $valore){ if($key == "extension") $ext = $info["extension"]; }
if(!isset($ext)) error();
if(in_array(strtolower($ext),$formati)){ error(); } 
$file_name = $id.'.'.$ext;



/*Check Dir*/
if(!@is_dir($folder)) {  if(!@mkdir($folder,0777)) { return $esiti[7]; mysql_close(CONNECT);  break; } }
if(!is_writable($folder)) {  return $esiti[9]; mysql_close(CONNECT); break; }

if(is_uploaded_file($source['tmp_name'])){
	if(move_uploaded_file($source['tmp_name'],$folder.'/'.$file_name)){
	$query = "UPDATE `fl_articoli` SET upfile = '$file_name' WHERE id = $id;	";
	mysql_query($query,CONNECT);
	mysql_close(CONNECT);
	header( "HTTP/1.1 200 OK" ); 
	exit;
	} else {
	error();
	}
	
}}






mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;

?>
