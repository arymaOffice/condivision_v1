<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

	ini_set('display_startup_errors',1);
	error_reporting(E_ALL);



if(isset($_POST['ricetta_id'])) {

$materiaprima_id = (isset($_POST['materiaprima_id'])) ?  check($_POST['materiaprima_id']) : 0;
$semilavorato_id = (isset($_POST['semilavorato_id'])) ?  check($_POST['semilavorato_id']) : 0;
$ricetta_id = check($_POST['ricetta_id']);
$quantita = check($_POST['quantita']);
if(isset($_POST['grammi'])){ 
	$_SESSION['inserisciGrammi'] = 1;
	$quantita = $quantita/1000; 
	} else { unset($_SESSION['inserisciGrammi']); }
$scarto = (isset($_POST['scarto'])) ? check($_POST['scarto']) : 0;
$note = (isset($_POST['note'])) ? check($_POST['note']) : '';
$secquenza = (isset($_POST['secquenza'])) ? check($_POST['secquenza']) : 0;

$sql = "INSERT INTO `fl_ricettario_diba` (`id`, `ricetta_id`, `materiaprima_id`,`semilavorato_id`, `quantita`, `scarto`, `nota`, `sequenza`) 
VALUES (NULL, '$ricetta_id', '$materiaprima_id',  '$semilavorato_id', '$quantita', '$scarto', '$note', '$secquenza');";
mysql_query($sql);

$sql = "UPDATE $tabella SET data_aggiornamento = NOW(), operatore = ".$_SESSION['number']." WHERE id = $ricetta_id LIMIT 1";
mysql_query($sql);


mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;


}

if(isset($_GET['rev'])) {

$rev = check($_GET['rev']);

echo $sql = "UPDATE $tabella SET `revisione` = revisione+1, data_aggiornamento = NOW(), operatore = ".$_SESSION['number']." WHERE id = $rev LIMIT 1";
mysql_query($sql);
mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;


}



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


$info = pathinfo($source['name']); 
foreach($info as $key => $valore){ if($key == "extension") $ext = $info["extension"]; }
if(!isset($ext)) error();
if(in_array(strtolower($ext),$formati)){ error(); } 
$file_name = strtolower($id.'.jpg');



if(!is_dir($folder)) {  if(!mkdir($folder,0777)) {  mysql_close(CONNECT); die($esiti[7]);} }
if(!is_writable($folder)) {  mysql_close(CONNECT);die($esiti[9]);  }

if(is_uploaded_file($source['tmp_name'])){
	if(move_uploaded_file($source['tmp_name'],$folder.'/'.$file_name)){


	} else {
	error();
	}
	
}}






mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;

?>
