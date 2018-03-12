<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 


if(isset($_GET['cw'])) {

$_SESSION['view'] = base64_decode(check($_GET['cw']));
mysql_close(CONNECT);
header('Location: '.$_SERVER['HTTP_REFERER']);
exit;

}

if(isset($_GET['move'])) {

$_SESSION['move'] = check($_GET['move']);
$_SESSION['action'] = check($_GET['ac']);
mysql_close(CONNECT);
header('Location: '.$_SESSION['POST_BACK_PAGE']);
exit;

}

if(isset($_GET['paste'])) {



		  $element = GRD('fl_dms',$_SESSION['move']);
	  
		  $values = '';
		  $campi = '';
		  $paste = "INSERT INTO `fl_dms` (`id`";
		  foreach($element as $chiave => $valore){
			  if($chiave == 'parent_id') $newfolder = $valore = base64_decode(check($_GET['paste']));
			  if($chiave == 'file' && $element['file'] != '' && file_exists(DMS_ROOT.$element['parent_id'].'/'.$element['file'])) $newfile = $valore = time().$element['file'];
			  if($chiave != 'id' && !is_numeric($chiave)) { 
			  $campi .= ", `$chiave`";
			  $values .=  ", '$valore'";
		  }}
		  $paste = $paste.$campi.') VALUES (NULL'.$values.' );';
		  if(!mysql_query($paste,CONNECT)) { echo mysql_error(); exit; } else {
			$new_id = mysql_insert_id(CONNECT);
			if($element['file'] != '' && file_exists(DMS_ROOT.$element['parent_id'].'/'.$element['file'])){  
			  if(!is_dir(DMS_ROOT.$newfolder)){
              mkdir(DMS_ROOT.$newfolder, 0755);  
              chmod(DMS_ROOT.$newfolder, 0755);
			  }
			  $newfile = (isset($newfile)) ? $newfile : $element['file'];
			  if (!copy(DMS_ROOT.$element['parent_id'].'/'.$element['file'],DMS_ROOT.$newfolder.'/'.$newfile)){ 
			  echo 'Errore Copia in '.DMS_ROOT.$element['parent_id'].'/'.$element['file'].' > '.DMS_ROOT.$newfolder.'/'.$element['file']; exit; 
			  }}
		 }
		  
		  if($_SESSION['action'] == 'cut') { 
		  if($element['file'] != '') @unlink(DMS_ROOT.$element['parent_id'].'/'.$element['file']);
		  mysql_query('DELETE FROM fl_dms WHERE id = '.$element['id'].' LIMIT 1',CONNECT);
		  }
		  
		  unset($_SESSION['move']);
		  unset($_SESSION['action']);
		  mysql_close(CONNECT);
		  header('Location: '.$_SESSION['POST_BACK_PAGE']);
		  exit;
		  
}

 if(isset($_GET['unset'])) {
			  
		  unset($_SESSION['move']);
		  unset($_SESSION['action']);
		  mysql_close(CONNECT);
		  header('Location: '.$_SESSION['POST_BACK_PAGE']);

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




if(isset($_POST['PiD'])){

$source = $_FILES['file'];
$workflow_id = base64_decode(check($_POST['WiD']));
$folder = (isset($_POST['PiD'])) ? base64_decode(check($_POST['PiD'])) : 0;
$account_id = (isset($_POST['AiD'])) ? base64_decode(check($_POST['AiD'])) : $_SESSION['number'];
$record_id = (isset($_POST['RiD'])) ? base64_decode(check($_POST['RiD'])) : 0;
$descrizione = (isset($_POST['DeS'])) ? base64_decode(check($_POST['DeS'])) : '';
$file_name = (isset($_POST['RiD'])) ? $record_id.'_'.$source['name'] : $source['name'];

	
	

/* Check Estensione */
 $info = pathinfo($source['name']); 
foreach($info as $key => $valore){ if($key == "extension") $ext = $info["extension"]; }
if(!isset($ext)) error();
if(in_array(strtolower($ext),$formati)){ error(); } 
if(isset($_POST['NAme'])) $file_name = base64_decode(check($_POST['NAme'])).'.'.$ext;


/*Check Dir*/
if(!@is_dir(DMS_ROOT.$folder.'/')) {  
	if(!@mkdir(DMS_ROOT.$folder.'/',0777)) { 
		return $esiti[7]; mysql_close(CONNECT);  exit; } 
	}



if(!is_writable(DMS_ROOT.$folder.'/')) {  return $esiti[9]; mysql_close(CONNECT); exit; }
if(file_exists(DMS_ROOT.$folder.'/'.$file_name)) {  $file_name = time().$file_name; }


if(is_uploaded_file($source['tmp_name'])){
	if(move_uploaded_file($source['tmp_name'],DMS_ROOT.$folder.'/'.$file_name)){
	$query = "INSERT INTO `fl_dms` (`id`, `resource_type`, `account_id`, `workflow_id`, `record_id`, `parent_id`, `label`, `descrizione`, `tags`, `file`, `lang`, `proprietario`, `operatore`, `data_creazione`, `data_aggiornamento`) 
	VALUES (NULL, '1', '$account_id', '$workflow_id', '$record_id', '$folder', '$file_name', '$descrizione', '', '$file_name', 'it', '".$_SESSION['number']."', '".$_SESSION['number']."',NOW(),NOW());	";
	if(mysql_query($query,CONNECT)){ 
	
	$descrizione_invio = (isset($_REQUEST['descrizione_invio'])) ? '<p>'.check($_REQUEST['descrizione_invio']).'</p>'  : ''; 
	$allegato = (isset($_REQUEST['allega_invio'])) ? DMS_ROOT.$folder.'/'.$file_name : '';
	$allegatoName = (isset($_REQUEST['allega_invio'])) ? $file_name : '';

	if(isset($_REQUEST['notifica']) && $account_id > 1) $notifi = notifica(0,$_SESSION['number'],$account_id,'Nuovo file pubblicato sul cloud di '.client,'<div style="font-family:Helvetica;">'.$descrizione_invio.'<h4> File: '.$file_name.' </h4> <br>Sulla piattaforma puoi accedere alla risorsa pubblicata dopo aver eseguito il login. <br><a href="'.ROOT.'">Accedi da qui</a></div>',1,0,1,0,0,$allegato,$allegatoName);
	mysql_close(CONNECT);
	exit;
	
	} else {
	smail(mail_admin,'Errore Upload DMS su '.ROOT,$query.mysql_error());
	mysql_close(CONNECT);
	error();
	}

	
	} else {
	smail(mail_admin,'Errore Upload DMS su '.ROOT,"Erroe di move_uploaded_file");	
	mysql_close(CONNECT);
	error();
	}
	
}}

mysql_close(CONNECT);
header('Location: '.check($_SERVER['HTTP_REFERER']));
exit;

?>