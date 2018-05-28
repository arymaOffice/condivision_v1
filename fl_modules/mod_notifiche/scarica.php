<?php

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
if($_SESSION['idh'] != $_SERVER['REMOTE_ADDR']) { echo "Non autorizzato ".$_SESSION['idh']." NOT VALID ".$_SERVER['REMOTE_ADDR']; exit; }
require('../../fl_core/settings.php');

$dir_files = "../../../set/files/".check($_GET['dir'])."/";
$file = $_GET['file'];
$dimensione_file = filesize($dir_files.$file); 
$filetype = filetype($dir_files.$file); 

if(strstr($file,".doc") || strstr($file,".docx")) {$filetype = "application/msword"; }

header("Content-Type: application/force-download; name=\"$file\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$dimensione_file);
header("Content-Disposition: attachment; filename=\"$file\"");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: private");
header("Pragma: no-cache");
readfile($dir_files.$file);

exit;

?>