<?php

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
if($_SESSION['idh'] != $_SERVER['REMOTE_ADDR']) { echo "Non autorizzato ".$_SESSION['idh']." NOT VALID ".$_SERVER['REMOTE_ADDR']; exit; }
require('../../fl_core/settings.php');

$dir_files = "../../../set/files/".check($_GET['dir'])."/";
$file = check($_GET['file']);
$id = base64_decode(check($_GET['id_fattura']));
$fattura = GRD('fl_fatture',$id);
if(($fattura['filename'] != $file || $fattura['proprietario'] != $_SESSION['number']) && $_SESSION['usertype'] > 1) die ("Accesso negato");

$dimensione_file = filesize($dir_files.$file); 
$filetype = filetype($dir_files.$file); 



header("Content-Type: application/pdf; name=\"$file\"");
readfile($dir_files.$file);

exit;

?>