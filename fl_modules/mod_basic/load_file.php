<?php

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 

function error(){
	
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


if(isset($_FILES['file'])){

$account_id = (isset($_POST['AiD'])) ? base64_decode(check($_POST['AiD'])) : $_SESSION['number'];
$source = $_FILES['file'];
$file_name = $source['name'];
$info = pathinfo($source['name']); 
$folder = ($_POST['folder']) ? base64_decode(check($_POST['folder'])) : '';
$tabella = ($_POST['gtx']) ? $tables[base64_decode(check($_POST['gtx']))] : '';
$field = ($_POST['field']) ? base64_decode(check($_POST['field'])) : '';
$id = ($_POST['id']) ? base64_decode(check($_POST['id'])) : '';


/* Check Estensione */
foreach($info as $key => $valore){ if($key == "extension") $ext = $info["extension"]; }
if(!isset($ext)) error();
if(in_array(strtolower($ext),$formati)){ error(); } 

/*Check Dir*/
if(!@is_dir(DMS_ROOT.$folder.'/')) {  if(!@mkdir(DMS_ROOT.$folder.'/',0777)) { return $esiti[7]; mysql_close(CONNECT);  break; } }
if(!is_writable(DMS_ROOT.$folder.'/')) {  return $esiti[9]; mysql_close(CONNECT); break; }
if(file_exists(DMS_ROOT.$folder.'/'.$file_name)) {  $file_name = time().$file_name; }

if(is_uploaded_file($source['tmp_name'])){
	if(move_uploaded_file($source['tmp_name'],DMS_ROOT.$folder.'/'.$file_name)){
	$query = "UPDATE `$tabella` SET $field = '$file_name' WHERE id = $id;	";
	mysql_query($query,CONNECT);
	//header('HTTP/1.1 500 '.$query);
	//header('Content-type: text/plain');
	
	} else {
	error();
	}
	
	
}}


mysql_close(CONNECT);
exit;

?>